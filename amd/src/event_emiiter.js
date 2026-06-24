/**
 * Event emitter for timestat block screen time tracking.
 *
 * @module     block_timestat/event_emiiter
 * @copyright  2024
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ScreenTime from 'block_timestat/screentime';
import ajax from 'core/ajax';

export const init = (trackingData, legacyConfig = null) => {
    const payload = normalizePayload(trackingData, legacyConfig);
    if (!payload || !payload.contextid) {
        return;
    }

    window.blockTimestatTracker = window.blockTimestatTracker || {};
    if (window.blockTimestatTracker[payload.contextid]) {
        return;
    }
    window.blockTimestatTracker[payload.contextid] = true;

    const reportInterval = getReportInterval(payload.config || {});
    const inactiveInterval = getInactiveInterval(payload.config || {});

    const $timerDisplay = payload.showtimer ? document.querySelector('.timer-display') : null;
    const $timer = document.getElementById('timer');
    const $reportedtime = document.getElementById('reportedtime');
    const $inactivitytime = document.getElementById('inactivitytime');
    const initialSeconds = Number.isInteger(payload.initialseconds) ?
        payload.initialseconds :
        ($timer ? parseInt($timer.dataset.initialSeconds || '0', 10) : 0);

    const inactiveClass = 'text-black-50';
    const screentime = new ScreenTime({
        field: {name: 'content', selector: 'body'},
        reportInterval: reportInterval,
        inactiveInterval: inactiveInterval,
        onReport: async (log) => {
            if (!log.body) {
                return;
            }
            const timespent = log.body;
            const contextIdInt = parseInt(payload.contextid, 10);
            try {
                await ajax.call([{
                    methodname: 'block_timestat_update_register',
                    args: {
                        timespent: timespent,
                        contextid: contextIdInt
                    }
                }]);
            } catch (err) {
                // Silently fail; service errors are not shown in UI.
            }
            if (!$reportedtime) {
                return;
            }
            const totalSeconds = initialSeconds + (log.body || 0);
            $reportedtime.textContent = formatTime(totalSeconds);
        },
        everySecondCallback: (log) => {
            const sessionSeconds = log['body'] || 0;
            const seconds = initialSeconds + sessionSeconds;
            if ($timer) {
                $timer.textContent = formatTime(seconds);
                if ($inactivitytime) {
                    $inactivitytime.textContent = formatTime(screentime.inactivityTimer);
                }
            }
        },
        onInactivity: () => {
            if (!$timerDisplay) {
                return;
            }
            $timerDisplay.classList.add(inactiveClass);
        },
        onStart: () => {
            if (!$timerDisplay) {
                return;
            }
            $timerDisplay.classList.remove(inactiveClass);
        }
    });
};

const normalizePayload = (trackingData, legacyConfig) => {
    if (typeof trackingData === 'object' && trackingData !== null && !Array.isArray(trackingData)) {
        return {
            ...trackingData,
            config: trackingData.config || {}
        };
    }

    return {
        contextid: parseInt(trackingData, 10),
        config: legacyConfig || {},
        showtimer: !!(legacyConfig && legacyConfig.showtimer)
    };
};

const formatTime = (seconds) => {
    return new Date(seconds * 1000).toISOString().substring(11, 19);
};

const getInactiveInterval = (config) => {
    const isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
    let {inactivitytime, inactivitytime_small} = config;
    inactivitytime = isMobile ? inactivitytime_small : inactivitytime;
    inactivitytime = inactivitytime && inactivitytime >= 10 ? inactivitytime : 10;
    return inactivitytime;
};

const getReportInterval = (config) => {
    let reportInterval = config.loginterval || 10;
    reportInterval = reportInterval < 10 ? 10 : reportInterval;
    return reportInterval;
};
