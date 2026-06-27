<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the external API for this component.
 *
 * @package    block_timestat
 * @copyright  2022 Jorge C. {}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_timestat;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/blocks/timestat/locallib.php');

use context;
use core_course\external\course_summary_exporter;
use dml_exception;
use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use invalid_parameter_exception;
use moodle_exception;
use context_course;

/**
 * This is the external API for this component.
 *
 * @copyright  2020 Mathew May {@link https://mathew.solutions}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * update_register_parameters
     *
     * @return external_function_parameters
     */
    public static function update_register_parameters(): external_function_parameters {
        return new external_function_parameters(
                [
                        'timespent' => new external_value(PARAM_INT),
                        'contextid' => new external_value(PARAM_INT),
                        'clientid' => new external_value(PARAM_ALPHANUMEXT, '', VALUE_DEFAULT, ''),
                        'cumulative' => new external_value(PARAM_INT, '', VALUE_DEFAULT, 0),
                        'sequence' => new external_value(PARAM_INT, '', VALUE_DEFAULT, 0),
                        'active' => new external_value(PARAM_BOOL, '', VALUE_DEFAULT, true),
                ]
        );
    }

    /**
     *
     * Update the register to save the timespent in a specific log.
     *
     * @param int $timespent The user time spent
     * @param int $contextid The log id
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws moodle_exception
     */
    public static function update_register(
            int $timespent,
            int $contextid,
            string $clientid = '',
            int $cumulative = 0,
            int $sequence = 0,
            bool $active = true
    ): array {
        global $DB, $USER;

        $params = self::validate_parameters(
                self::update_register_parameters(),
                [
                    'timespent' => $timespent,
                    'contextid' => $contextid,
                    'clientid' => $clientid,
                    'cumulative' => $cumulative,
                    'sequence' => $sequence,
                    'active' => $active,
                ]
        );
        if (\core_text::strlen($params['clientid']) > 64) {
            throw new invalid_parameter_exception('clientid must not exceed 64 characters');
        }
        $params['cumulative'] = max(0, $params['cumulative']);
        $params['sequence'] = max(0, $params['sequence']);
        $log = block_timestat_get_user_last_log_by_contextid($contextid);
        if (!$log || (int)$log->userid !== (int)$USER->id) {
            throw new moodle_exception('notrackinglog', 'block_timestat');
        }
        $coursecontext = context_course::instance($log->courseid);
        self::validate_context($coursecontext);
        require_capability('block/timestat:view', $coursecontext);

        $lockfactory = \core\lock\lock_config::get_lock_factory('block_timestat');
        $lock = $lockfactory->get_lock('user_' . $USER->id . '_course_' . $log->courseid, 5);
        if (!$lock) {
            throw new moodle_exception('cannotacquirelock', 'block_timestat');
        }

        try {
            $transaction = $DB->start_delegated_transaction();
            if ($params['clientid'] === '') {
                $accepted = max(0, min($params['timespent'], block_timestat_get_max_reportable_seconds()));
                self::increment_log_time((int)$log->id, $accepted);
                $result = [
                    'total' => block_timestat_get_user_course_timespent((int)$log->courseid, (int)$USER->id),
                    'acknowledged' => 0,
                    'trackingactive' => true,
                ];
                $transaction->allow_commit();
                return $result;
            }

            $now = time();
            $sessionparams = [
                'userid' => (int)$USER->id,
                'courseid' => (int)$log->courseid,
                'clientid' => $params['clientid'],
            ];
            $session = $DB->get_record('block_timestat_session', $sessionparams);
            if (!$session) {
                $session = (object)($sessionparams + [
                    'reportedseconds' => 0,
                    'lastsequence' => 0,
                    'active' => 0,
                    'timecreated' => $now,
                    'timemodified' => $now,
                ]);
                $session->id = $DB->insert_record('block_timestat_session', $session);
            }

            $requested = max(0, $params['cumulative'] - (int)$session->reportedseconds);
            $elapsed = max(0, $now - (int)$session->timemodified);
            $maximum = max(block_timestat_get_max_reportable_seconds(),
                $elapsed + block_timestat_get_max_reportable_seconds());
            $accepted = min($requested, $maximum);

            $accountparams = ['userid' => (int)$USER->id, 'courseid' => (int)$log->courseid];
            $account = $DB->get_record('block_timestat_account', $accountparams);
            if (!$account) {
                $account = (object)($accountparams + ['accounteduntil' => $now, 'timemodified' => $now]);
                $account->id = $DB->insert_record('block_timestat_account', $account);
            }

            $leasetime = block_timestat_get_max_reportable_seconds();
            $hasactivebrowser = $DB->record_exists_select(
                'block_timestat_session',
                'userid = :activeuserid AND courseid = :activecourseid AND active = 1 AND timemodified >= :activeafter',
                [
                    'activeuserid' => (int)$USER->id,
                    'activecourseid' => (int)$log->courseid,
                    'activeafter' => $now - $leasetime,
                ]
            );

            $newseconds = 0;
            $advanceaccount = false;
            if ($hasactivebrowser) {
                $newseconds = max(0, $now - (int)$account->accounteduntil);
                $advanceaccount = true;
            } elseif ($accepted > 0) {
                // Only add the part of this client's interval not already covered globally.
                $claimstart = $now - $accepted;
                $newseconds = max(0, $now - max((int)$account->accounteduntil, $claimstart));
                $advanceaccount = true;
            } elseif ($params['active']) {
                // Starting after a real gap establishes a new frontier without counting offline time.
                $advanceaccount = true;
            }
            self::increment_log_time((int)$log->id, $newseconds);
            if ($advanceaccount) {
                $account->accounteduntil = $now;
                $account->timemodified = $now;
                $DB->update_record('block_timestat_account', $account);
            }

            if ($accepted > 0) {
                $session->reportedseconds += $accepted;
            }
            if ($params['sequence'] > (int)$session->lastsequence) {
                $session->lastsequence = $params['sequence'];
                $session->active = $params['active'] ? 1 : 0;
            }
            $session->timemodified = $now;
            $DB->update_record('block_timestat_session', $session);

            $trackingactive = $DB->record_exists_select(
                'block_timestat_session',
                'userid = :resultuserid AND courseid = :resultcourseid AND active = 1 '
                    . 'AND timemodified >= :resultactiveafter',
                [
                    'resultuserid' => (int)$USER->id,
                    'resultcourseid' => (int)$log->courseid,
                    'resultactiveafter' => $now - $leasetime,
                ]
            );

            $result = [
                'total' => block_timestat_get_user_course_timespent((int)$log->courseid, (int)$USER->id),
                'acknowledged' => (int)$session->reportedseconds,
                'trackingactive' => $trackingactive,
            ];
            $transaction->allow_commit();
            return $result;
        } catch (\Throwable $exception) {
            if (isset($transaction)) {
                $transaction->rollback($exception);
            }
            throw $exception;
        } finally {
            $lock->release();
        }
    }

    /**
     * update_register_returns.
     *
     * @return \external_description
     */
    public static function update_register_returns() {
        return new external_single_structure([
            'total' => new external_value(PARAM_INT),
            'acknowledged' => new external_value(PARAM_INT),
            'trackingactive' => new external_value(PARAM_BOOL),
        ]);
    }

    /**
     * Atomically add seconds to the record associated with a log entry.
     *
     * @param int $logid
     * @param int $seconds
     * @return void
     */
    private static function increment_log_time(int $logid, int $seconds): void {
        global $DB;

        if ($seconds <= 0) {
            return;
        }

        $record = $DB->get_record('block_timestat', ['log_id' => $logid], 'id', IGNORE_MULTIPLE);
        if (!$record) {
            $DB->insert_record('block_timestat', (object)['log_id' => $logid, 'timespent' => $seconds]);
            return;
        }

        $DB->execute(
            'UPDATE {block_timestat} SET timespent = COALESCE(timespent, 0) + :seconds WHERE id = :id',
            ['seconds' => $seconds, 'id' => $record->id]
        );
    }
}
