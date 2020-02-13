/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.sass';

import $ from 'jquery';
import 'bootstrap';
import 'popper.js';
import 'select2';
import 'datatables.net-dt';

let newItemButton = $('<button type="button" class="new_item_button" id="newItemButton">Ajouter un item</button>');
let itemsCollection;


$(document).ready(function () {
    convertSelect($('.item'));

    $('#productsTable').DataTable({
        "scrollX": true,
        "scrollY": 200,
        "searching": false,
        "paging": false,
        "info": false
    });

    $('.dataTables_length').addClass('bs-select');

    itemsCollection = $('.items');
    itemsCollection.find('.item').each(function () {
        addRemoveButton($(this));
    });
    itemsCollection.append(newItemButton);
    itemsCollection.data('index', itemsCollection.find(':input').length);

    newItemButton.on('click', function (e) {
        addItemForm(itemsCollection, newItemButton);
    });
});

function addItemForm(itemsCollection, newItemButton) {
    let index = itemsCollection.data('index');
    let newItemForm = itemsCollection.data('prototype');
    newItemForm = newItemForm.replace(/__name__/g, index);
    itemsCollection.data('index', index + 1);
    let newItemFormDiv = $('<div class="item"></div>').append(newItemForm);
    newItemButton.before(newItemFormDiv);
    addRemoveButton(newItemFormDiv);
    convertSelect(newItemFormDiv);
}

function addRemoveButton(item) {
    let removeItemButton = $('<button type="button" class="remove_item_button">Supprimer l\'item</button>');
    $(item).append(removeItemButton);
    removeItemButton.on('click', function () {
        item.remove();
    });
}

function convertSelect(item) {
    item.find('.select-product').select2({
        "language": {
            "noResults": function () {
                return "Aucun résultat - <a href='{{ path('admin.product.new') }}' class='text-danger' target='_blank'>Créer une référence</a>";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
    item.find('.select-address').select2({
        "language": {
            "noResults": function () {
                return "Aucun résultat - <a href='{{ path('admin.address.new') }}' class='text-danger' target='_blank'>Créer une adresse</a>";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
}