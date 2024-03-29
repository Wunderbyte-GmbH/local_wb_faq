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
 * This class contains a list of webservice functions related to the Groupmanager Module by Wunderbyte.
 *
 * @package    local_wb_faq
 * @copyright  2022 Georg Maißer <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace local_wb_faq\external;

use context_system;
use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use moodle_exception;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

class provide_image extends external_api {


    /**
     * Describes the paramters for add_item_to_cart.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters(array(
                'postid' => new external_value(PARAM_INT, 'action'),
                'filename' => new external_value(PARAM_TEXT, 'filename'),
            )
        );
    }

    /**
     * Webservice for groupmanager to return the information of a given client.
     *
     * @param integer $postid
     * @param string $filename
     *
     * @return array
     */
    public static function execute(int $postid, string $filename): array {
        $params = self::validate_parameters(self::execute_parameters(), [
            'postid' => $postid,
            'filename' => $filename,
        ]);

        $context = context_system::instance();
        if (!has_capability('local/wb_faq:accessimages', $context)) {
            throw new moodle_exception('youdonthavetheright', 'local_wb_faq');
        }

        return synchronize_ticket_post::return_image_by_postid($params['postid'], $params['filename']);
    }

    /**
     * Returns description of method result value.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure(array(
                'base64binaries' => new external_value(PARAM_RAW, 'Base 64 encoded files'),
                'fileName' => new external_value(PARAM_TEXT, 'Filename'),
                'mediaType' => new external_value(PARAM_TEXT, 'data returend'),
            )
        );
    }
}
