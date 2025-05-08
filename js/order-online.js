
/* Start Item Info Modal Functions */


document.addEventListener("DOMContentLoaded", () => {
  const lazyLoadInstance = new LazyLoad({
      elements_selector: ".lazy",
      callback_loaded: (element) => {
          element.classList.add('loaded');
      }
  });
});


// Preparing Models
var item_info = new bootstrap.Modal(document.getElementById('item_info'));
var card_info = new bootstrap.Modal(document.getElementById('card_info'));
var user_info = new bootstrap.Modal(document.getElementById('user_info'));
var final_info = new bootstrap.Modal(document.getElementById('final_info'));








//Calcuating overall item price (for item info modal)
function calc_single_item_price()
{
    var window = $("#item_info")
    var window_body = window.find(".modal-body")
    var sizes_parent = window_body.find(".item-sizes")
    var options_parent = window_body.find(".options")

    var item_id = window.attr("data-id")
    var selected_size = sizes_parent.find("ul input[name='size']:checked")
    
    var item_price = items_info[item_id].price
    var selected_count = window_body.find("div div.item_count_custom span.count").text()

    if(selected_size.length > 0)
    {
        item_price = items_info[item_id].sizes[selected_size.val()].size_price
    }

    var options = options_parent.find("ul")
    var options_price = 0
    options.each(function(i,option){
        var values = $(option).find("input:checked")
        values.each(function(index,value,_){
            var price = $(value).attr("data-price")
            options_price += parseInt(price)
        })
    })
    console.log(selected_count)
    var total_price = (parseFloat(item_price) + parseFloat(options_price)) * parseFloat(selected_count)

    window_body.find("div.item_price span:first-of-type").text(total_price)
}

// Increase or decrease item
$("#item_info .item_count_custom .add").click(function(){
    var current_count = parseInt($("#item_info").find("div:first-of-type .count").text())
    $("#item_info").find("div:first-of-type .count").text(current_count + 1)
  
    calc_single_item_price()
})
  
$("#item_info .item_count_custom .minus").click(function(){
    var current_count = parseInt($("#item_info").find("div:first-of-type .count").text())
    if(current_count - 1 > 0)
    {
        $("#item_info").find("div:first-of-type .count").text(current_count - 1)
    }

    calc_single_item_price()
})

history.pushState(null, null, window.top.location.pathname + window.top.location.search);
window.addEventListener('popstate', (e) => {
  console.log("triggered")
  if (step == 1) {
    e.preventDefault();
    // Insert Your Logic Here, You Can Do Whatever You Want
    history.pushState(null, null, window.top.location.pathname + window.top.location.search);
    step = 0;
    item_info.hide();
  } else if (cart_steps == 1) {
    e.preventDefault();
    // Insert Your Logic Here, You Can Do Whatever You Want
    history.pushState(null, null, window.top.location.pathname + window.top.location.search);
    cart_steps = 0
    card_info.hide();
  } else if (user_steps == 1) {
    e.preventDefault();
    // Insert Your Logic Here, You Can Do Whatever You Want
    history.pushState(null, null, window.top.location.pathname + window.top.location.search);
    user_steps = 0
    cart_steps = 1
    user_info.hide();
    card_info.show();

  } else if (final_steps == 1) {
    e.preventDefault();
    // Insert Your Logic Here, You Can Do Whatever You Want
    history.pushState(null, null, window.top.location.pathname + window.top.location.search);
    final_steps = 0
    user_steps = 1
    final_info.hide();
    $("#user_info button:last-of-type").prop("disabled", false)
    user_info.show();
  } else {
    history.back()
  }



});