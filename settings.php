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
 * Online users block settings.
 *
 * @package    block_timestat
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if (!class_exists('block_timestat_admin_setting_min10')) {
    class block_timestat_admin_setting_min10 extends admin_setting_configtext {
        public function validate($data) {
            if (!is_numeric($data) || (int)$data < 10) {
                return get_string('err_min10', 'block_timestat');
            }
            return parent::validate($data);
        }
    }
}

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_timestat/showtimer',
        get_string('showtimer', 'block_timestat'),
        get_string('showtimer_desc', 'block_timestat'), 0));

    $settings->add(new block_timestat_admin_setting_min10('block_timestat/loginterval',
        get_string('loginterval', 'block_timestat'),
        get_string('loginterval_desc', 'block_timestat'), 15, PARAM_INT));

    $settings->add(new block_timestat_admin_setting_min10('block_timestat/inactivitytime',
        get_string('inactivitytime', 'block_timestat'),
        get_string('inactivitytime_desc', 'block_timestat'), 30, PARAM_INT));

    $settings->add(new block_timestat_admin_setting_min10('block_timestat/inactivitytime_small',
        get_string('inactivitytime_small', 'block_timestat'),
        get_string('inactivitytime_small_desc', 'block_timestat'), 30, PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('block_timestat/ignoreinactivity',
        get_string('ignoreinactivity', 'block_timestat'),
        get_string('ignoreinactivity_desc', 'block_timestat'), 0));

    $settings->add(new admin_setting_configcheckbox('block_timestat/trackeditingteachers',
        get_string('trackeditingteachers', 'block_timestat'),
        get_string('trackeditingteachers_desc', 'block_timestat'), 0));

    $settings->add(new admin_setting_configcheckbox('block_timestat/trackteachers',
        get_string('trackteachers', 'block_timestat'),
        get_string('trackteachers_desc', 'block_timestat'), 0));
}
