"use strict";


var totalPriceDisplay ;

function onBasketAdd(e)
{
    e.preventDefault() ;
    var select = $(this).parents(".order-block").find(".quantity") ;

    let data = {
        id : $(this).data('id'),
        q  : select.val(),
    } ;

    $.getJSON($(this).data('action'), data, onBasketChangeSuccess.bind(this)) ;
}

function onBasketChange(e)
{
    e.preventDefault() ;

    let data = {
        id : $(this).data('id'),
        q  : $(this).val(),
    } ;

    $.getJSON($(this).data('action'), data, onBasketChangeSuccess.bind(this)) ;
}

function onBasketRemoveItem(e)
{
    e.preventDefault() ;

    let data = {
        id : $(this).data('id'),
    } ;

    $.getJSON($(this).data('action'), data, onBasketChangeSuccess.bind(this)) ;
}


function onBasketChangeSuccess(data)
{
    if(data.success)
    {
        if(data.todo)
        {
            if(data.todo == "delete")
            {
                let container = $(this).parents(".removableContainer");
                container.fadeOut("slow", function()
                {
                    container.remove() ;
                }) ;
                let priceToRemove = parseFloat(container.find(".subTotalPrice").text()) ;
                let newTotalPrice = totalPriceDisplay.text() - priceToRemove ;
                totalPriceDisplay.text(newTotalPrice.toFixed(2)) ;

            }

            if(data.todo == "updateQuantity")
            {
                let container = $(this).parents(".removableContainer");
                let subTotalDisplay = container.find(".subTotalPrice") ;
                let oldSubtotal = parseFloat(subTotalDisplay.text()) ;
                let priceEach = parseFloat(container.find(".priceEach").text()) ;
                let newSubTotal = parseInt(data.quantity) * priceEach ;
                let newTotalPrice = totalPriceDisplay.text() - oldSubtotal + newSubTotal ;

                container.find(".quantityOrdered").val(data.quantity) ;
                subTotalDisplay.text(newSubTotal.toFixed(2)) ;
                totalPriceDisplay.text(newTotalPrice.toFixed(2)) ;
            }
        }
    }
    else
    {
        if(data.redirect)
        {
            window.location.href = data.redirect ;
        }
    }
    if(data.message)
    {
        alert(data.message) ;
    }
}

$(function()
{
    totalPriceDisplay = $("#totalPrice");
    $(".add-to-basket").on("click", onBasketAdd);
    $(".update-basket-line").on("change", onBasketChange);
    $(".delete-basket-item").on("click", onBasketRemoveItem);
});
