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
 * Plugin event observers are registered here.
 *
 * @package local_entites
 * @copyright 2022 Wunderbyte Gmbh <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wb_faq\output;

use plugin_renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer class.
 * @package mod_mooduell
 */
class renderer extends plugin_renderer_base {

    /**
     * Render a faq list.
     *
     * @param templatable $viewpage
     * @return string|boolean
     */
    public function render_list_faq(templatable $data) {
        $data = $data->export_for_template($this);
        return $this->render_from_template('local_wb_faq/faq_list', $data);
    }

    /**
     * Render a searchfield.
     *
     * @param templatable $viewpage
     * @return string|boolean
     */
    public function render_display_search(templatable $data) {
        $data = $data->export_for_template($this);
        return $this->render_from_template('local_wb_faq/display_search', $data);
    }
}
