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
 * local wb_faq
 *
 * @package     local_wb_faq
 * @author      Thomas Winkler
 * @copyright   2022 Wunderbyte GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wb_faq;

use local_wb_faq_external;
use stdClass;

defined('MOODLE_INTERNAL') || die;

/**
 * Class settings_manager
 * @author      Thomas Winkler
 * @copyright   2022 Wunderbyte GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class settings_manager {

    private $id;

    /**
     * entity constructor.
     *
     */
    public function __construct(int $id = null) {
        global $DB;
        if ($id == null) {
            $this->id = $id;
            $this->data = new stdClass();
            $this->data->id = $this->id;
        } else {
            $this->data = $DB->get_record('local_wb_faq_entry', array('id' => $id));
            $this->id = $this->data->id;
            $this->title = $this->data->title;
        }
    }

    /**
     * Updates the cache
     *
     * @return void
     */
    private function update_cache() {
        $cache = \cache::make('local_wb_faq', 'faqcache');
        $cachekey = 'faq_cache';
        $cachedrawdata = $this->load_tree();
        $cache->set($cachekey, $cachedrawdata);
    }

    public function buildtree($elements, $parentid = 0, $depth = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parentid == $parentid) {
                $children = $this->buildtree($elements, $element->id, $depth++);
                if ($children) {
                    $element->children[] = $children;
                }
                $prefix = "";
                for ($i = 0; $i <= $depth; $i++) {
                    $prefix .= "-";
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function buildchildren(&$array, $node, $delimiter) {
        if (!isset($node->children)) {
            return;
        }
        foreach ($node->children as $row => $child) {
            foreach ($child as $row2 => $c) {
                $array[$c->id] = $delimiter."".$c->title;

                if (isset($c->children)) {
                    $this->buildchildren($array, $c, $delimiter.'-');
                }
            }
        }
    }

    public function buildselect(int $root) {
        global $DB;
        $categories = $DB->get_records_sql("SELECT * FROM {local_wb_faq_entry} WHERE type = '0' ORDER BY parentid, type ");
        $tree = $this->buildtree($categories, $root);
        $option = [];
        $nodes = $tree;

        foreach ($nodes as $node) {
            $option[$node->id] = $node->title;
            if (isset($node->children)) {
                $this->buildchildren($option, $node, '-');
            }
        }
        return $option;
    }

    public function buildsearch($elements, $parentid = 0, $depth = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parentid == $parentid) {
                $children = $this->buildsearch($elements, $element->id, $depth++);
                if ($children) {
                    $element->children[] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function buildsearchchildren(&$array, $node) {

        if (!isset($node->children)) {
            return;
        }
        foreach ($node->children as $row => $child) {
            foreach ($child as $row2 => $c) {
                $array[$c->id] = $c;
                if (isset($c->children)) {
                    $this->buildsearchchildren($array, $c);
                }
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param integer $root
     * @return void
     */
    public function buildsearchtree(int $root) {
        global $DB;
        $entries = $DB->get_records_sql("SELECT * FROM {local_wb_faq_entry} ORDER BY type, parentid");
        $tree = $this->buildsearch($entries, $root);
        $option = [];
        $nodes = $tree;

        foreach ($nodes as $node) {
            $option[$node->id] = $node;
            if (isset($node->children)) {
                $this->buildsearchchildren($option, $node);
            }
        }
        return array_values($option);
    }



    /**
     * Returns the parent-child tree as array or json string
     *
     * @param boolean $json
     * @param int $root - root level from faq
     * @return mixed
     */
    public function load_from_cache(bool $json = false, $root = null) {

        $cache = \cache::make('local_wb_faq', 'faqcache');
        $cachekey = 'faq_cache';
        $cachedrawdata = $cache->get($cachekey);
        if (!$cachedrawdata) {
            $this->update_cache();
            $cachedrawdata = $cache->get($cachekey);
        }
        if ($root) {
            $cachedrawdata[$root]->toplevel = true;
        }
        if ($json) {
            return json_encode($cachedrawdata, true);
        }
        return $cachedrawdata;
    }
    /**
     * Loads the tree with parent children nodes
     *
     * @return array
     */
    public function load_tree() {
        global $DB;
        $records = $DB->get_records_sql("SELECT * FROM {local_wb_faq_entry} faq ORDER BY parentid, type ASC");
        $recordsvalues = array_values($records);
        $dataarr = [];
        foreach ($recordsvalues as $record) {
            if ($record->type == 0) {
                $dataarr[$record->id] = $record;
                $dataarr[$record->parentid]->categories[] = $record;
                // $dataarr[$record->parentid]->parenttitle = $dataarr[$dataarr[$record->parentid]->parentid]->title;
                if ($record->parentid == 0) {
                    // TODO add string
                    $dataarr[$record->parentid]->title = "FAQ";
                    $dataarr[$record->parentid]->toplevel = true;
                    $dataarr[$record->parentid]->parentid = 0;
                }
            }
            if ($record->type == 1) {
                $dataarr[$record->parentid]->entries[] = $record;
            }
        }
        return $dataarr;
    }

    public function category_select_tree(array $datatree = null): array {
        if (!$datatree) {
            $cache = \cache::make('local_wb_faq', 'faqcache');
            $cachekey = 'faq_cache';
            $cachedrawdata = $cache->get($cachekey);
        }
        $options = [];
        // todo buildTree()
        return $options;

    }
    /**
     *
     * This is to create a new entity in the database
     *
     * @param stdClass $data
     *
     */
    public function create_faq(stdClass $data): int {
        global $DB;
        $temp = $data->content['text'];
        unset($data->content);
        $data->content = $temp;
        /* Fill entries for testing. */
        /* for ($i = 0; $i <= 100; $i ++) {
            $id = $DB->insert_record('local_wb_faq_entry', $data);
        } */
        $id = $DB->insert_record('local_wb_faq_entry', $data);
        $this->update_cache();
        return $id;
    }

    /**
     *
     * This is to update the entity based on the data object
     *
     * @param stdClass $data
     * @return int
     */
    public function update_faq(stdClass $data): int {
        global $DB;
        $temp = $data->content['text'];
        unset($data->content);
        $data->content = $temp;
        $update = $DB->update_record('local_wb_faq_entry', $data);

        return $update;
    }

    /**
     * Prepare submitted form data for writing to db.
     *
     * @param stdClass $formdata
     * @return stdClass
     */
    public static function form_to_db(stdClass $formdata): stdClass {
        $record = new stdClass();
        $record->id = isset($formdata->id) ? $formdata->id : 0;
        $record->dataid = $formdata->d;
        $record->name = $formdata->name;
        $record->description = $formdata->description;
        $record->required = $formdata->required;
        return $record;
    }

    /**
     * Given a db record make it ready for the form.
     *
     * @param stdClass $record
     * @return stdClass
     */
    public static function db_to_form(stdClass $record): stdClass {
        $formdata = new stdClass();
        $formdata->id = isset($record->id) ? $record->id : 0;
        $formdata->name = $record->name;
        $formdata->description['text'] = $record->description;
        $formdata->name = $record->name;
        $formdata->id = $record->id;
        $formdata->parentid = $record->parentid;
        $formdata->sortorder  = $record->sortorder;
        $formdata->type  = $record->type;
        return $formdata;
    }

    /**
     *
     * This is to update or delete an entity if it does not exist
     *
     * @return mixed
     */
    public function delete() {
        global $DB;
        $DB->delete_records('local_wb_faq_entry', array('id' => $this->id));
    }

    /**
     * Return category by name.
     *
     * @param string $name
     * @return null|int
     */
    public static function return_category_id_by_name(string $name) {

        global $DB;

        if (!$name) {
            return 0;
        }

        if (!$records = $DB->get_records('local_wb_faq_entry', ['type' => 0, 'title' => $name])) {
            return null;
        } else {
            if (count($records) === 1) {

                $record = reset($records);

                return $record->id;
            } else {
                return null;
            }
        }
    }
}
