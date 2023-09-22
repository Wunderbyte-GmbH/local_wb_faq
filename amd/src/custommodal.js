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

/*
 * @package    local_wunderbyte_table
 * @copyright  Wunderbyte GmbH <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// import Templates from "core/templates";
// import {get_string as getString} from "core/str";
// import Ajax from "core/ajax";

import Modal from 'core/modal';
import ModalRegistry from 'core/modal_registry';

const NavbarModal = class extends Modal {
  static TYPE = "local_wb_faq/navbar/navbarmodal";
  static TEMPLATE = "local_wb_faq/navbar/navbarmodal";

  registerEventListeners() {
      // Call the parent registration.
      super.registerEventListeners();

      // Register to close on save/cancel.
      this.registerCloseOnSave();
      this.registerCloseOnCancel();
  }
};

ModalRegistry.register(NavbarModal.TYPE, NavbarModal, NavbarModal.TEMPLATE);
export default NavbarModal;