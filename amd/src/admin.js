
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

import Ajax from 'core/ajax';
import Templates from 'core/templates';
import DynamicForm from 'core_form/dynamicform';

/**
 * Gets called from mustache template.
 *
 */
export const init = (data) => {

    addEvents(data);
    renderforcache(data);
};

/**
 * adds Evente to toggle switch
 */
function addEvents(data) {
    let select = document.getElementById('local_wb_faq_admin');
    select.addEventListener('click', (e) => {
        if (e.target.hasAttribute('data-action')) {
            e.target.setAttribute('disabled', 'disabled');
            if (e.target.dataset.action == "goto") {

                render(e.target.dataset.targetid, data);
            }
            if (e.target.dataset.action == "quickedit") {

                renderedit(e.target.dataset.targetid, e.target.dataset.type, data);
            }
            if (e.target.dataset.action == "delete") {
                e.target.closest('tr').remove();
                deleteentry(e.target.dataset.targetid);
            }
            e.target.removeAttribute('disabled');
        }
    });
}

/**
 * Renders template from jsonobject for the categoryid
 * @param {*} id
 */
function render(id, data) {
    // Load the specific category data
    let json = data[id];
    // Select Container
    let container = document.getElementById('local_wb_faq_admincontent');
    // Render
    Templates.renderForPromise('local_wb_faq/admincontent', json).then(({html}) => {
        container.insertAdjacentHTML('beforeend', html);
    }).catch(e => {
        // eslint-disable-next-line no-console
        console.log(e);
    });
}

/**
 * Renders template from asd for the categoryid
 * @param {*} id
 */
 export const deleteentry = (id) => {
    Ajax.call([{
        methodname: "local_wb_faq_delete_entry",
        args: {
            'id' : id
        },
        done: function() {
        },
    }]);
};



function renderedit(id, type) {
    let json = {'id' : type + '-' + id};
    let  container = document.querySelector('#local_wb_faq_admin .view-' + type + '-' + id);
    let editrow = '#local_wb_faq_admin .edit-' + type + '-' + id;
    let td = '#local_wb_faq_admin .edit-' + type + '-' + id + ' td';
    let hidden = 'hidden';
    if (id == 0) {
        editrow = '#local_wb_faq_admin #local_wb_faq_new_entry form';
        container = document.querySelector('#local_wb_faq_new_entry');
        td = '#local_wb_faq_admin #local_wb_faq_new_entry';
        hidden = 'addnew';
    }

    Templates.renderForPromise('local_wb_faq/quickedit', json).then(({html}) => {
        container.insertAdjacentHTML('afterend', html);
        container.classList.add(hidden);
    }).then(() =>  {
    let dynamicForm = new DynamicForm(document.querySelector(td), 'local_wb_faq\\form\\dynamiceditform');
    dynamicForm.load({
        'id' : id,
        'type' : type
        });
    return dynamicForm;
    }).then((dynamicForm) => {
        dynamicForm.addEventListener(dynamicForm.events.FORM_SUBMITTED, (e) => {
            e.preventDefault();
            container.classList.remove(hidden);
            document.querySelector(editrow).remove();
            window.location.reload();
        });
        dynamicForm.addEventListener(dynamicForm.events.FORM_CANCELLED, (e) => {
            e.preventDefault();
            container.classList.remove(hidden);
            document.querySelector(editrow).remove();
        });
    });
}

/**
 *
 * @param {object} json
 */
function renderforcache(json) {
    Templates.renderForPromise('local_wb_faq/quickedit', json[1]);
}
