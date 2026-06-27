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
 *
 * Lang strings for the timestat block.
 *
 * @package    block_timestat
 * @copyright  2014 Barbara Dębska, Łukasz Sanokowski, Łukasz Musiał
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockname'] = 'Timestat';
$string['pluginname'] = 'Timestat';
$string['blocktitle'] = 'Time connected to course';
$string['nologs'] = 'No logs found!';
$string['calculate'] = 'Calculate';
$string['viewreport'] = 'View report';
$string['summary'] = 'Total course time';
$string['start'] = 'Start:';
$string['end'] = 'End:';
$string['days'] = ' days ';
$string['hours'] = ' hours ';
$string['minuts'] = ' minutes ';
$string['seconds'] = ' seconds ';
$string['time'] = 'Time';
$string['timespent'] = 'Time spent';
$string['choosetimeperiod'] = 'Choose time period';
$string['loginterval'] = 'Log interval (seconds)';
$string['loginterval_desc'] = 'The time interval in which the user\'s activity is logged. The minimum value is 10 seconds.';
$string['inactivitytime'] = 'Inactivity time (big screens) (seconds)';
$string['inactivitytime_desc'] = 'The time in seconds after which the user is considered inactive. The minimum value is 10 seconds.';
$string['inactivitytime_small'] = 'Inactivity time (small screens)';
$string['inactivitytime_small_desc'] = 'The time in seconds after which the user is considered inactive when the user\'s activity is logged in small screens. The minimum value is 10 seconds.';
$string['ignoreinactivity'] = 'Ignore inactivity';
$string['ignoreinactivity_desc'] = 'If enabled, time tracking continues while the page remains open even when the user does not interact with it.';
$string['loginterval_help'] = 'The time interval in which the user\'s activity is logged.';
$string['showtimer'] = 'Show timer';
$string['showtimer_desc'] = 'If enabled, the time counter will be visible to all enrolled users. If disabled, the time counter will be visible only to users with the "block/timestat:viewtimer" capability.';
$string['reportedtime'] = 'Reported time';
$string['loading'] = 'Loading...';
$string['notrackinglog'] = 'No valid tracking log was found for this request.';
$string['timestat:viewreport'] = 'View report';
$string['timestat:viewtimer'] = 'View timer';
$string['timestat:addinstance'] = 'Add a new Connected time to course block';
$string['timestat:view'] = 'View the Connected time to course block';
$string['privacy:metadata:block_timestat'] = 'Information about the time spent by the user in a specific log entry.';
$string['privacy:metadata:block_timestat:log_id'] = 'The ID of the user related to the log entry.';
$string['privacy:metadata:block_timestat:timespent'] = 'The time spent by the user in the log entry.';
$string['privacy:metadata:block_timestat_session'] = 'Browser tracking state used to prevent duplicate time records.';
$string['privacy:metadata:block_timestat_session:userid'] = 'The user being tracked.';
$string['privacy:metadata:block_timestat_session:courseid'] = 'The course being tracked.';
$string['privacy:metadata:block_timestat_session:clientid'] = 'A random browser session identifier.';
$string['privacy:metadata:block_timestat_session:reportedseconds'] = 'The cumulative seconds received from this browser session.';
$string['privacy:metadata:block_timestat_session:lastsequence'] = 'The last ordered request received from this browser session.';
$string['privacy:metadata:block_timestat_session:active'] = 'Whether this browser session is currently counting time.';
$string['privacy:metadata:block_timestat_session:timemodified'] = 'The last time this browser session reported activity.';
$string['privacy:metadata:block_timestat_account'] = 'Shared tracking state used to merge simultaneous browser sessions.';
$string['privacy:metadata:block_timestat_account:userid'] = 'The user being tracked.';
$string['privacy:metadata:block_timestat_account:courseid'] = 'The course being tracked.';
$string['privacy:metadata:block_timestat_account:accounteduntil'] = 'The end of the latest interval already counted.';
$string['cannotacquirelock'] = 'The tracking state is busy. The request can be retried safely.';
$string['selectauser'] = 'Select a user';
$string['sortby'] = 'Sort by';
$string['sort_timespent_desc'] = 'Time spent (highest first)';
$string['sort_lastname_asc'] = 'Surname';
$string['sort_firstname_asc'] = 'First name';
$string['trackeditingteachers'] = 'Track editing teachers';
$string['trackeditingteachers_desc'] = 'Allow time tracking for users with the editing teacher role.';
$string['trackteachers'] = 'Track non-editing teachers';
$string['trackteachers_desc'] = 'Allow time tracking for users with the non-editing teacher role.';
$string['err_min10'] = 'The value must be a number greater than or equal to 10.';
