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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/locallib.php');

/**
 * Inject tracking before the footer when the current page is trackable.
 *
 * @return string
 */
function block_timestat_before_footer(): string {
    return block_timestat_render_tracking_bootstrap();
}

/**
 * Fallback hook for renderers that print content before the body.
 *
 * @return string
 */
function block_timestat_before_standard_top_of_body_html(): string {
    return block_timestat_render_tracking_bootstrap();
}

