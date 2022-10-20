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
 * Contains class mod_questionnaire\output\indexpage
 *
 * @package    local_wb_faq
 * @copyright  2022 Wunderbyte Gmbh <info@wunderbyte.at>
 * @author     Georg Maißer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

namespace local_wb_faq\output;

use local_wb_faq\form\categories;
use local_wb_faq\wb_faq;
use renderable;
use renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();

/**
 * display faq list
 * @package local_wb_faq
 *
 */
class display_search implements renderable, templatable {

    /**
     * data is the array used for output.
     *
     * @var array
     */
    private $data = [];

    /**
     * uid to identify search & list which belong together.
     *
     * @var null|string
     */
    private $uid = null;

    /**
     * Constructor.
     * @param integer $categoryid
     * @param string $uid
     */
    public function __construct($categoryid = 0, string $uid) {

        $this->uid = $uid;

        $sm = new wb_faq();

        $searchrecords = $sm->buildsearchtree($categoryid);

        $data['json'] = json_encode($searchrecords, true);
        $data['root'] = $categoryid;

        $this->data = $data;
    }

    /**
     * Prepare data for use in a template
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        $data = $this->data;
        $data['uid'] = $this->uid;
        return $data;
    }
}
