
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


import Templates from 'core/templates';
import {get_string as getString} from 'core/str';

/**
 * Gets called from mustache template.
 *
 * @param {*} data
 * @param {integer} root
 */
export const init = (data, root) => {

    render(root, data, root);
    addEvents(data, root);
};

/**
 * Adds Evente to toggle switch
 * @param {*} data
 * @param {integer} root
 */
function addEvents(data, root) {
    let select = document.getElementById('local_wb_faq');

    if (select) {
        select.addEventListener('click', (e) => {
            if (e.target.dataset.action == "goto") {
                render(e.target.dataset.targetid, data, root);
            }
        });
    }

    let searchbox = document.getElementById('wb_faq_searchbox');

    if (searchbox) {
        searchbox.addEventListener('click', (e) => {
            if (e.target.dataset.action == "goto") {
                render(e.target.dataset.targetid, data, root);
                document.querySelector('#local_wb_faq').scrollIntoView({block: "start", behavior: "smooth"});
            }
        });
    }

    let category = document.querySelector('#id_categorypicker');

    if (category) {
        category.addEventListener('change', () => {
            let faqid = category.value;
            if (category.value == '') {
                faqid = root;
            }

            render(faqid, data, root);
        });
    }
}

/**
 * Renders template from jsonobject for the categoryid
 * @param {integer} id
 * @param {*} data
 * @param {integer} root
 */
function render(id, data, root) {

    // Load the specific category data
    let json = JSON.parse(data);
    let templatedata = json[id];
    if (json[templatedata.parentid].title) {
        templatedata.parenttitle = json[templatedata.parentid].title;
    }
    if (templatedata.parentid == '') {
        templatedata.parenttitle = getString('faq', 'local_wb_faq');
    }
    // Select Container
    let container = document.getElementById('local_wb_faq');
    // Empty Container
    container.innerHTML = "";
    // Render
    Templates.renderForPromise('local_wb_faq/faq', templatedata).then(({html}) => {

        container.insertAdjacentHTML('afterbegin', html);
        return;
    }).catch(e => {
        // eslint-disable-next-line no-console
        console.log(e);
    });
}
