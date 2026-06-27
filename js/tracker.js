/**
 * Browser tracking bootstrap for Timestat.
 *
 * @package block_timestat
 */
(function() {
    'use strict';

    var retryCount = 0;
    var retryTimer = null;

    var clockNow = function() {
        return window.performance && typeof window.performance.now === 'function'
            ? window.performance.now()
            : Date.now();
    };

    var scheduleRetry = function() {
        if (retryTimer !== null || retryCount >= 100) {
            return;
        }
        retryCount++;
        retryTimer = window.setTimeout(function() {
            retryTimer = null;
            startTrackers();
        }, 150);
    };

    var createClientId = function() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }
        return 'client-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2);
    };

    var readState = function(key) {
        try {
            var value = JSON.parse(window.sessionStorage.getItem(key) || 'null');
            return value && typeof value === 'object' ? value : null;
        } catch (error) {
            return null;
        }
    };

    var writeState = function(key, value) {
        try {
            window.sessionStorage.setItem(key, JSON.stringify(value));
        } catch (error) {
            // Tracking continues even when browser storage is unavailable.
        }
    };

    var startTracker = function(bootstrap) {
        if (bootstrap.getAttribute('data-timestat-initialized') === '1') {
            return;
        }

        var payload;
        try {
            payload = JSON.parse(window.atob(bootstrap.getAttribute('data-timestat-payload') || ''));
        } catch (error) {
            bootstrap.setAttribute('data-timestat-initialized', 'invalid');
            return;
        }
        if (!payload || !payload.contextid || !payload.courseid) {
            bootstrap.setAttribute('data-timestat-initialized', 'invalid');
            return;
        }

        window.blockTimestatTracker = window.blockTimestatTracker || {};
        if (window.blockTimestatTracker[payload.courseid]) {
            bootstrap.setAttribute('data-timestat-initialized', '1');
            return;
        }
        bootstrap.setAttribute('data-timestat-initialized', '1');

        var config = payload.config || {};
        var reportInterval = Math.max(parseInt(config.loginterval, 10) || 10, 10);
        var smallScreen = window.matchMedia('only screen and (max-width: 760px)').matches;
        var configuredInactivity = smallScreen ? config.inactivitytime_small : config.inactivitytime;
        var inactivityLimit = Math.max(parseInt(configuredInactivity, 10) || 10, 10) * 1000;
        var ignoreInactivity = Boolean(config.ignoreinactivity);
        var initialSeconds = Math.max(0, parseInt(payload.initialseconds, 10) || 0);
        var stateKey = 'block_timestat_tracking_' + payload.courseid;
        var stored = readState(stateKey) || {};
        var clientId = typeof stored.clientid === 'string' && stored.clientid
            ? stored.clientid
            : createClientId();
        var baseCumulativeMilliseconds = Math.max(0, Number(stored.cumulativeMilliseconds) || 0);
        var acknowledgedSeconds = Math.max(0, parseInt(stored.acknowledgedSeconds, 10) || 0);
        var requestSequence = Math.max(0, parseInt(stored.requestSequence, 10) || 0);
        var authoritativeTotal = Math.max(initialSeconds, parseInt(stored.authoritativeTotal, 10) || 0);
        var startedAt = clockNow();
        var lastActivityAt = startedAt;
        var accumulatedMilliseconds = 0;
        var lastSentCumulative = acknowledgedSeconds;
        var lastRequestAt = startedAt - (reportInterval * 1000);
        var inactive = false;
        var synchronised = false;
        var authoritativeAt = startedAt;
        var trackingActive = true;
        var lastAppliedSequence = 0;

        baseCumulativeMilliseconds = Math.max(baseCumulativeMilliseconds, acknowledgedSeconds * 1000);

        var getElements = function() {
            return {
                display: document.querySelector('.timer-display'),
                timer: document.getElementById('timer'),
                reported: document.getElementById('reportedtime'),
                inactivity: document.getElementById('inactivitytime')
            };
        };

        var formatTime = function(seconds) {
            seconds = Math.max(0, Math.floor(seconds));
            var days = Math.floor(seconds / 86400);
            var hours = Math.floor((seconds % 86400) / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var remainingSeconds = seconds % 60;
            var clock = String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(remainingSeconds).padStart(2, '0');

            if (days > 0) {
                return String(days) + 'd ' + clock;
            }

            return clock;
        };

        var getPageMilliseconds = function(now) {
            if (inactive) {
                return accumulatedMilliseconds;
            }
            var activeUntil = ignoreInactivity ? now : Math.min(now, lastActivityAt + inactivityLimit);
            return accumulatedMilliseconds + Math.max(0, activeUntil - startedAt);
        };

        var getCumulativeMilliseconds = function(now) {
            return baseCumulativeMilliseconds + getPageMilliseconds(now);
        };

        var mergeAndPersistState = function(now) {
            var cumulative = getCumulativeMilliseconds(now);
            var latest = readState(stateKey) || {};
            var latestCumulative = Math.max(0, Number(latest.cumulativeMilliseconds) || 0);
            if (latest.clientid === clientId && latestCumulative > cumulative) {
                baseCumulativeMilliseconds += latestCumulative - cumulative;
                cumulative = latestCumulative;
            }
            acknowledgedSeconds = Math.max(
                acknowledgedSeconds,
                latest.clientid === clientId ? parseInt(latest.acknowledgedSeconds, 10) || 0 : 0
            );
            authoritativeTotal = Math.max(
                authoritativeTotal,
                latest.clientid === clientId ? parseInt(latest.authoritativeTotal, 10) || 0 : 0
            );
            requestSequence = Math.max(
                requestSequence,
                latest.clientid === clientId ? parseInt(latest.requestSequence, 10) || 0 : 0
            );
            writeState(stateKey, {
                clientid: clientId,
                cumulativeMilliseconds: cumulative,
                acknowledgedSeconds: acknowledgedSeconds,
                authoritativeTotal: authoritativeTotal,
                requestSequence: requestSequence
            });
            return Math.floor(cumulative / 1000);
        };

        var getDisplayedTotal = function(now) {
            var cumulative = Math.floor(getCumulativeMilliseconds(now) / 1000);
            var pending = Math.max(0, cumulative - acknowledgedSeconds);
            if (!synchronised) {
                var storedTotal = Math.max(0, parseInt(stored.authoritativeTotal, 10) || 0);
                return Math.max(initialSeconds, storedTotal + pending);
            }
            var elapsedSinceSync = trackingActive ? Math.max(0, now - authoritativeAt) / 1000 : 0;
            return authoritativeTotal + elapsedSinceSync;
        };

        var updateDisplay = function(now) {
            var elements = getElements();
            var total = getDisplayedTotal(now);
            if (elements.timer) {
                elements.timer.textContent = formatTime(total);
            }
            if (elements.reported) {
                elements.reported.textContent = formatTime(authoritativeTotal);
            }
            if (elements.inactivity) {
                elements.inactivity.textContent = formatTime((now - lastActivityAt) / 1000);
            }
        };

        var save = function(now, force, active) {
            var cumulative = mergeAndPersistState(now);
            if (!force && cumulative <= lastSentCumulative) {
                return;
            }
            lastSentCumulative = Math.max(lastSentCumulative, cumulative);
            lastRequestAt = now;
            requestSequence++;
            var sentSequence = requestSequence;
            var sentAt = now;
            mergeAndPersistState(now);

            var request = [{
                index: 0,
                methodname: 'block_timestat_update_register',
                args: {
                    timespent: 0,
                    contextid: parseInt(payload.contextid, 10),
                    clientid: clientId,
                    cumulative: cumulative,
                    sequence: requestSequence,
                    active: active
                }
            }];
            var url = M.cfg.wwwroot + '/lib/ajax/service.php?sesskey=' +
                encodeURIComponent(M.cfg.sesskey) + '&info=block_timestat_update_register';

            window.fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                keepalive: true,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(request)
            }).then(function(response) {
                if (!response.ok) {
                    throw new Error('Timestat request failed');
                }
                return response.json();
            }).then(function(result) {
                if (!result[0] || result[0].error) {
                    throw new Error('Timestat service failed');
                }
                var data = result[0].data || {};
                acknowledgedSeconds = Math.max(acknowledgedSeconds, parseInt(data.acknowledged, 10) || 0);
                lastSentCumulative = acknowledgedSeconds;
                var receivedAt = clockNow();
                if (sentSequence >= lastAppliedSequence) {
                    lastAppliedSequence = sentSequence;
                    authoritativeTotal = Math.max(authoritativeTotal, parseInt(data.total, 10) || 0);
                    authoritativeAt = sentAt + Math.max(0, receivedAt - sentAt) / 2;
                    trackingActive = Boolean(data.trackingactive);
                    synchronised = true;
                }
                mergeAndPersistState(receivedAt);
                updateDisplay(receivedAt);
            }).catch(function() {
                // The same cumulative value is safe to retry because the API is idempotent.
                lastSentCumulative = acknowledgedSeconds;
            });
        };

        var setActive = function(now) {
            lastActivityAt = now;
            if (!inactive) {
                return;
            }
            inactive = false;
            startedAt = now;
            var elements = getElements();
            if (elements.display) {
                elements.display.classList.remove('text-black-50');
            }
            save(now, true, true);
        };

        ['click', 'scroll', 'mousemove', 'keypress', 'touchstart', 'touchmove', 'wheel'].forEach(function(eventName) {
            window.addEventListener(eventName, function() {
                setActive(clockNow());
            }, {passive: true});
        });

        window.blockTimestatTracker[payload.courseid] = {mode: 'idempotent', clientid: clientId};
        mergeAndPersistState(startedAt);
        updateDisplay(startedAt);
        save(startedAt, true, true);

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                save(clockNow(), true, !inactive);
            }
        });

        window.addEventListener('pagehide', function() {
            var now = clockNow();
            accumulatedMilliseconds = getPageMilliseconds(now);
            inactive = true;
            mergeAndPersistState(now);
            save(now, true, false);
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                var now = clockNow();
                inactive = false;
                startedAt = now;
                lastActivityAt = now;
                save(now, true, true);
            }
        });

        window.setInterval(function() {
            var now = clockNow();
            if (!ignoreInactivity && !inactive && now - lastActivityAt >= inactivityLimit) {
                accumulatedMilliseconds = getPageMilliseconds(now);
                inactive = true;
                var elements = getElements();
                if (elements.display) {
                    elements.display.classList.add('text-black-50');
                }
                save(now, true, false);
            }

            mergeAndPersistState(now);
            updateDisplay(now);
            var cumulative = Math.floor(getCumulativeMilliseconds(now) / 1000);
            if (cumulative - acknowledgedSeconds >= reportInterval) {
                save(now, false, !inactive);
            } else if (now - lastRequestAt >= reportInterval * 1000) {
                // Inactive browsers still poll so they show time counted by another browser.
                save(now, true, !inactive);
            }
        }, 1000);
    };

    var startTrackers = function() {
        if (!window.M || !M.cfg || !M.cfg.wwwroot || !M.cfg.sesskey) {
            scheduleRetry();
            return;
        }
        var bootstraps = document.querySelectorAll('[data-timestat-payload]');
        if (!bootstraps.length) {
            scheduleRetry();
            return;
        }
        Array.prototype.forEach.call(bootstraps, startTracker);
    };

    window.blockTimestatStart = startTrackers;
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startTrackers, {once: true});
    } else {
        startTrackers();
    }
}());
