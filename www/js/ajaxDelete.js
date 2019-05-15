"use strict";

function removePlat(e)
{
    console.log($(this).data('action')) ;
    e.preventDefault();
    let sendData = { id: $(this).data('id')} ;
    $.getJSON($(this).data('action'), sendData, onRemoveSucces.bind(this)) ;
}

function onRemoveSucces()
{
    $(this).parents(".removable-container").fadeOut("slow") ;
}

$(function () {
    $(".remove-plat").on("click", removePlat);
});

// var removeInput ;
//
//     function deleteAjaxAction()
//     {
//         let platData = { id : $(this).data('id') } ;
//         $.getJSON($(this).data('action'), platData, onDeleteSucces.bind(this));
//     }
//
//     function onDeleteSucces()
//     {
//         $(this).parents(".platContainer").fadeOut("slow");
//     }
//
//     $(function()
//     {
//     removeInput = $(".ajaxDelete");
//     removeInput.on("click", deleteAjaxAction);
//     });