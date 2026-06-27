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
 * Contains the class for the timestat block.
 *
 * @package    block_timestat
 * @copyright  2014 Barbara Dębska, Łukasz Sanokowski, Łukasz Musiał
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/timestat/locallib.php');

/**
 * Course time block class.
 *
 * @package    block_timestat
 * @copyright  2014 Barbara Dębska, Łukasz Sanokowski, Łukasz Musiał
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_timestat extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('blocktitle', 'block_timestat');
    }

    /**
     * Returns the contents.
     *
     * @return stdClass contents of block
     * @throws dml_exception
     */
    public function get_content() {
        global $CFG, $COURSE, $OUTPUT, $USER;
        if ($this->content !== null) {
            return $this->content;
        }
        $coursecontext = context_course::instance($COURSE->id);
        $config = get_config('block_timestat');
        $this->content = new stdClass();
        $this->content->text = '';
        if (!has_capability('block/timestat:view', $coursecontext)) {
            return $this->content;
        }

        $shouldtrack = block_timestat_should_track_user($coursecontext, $USER->id);
        $canseetimer = has_capability('block/timestat:viewtimer', $coursecontext);
        $data = new stdClass();
        $data->courseid = $COURSE->id;
        $data->shouldseetimer = $shouldtrack && ($canseetimer || ($config->showtimer ?? false));
        $data->initialseconds = block_timestat_get_user_course_timespent($COURSE->id, $USER->id);
        $data->initialtimeclock = block_timestat_seconds_to_clocktime($data->initialseconds);
        $data->initialtimestring = block_timestat_seconds_to_stringtime($data->initialseconds);
        $data->shouldseereport = has_capability('block/timestat:viewreport', $coursecontext);

        // Fallback for pages where the global hook is not available.
        $tracking = block_timestat_build_tracking_payload($this->page, $USER->id);
        $data->hastracking = $tracking !== null;
        $data->trackingjson = $tracking !== null
            ? json_encode($tracking, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
            : null;
        $data->trackingpayload = $data->trackingjson !== null ? base64_encode($data->trackingjson) : null;
        $data->wwwroot = $CFG->wwwroot;
        $data->pluginversion = get_config('block_timestat', 'version');
        $this->content->text = $OUTPUT->render_from_template('block_timestat/main', $data);

        if ($tracking !== null) {
            $trackerurl = new moodle_url('/blocks/timestat/js/tracker.js', ['v' => $data->pluginversion]);
            $this->page->requires->js($trackerurl);
        }

        return $this->content;
    }

    /**
     * Defines where the block can be added.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'site-index' => false,
            'course-view' => true,
            'course-view-social' => true,
            'mod' => true,
            'mod-quiz' => true,
            'course' => true,
        ];
    }

    public function has_config() {
        return true;
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function get_config_for_external() {
        $configs = get_config('block_timestat');
        return (object)[
            'instance' => new stdClass(),
            'plugin' => $configs,
        ];
    }
}
