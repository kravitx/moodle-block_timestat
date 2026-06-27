<?php

namespace block_timestat\privacy;

use coding_exception;
use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\{approved_contextlist, approved_userlist, contextlist, core_userlist_provider, transform, userlist, writer};
use dml_exception;

class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    core_userlist_provider {


    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'block_timestat',
            [
                'log_id' => 'privacy:metadata:block_timestat:log_id',
                'timespent' => 'privacy:metadata:block_timestat:timespent',
            ],
            'privacy:metadata:block_timestat'
        );
        $collection->add_database_table(
            'block_timestat_session',
            [
                'userid' => 'privacy:metadata:block_timestat_session:userid',
                'courseid' => 'privacy:metadata:block_timestat_session:courseid',
                'clientid' => 'privacy:metadata:block_timestat_session:clientid',
                'reportedseconds' => 'privacy:metadata:block_timestat_session:reportedseconds',
                'lastsequence' => 'privacy:metadata:block_timestat_session:lastsequence',
                'active' => 'privacy:metadata:block_timestat_session:active',
                'timemodified' => 'privacy:metadata:block_timestat_session:timemodified',
            ],
            'privacy:metadata:block_timestat_session'
        );
        $collection->add_database_table(
            'block_timestat_account',
            [
                'userid' => 'privacy:metadata:block_timestat_account:userid',
                'courseid' => 'privacy:metadata:block_timestat_account:courseid',
                'accounteduntil' => 'privacy:metadata:block_timestat_account:accounteduntil',
            ],
            'privacy:metadata:block_timestat_account'
        );
        return $collection;
    }

    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        $sql = "SELECT lsl.userid
                FROM {block_timestat} bt
                JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
                WHERE lsl.contextid = :contextid";
        $params = ['contextid' => $context->id];
        $userlist->add_from_sql('userid', $sql, $params);
        if ($context->contextlevel === CONTEXT_COURSE) {
            $userlist->add_from_sql('userid',
                'SELECT userid FROM {block_timestat_session} WHERE courseid = :sessioncourseid',
                ['sessioncourseid' => $context->instanceid]);
            $userlist->add_from_sql('userid',
                'SELECT userid FROM {block_timestat_account} WHERE courseid = :accountcourseid',
                ['accountcourseid' => $context->instanceid]);
        }
    }

    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        $userids = $userlist->get_userids();

        [$insql, $inparams] = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $sql = "SELECT bt.id
                FROM {block_timestat} bt
                JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
                WHERE lsl.userid $insql";
        $records = $DB->get_records_sql($sql, $inparams);

        foreach ($records as $record) {
            $DB->delete_records('block_timestat', ['id' => $record->id]);
        }
        $DB->delete_records_select('block_timestat_session', "userid $insql", $inparams);
        $DB->delete_records_select('block_timestat_account', "userid $insql", $inparams);
    }

    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT DISTINCT lsl.contextid
                FROM {block_timestat} bt
                JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
                WHERE lsl.userid = :userid";
        $params = ['userid' => $userid];
        $contextlist->add_from_sql($sql, $params);

        $contextlist->add_from_sql(
            'SELECT ctx.id
               FROM {context} ctx
               JOIN {block_timestat_session} bts ON bts.courseid = ctx.instanceid
              WHERE ctx.contextlevel = :contextlevel AND bts.userid = :sessionuserid',
            ['contextlevel' => CONTEXT_COURSE, 'sessionuserid' => $userid]
        );

        return $contextlist;
    }

    /**
     * @throws dml_exception|coding_exception
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $data = [];
        $userid = (int)$contextlist->get_user()->id;

        $sql = "SELECT lsl.id AS logid, lsl.courseid, bt.timespent, lsl.timecreated AS timestart, lsl.contextid
            FROM {block_timestat} bt
            JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
            WHERE lsl.userid = :userid";

        $results = $DB->get_records_sql($sql, ['userid' => $userid]);

        foreach ($results as $result) {
            $data[$result->contextid][] =
                (object)[
                    'log_id' => $result->logid,
                    'timespent' => $result->timespent,
                    'timestart' => transform::datetime($result->timestart),
                ];
        }

        if (!empty($data)) {
            foreach ($contextlist as $context) {
                if (empty($data[$context->id])) {
                    continue;
                }
                $contextdata = (object)['block_timestat' => $data[$context->id]];
                writer::with_context($context)->export_data(
                    [get_string('privacy:metadata:block_timestat', 'block_timestat')],
                    $contextdata
                );
            }
        }
    }

    /**
     * Delete all personal data for all users in the specified context.
     *
     * @param context $context $context Context to delete data from.
     * @throws dml_exception
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        global $DB;
        $sql = "SELECT bt.id
                FROM {block_timestat} bt
                JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
                WHERE lsl.contextid = :contextid";
        $params = ['contextid' => $context->id];
        $records = $DB->get_records_sql($sql, $params);

        foreach ($records as $record) {
            $DB->delete_records('block_timestat', ['id' => $record->id]);
        }
        if ($context->contextlevel === CONTEXT_COURSE) {
            $DB->delete_records('block_timestat_session', ['courseid' => $context->instanceid]);
            $DB->delete_records('block_timestat_account', ['courseid' => $context->instanceid]);
        }
    }

    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $contextids = $contextlist->get_contextids();
        [$insql, $inparams] = $DB->get_in_or_equal($contextids, SQL_PARAMS_NAMED);
        $user = $contextlist->get_user();

        $sql = "SELECT bt.id
                FROM {block_timestat} bt
                JOIN {logstore_standard_log} lsl ON bt.log_id = lsl.id
                WHERE lsl.userid = :userid AND lsl.contextid $insql";
        $params = ['userid' => $user->id] + $inparams;
        $records = $DB->get_records_sql($sql, $params);

        foreach ($records as $record) {
            $DB->delete_records('block_timestat', ['id' => $record->id]);
        }
        $courseids = $DB->get_fieldset_select('context', 'instanceid',
            'contextlevel = :courselevel AND id ' . $insql,
            ['courselevel' => CONTEXT_COURSE] + $inparams);
        if ($courseids) {
            [$courseinsql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'course');
            $courseparams['trackinguserid'] = $user->id;
            $DB->delete_records_select('block_timestat_session',
                "userid = :trackinguserid AND courseid $courseinsql", $courseparams);
            $DB->delete_records_select('block_timestat_account',
                "userid = :trackinguserid AND courseid $courseinsql", $courseparams);
        }
        return count($records);
    }
}
