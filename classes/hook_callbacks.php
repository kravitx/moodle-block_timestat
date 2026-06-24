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

namespace block_timestat;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/timestat/locallib.php');

/**
 * Hook callbacks for Moodle 4.5+.
 */
class hook_callbacks {
    /**
     * Inject tracking HTML before the page body when supported by core.
     *
     * @param object $hook
     * @return void
     */
    public static function before_standard_top_of_body_html_generation(object $hook): void {
        self::append_tracking_bootstrap($hook);
    }

    /**
     * Inject tracking HTML before the standard footer when supported by core.
     *
     * @param object $hook
     * @return void
     */
    public static function before_standard_footer_html_generation(object $hook): void {
        self::append_tracking_bootstrap($hook);
    }

    /**
     * Append bootstrap HTML to the hook payload when possible.
     *
     * @param object $hook
     * @return void
     */
    protected static function append_tracking_bootstrap(object $hook): void {
        $html = \block_timestat_render_tracking_bootstrap();
        if ($html === '') {
            return;
        }

        if (method_exists($hook, 'add_html')) {
            $hook->add_html($html);
            return;
        }

        if (method_exists($hook, 'get_html') && method_exists($hook, 'set_html')) {
            $hook->set_html((string)$hook->get_html() . $html);
            return;
        }

        if (method_exists($hook, 'get_output') && method_exists($hook, 'set_output')) {
            $hook->set_output((string)$hook->get_output() . $html);
            return;
        }

        if (property_exists($hook, 'html')) {
            $hook->html .= $html;
            return;
        }

        if (property_exists($hook, 'output')) {
            $hook->output .= $html;
        }
    }
}
