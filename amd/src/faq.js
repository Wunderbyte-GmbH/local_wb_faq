
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
/**
 * Gets called from mustache template.
 *
 */
export const init = (data, root) => {
    render(root, data);
    addEvents(data, root);
};

/**
 * adds Evente to toggle switch
 */
function addEvents(data, root) {
    let select = document.getElementById('local_wb_faq');
    select.addEventListener('click', (e) => {
        if (e.target.dataset.action == "goto") {
            render(e.target.dataset.targetid, data);
        }
    });
    let category = document.querySelector('#id_categorypicker');
    category.addEventListener('change', (e) => {
        let faqid = category.value;
        if (category.value == '') {
            faqid = root;
            console.log(category.value);
            console.log(root);
        }
        render(faqid, data);
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
    let container = document.getElementById('local_wb_faq');
    // Empty Container
    container.innerHTML = "";
    // Render
    Templates.renderForPromise('local_wb_faq/faq', json).then(({html}) => {
        container.insertAdjacentHTML('afterbegin', html);
    });
}
