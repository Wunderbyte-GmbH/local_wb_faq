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
 * Shortcodes for local_wb_faq
 *
 * @package local_wb_faq
 * @subpackage db
 * @since Moodle 3.11
 * @copyright 2022 Georg Maißer
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_wb_faq;

use local_wb_faq\output\display_search;
use local_wb_faq\output\faq_list;

/**
 * Deals with local_shortcodes regarding booking.
 */
class shortcodes {

    public static function show_faq($shortcode, $args, $content, $env, $next) {

        global $PAGE;

        if (isset($args['cat'])) {
            $category = ($args['cat']);
        }

        if (!$categoryid = settings_manager::return_category_id_by_name($category)) {
            return get_string('categorynotfound', 'local_wb_faq');
        }

        $renderer = $PAGE->get_renderer('local_wb_faq');
        $data = new display_search($categoryid);
        $out = $renderer->render_display_search($data);

        $data = new faq_list($categoryid);
        $out .= $renderer->render_list_faq($data);

        return $out;
    }
}
