$(document).ready(function () {
    initSelect2Ajax($("#parent_id"), $("#categories_search").val());
    initCkEditor("category_description[en][description]");
});
