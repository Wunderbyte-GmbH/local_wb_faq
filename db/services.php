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
 * Module Wizard external functions and service definitions.
 *
 * @package local_wb_faq
 * @category external
 * @copyright 2022 Wunderbyte GmbH (info@wunderbyte.at]
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
        'local_wb_faq_delete_entry' => [
                'classname' => 'local_wb_faq_external',
                'methodname' => 'delete_entry',
                'classpath' => 'local/wb_faq/classes/external.php',
                'description' => 'Deletes an entry by id.',
                'type' => 'read',
                'ajax' => true,
                'services' => [],
                'capabilities' => 'local/wb_faq:canedit'
        ],
        'local_wb_faq_toggle_entry_visibility' => [
                'classname' => 'local_wb_faq_external',
                'methodname' => 'toggle_entry_visibility',
                'classpath' => 'local/wb_faq/classes/external.php',
                'description' => 'Toggles visibility by entry id',
                'type' => 'read',
                'ajax' => true,
                'services' => [],
                'capabilities' => 'local/wb_faq:canedit'
        ],
        'local_wb_faq_set_wbfaq_order' => [
                'classname' => 'local_wb_faq\external\set_wbfaq_order',
                'classpath' => '',
                'description' => 'reorders Element in a FAQ branch.',
                'type' => 'write',
                'capabilities' => 'local/wb_faq:canedit',
                'ajax' => true,
        ],
        'local_wb_faq_get_faq_data' => [
                'classname' => 'local_wb_faq\external\get_faq_data',
                'classpath' => '',
                'description' => 'reload data.',
                'type' => 'write',
                'capabilities' => 'local/wb_faq:canedit',
                'ajax' => true,
        ],
        'local_wb_faq_provide_image' => [
                'classname' => 'local_wb_faq\external\provide_image',
                'classpath' => '',
                'description' => 'Provide image',
                'type' => 'read',
                'capabilities' => 'local/local_wb_faq:accessimages',
                'ajax' => 1,
        ],
];

$services = array(
        'Wunderbyte wb_faq external' => array(
                'functions' => array(
                        'local_wb_faq_delete_entry'
                ),
                'restrictedusers' => 0,
                'shortname' => 'local_wb_faq_external',
                'enabled' => 1
        )
);
