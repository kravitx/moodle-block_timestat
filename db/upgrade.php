<?php
// This file is part of Moodle - http://moodle.org/.

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for block_timestat.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_timestat_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026062711) {
        $table = new xmldb_table('block_timestat_session');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('clientid', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL);
        $table->add_field('reportedseconds', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('courseid_fk', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
        $table->add_index('usercourseclient_uix', XMLDB_INDEX_UNIQUE, ['userid', 'courseid', 'clientid']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $accounttable = new xmldb_table('block_timestat_account');
        $accounttable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $accounttable->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $accounttable->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $accounttable->add_field('accounteduntil', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $accounttable->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

        $accounttable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $accounttable->add_key('userid_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $accounttable->add_key('courseid_fk', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
        $accounttable->add_index('usercourse_uix', XMLDB_INDEX_UNIQUE, ['userid', 'courseid']);

        if (!$dbman->table_exists($accounttable)) {
            $dbman->create_table($accounttable);
        }

        upgrade_block_savepoint(true, 2026062711, 'timestat');
    }

    if ($oldversion < 2026062712) {
        $table = new xmldb_table('block_timestat_session');
        $field = new xmldb_field('lastsequence', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0', 'reportedseconds');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('active', XMLDB_TYPE_INTEGER, '1', null,
            XMLDB_NOTNULL, null, '0', 'lastsequence');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_block_savepoint(true, 2026062712, 'timestat');
    }

    return true;
}
