
$(document).ready(function() {
  if ($('.carousel').length > 0) {
    const carousel = new bootstrap.Carousel('.carousel')
  }
});
var opts = {
    speed: 500            //slider speed
    ,autoSlider: false     //autoslide on/off
    ,hasNav: true         //show prev/next slider button?
    ,pauseOnHover: true   //pause when mouse over ?
    ,navLeftTxt: '<'   //prev button text
    ,navRightTxt: '>'  //next button text
    ,zIndex:20            //z-index  setting
    ,ease: 'linear'       //animation ease setting
    ,beforeAction: function() {}
    ,afterAction: function() {}
  }
  var as = $('#papers').paperSlider(opts)
   
  $(".ps-nav-prev").click(function(){
    var currentPage = parseInt($("#currentPage").text());
    var totalPage = parseInt($("#totalPages").text());
    if(currentPage - 1 == 0){
      $("#currentPage").text(totalPage)
    }
    else {
      $("#currentPage").text(currentPage - 1)
    }
  })
  $(".ps-nav-next").click(function(){
    var currentPage = parseInt($("#currentPage").text());
    var totalPage = parseInt($("#totalPages").text());
    if(currentPage >= totalPage){
      $("#currentPage").text(1)
    }
    else {
      $("#currentPage").text(currentPage + 1)
    }
  })
  $(function(){
    $("#papers").on("swipeleft",function(){
      $( ".ps-nav-next" ).trigger( "click" );
    }); 
    $("#papers").on("swiperight",function(){
      $( ".ps-nav-prev" ).trigger( "click" );
    });   
  $(".show_offer_det").on("click",function() {
    var img = $(this).parent().find("img").clone()
    img.attr("width","100%")
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      var data = JSON.parse(this.responseText);
      $("#myModal img").attr("src","")
      $("#myModal .modal-body div h2").text(data.title)
      $("#myModal .discount span").text(data.price + " جنية")
      $("#myModal pre").text(data.description.trim())
      $("#myModal img").parent().html(img)
      $("#myModal").modal()
    }
    xhttp.open("GET", "ajax/offer.php?id=" + $(this).attr("data-id"), true);
    xhttp.send();
  })
  $(".show_safwa_card_det").on("click",function() {
    var img = $(this).parent().find("img").clone()
    img.attr("width","100%")
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      var data = JSON.parse(this.responseText);
      $("#myModal img").attr("src","")
      $("#myModal .modal-header h2 span.content").text(data.title)
      $("#myModal pre").text(data.description)
      $("#myModal img").parent().html(img)
      $("#myModal").modal()
    }
    xhttp.open("GET", "ajax/safwa-card.php?id=" + $(this).attr("data-id"), true);
    xhttp.send();
  })
})
var input_comment_text = ""
$(".validate_number").on('keydown', function(e){
  input_comment_text = $(this).val()
})
$(".validate_number").on('input', function(e){
  var x= $(this).val();
  if (isNaN(x)) 
  {
    $(this).val(input_comment_text)
  }
  else {
    input_comment_text = x
  }
})

$(".order").click(function(){
  window.location.href = "/order";
})
$(".logo").click(function(){
  window.location.href = "/";
})

$(".order-online-page .categories ul li").click(function(){
  var id = $(this).attr("data-id")
  var that = this
  $('.order-online-page').animate({
    scrollTop: $(".item-list[data-id='" + id + "']").offset().top - $(".order-online-page").offset().top + $(".order-online-page").scrollTop() - 30
  }, 500, function() {
    $(".order-online-page .categories ul li.active").removeClass("active")
    $(that).addClass("active")
  });
})

$(".order-online-page").scroll(function (event) {
  var scroll = $(".order-online-page").scrollTop()
  for(var i = 1; i <= $(".item-list").length; i++)
  {
    var scrolled = $(".item-list:nth-of-type(" + i + ")").offset().top - $(".order-online-page").offset().top + $(".order-online-page").scrollTop() - 100
    if(scroll >= scrolled && scroll <= scrolled + $(".item-list:nth-of-type(" + i + ")").height())
    {
      $(".order-online-page .categories ul li.active").removeClass("active")
      $(".order-online-page .categories ul li:nth-of-type(" + i + ")").addClass("active")
    }
  }
});

/* Start item window */
var step = 0

$(".food-item").click(function(){
  var id = $(this).attr("data-id")
  
  item_info.show();
  open_item(id, this)
  step = 1;
})


function open_item(id, me)
{
  $("#item_info").find("div.item_price .item_before_price").remove()
  // delete any old values
  $("#item_info").find(".options").html("")
  $("#item_info").find(".item-sizes").hide();
  $("#item_info").find(".item-sizes ul li").remove();
  
  // Clone Image
  var image = $(me).find("img").clone();
  image.css('width', '100%')
  
  //prepare window
  $("#item_info").find("div:last-of-type .count").text("1")

  $("#item_info").attr("data-id", id)
  $("#item_info").find("span.item-name").text(items_info[id]['title'])
  $("#item_info").find("img").replaceWith(image)
  $("#item_info").find("pre").text(items_info[id]['description'])
  $("#item_info").find("div.item_price span:first-of-type").text(items_info[id]['price'])

  var index_for_check = 0;
  if(items_info[id].sizes.length > 0)
  {
    $("#item_info").find(".item-sizes").show();
    items_info[id].sizes.forEach(function(item,i,_){
      var checked = ''
      if(i == 0)
      {
        checked = 'checked'
      }
      $("#item_info").find(".item-sizes ul").append(`
        <li class="d-flex justify-content-between">
        <div>
          <input id="check${index_for_check}" type="radio" name="size" value="${i}" onchange="calc_single_item_price()" ${checked}>
          <label style="line-height: 20px;vertical-align: top;" for="check${index_for_check}">${item.size_name}</label>
        </div>
        <div>
          [<span class="price fs-6">${item.size_price}</span> ${currency}]
        </div>
      </li>
      `)
      index_for_check++;
    })
  }

  if(items_info[id].options.length > 0)
  {
    items_info[id].options.forEach(function(item,i,_){
      var flag = ""
      if(item.type == 1)
      {
        flag = `
        <span class="fs-6 fw-bold" style="background: #ff8e07;
        border-radius: 6px;
    color: white;
    padding: 6px 7px;">${lang.optional}</span>`
      }
      else if(item.type == 0)
      {
        flag = `
        <span class="fs-6 fw-bold" style="background: #e51212;
        border-radius: 6px;
    color: white;
    padding: 6px 7px;">${lang.required}</span>`
      }
      var item_push = `
        <div class="item-option" style="margin-bottom:10px !important;width:90%;margin:auto;">
          <hr>
          <h3 class="fs-5 fw-bold position-relative d-flex justify-content-between" style="margin-bottom:15px;">${lang.choose} ${item.name}: ${flag}</h3>
          <ul style="list-style:none;padding:0;" data-type="${item.type}" data-option-id=${item.id}>
      `
      item.values.forEach(function(value,_,_){
        if(item.type == 0)
        {
          item_push += `
            <li class="d-flex justify-content-between">
              <div>
                <input id="check${index_for_check}" type="radio" name="option${item.id}" value="${value.id}" data-price="${value.price}" onchange="calc_single_item_price()">
                <label style="line-height: 20px;vertical-align: top;" for="check${index_for_check}">${value.name}</label>
              </div>
              `
            if(value.price > 0)
            {
              item_push += `<div>
              [+ <span class="price fs-6">${value.price}</span> ${currency}]
            </div>`
            }

          item_push += `
            </li>
          `
        }
        else
        {
          item_push += `
            <li class="d-flex justify-content-between">
              <div>
                <input id="check${index_for_check}" type="checkbox" name="option${item.id}[]" value="${value.id}" data-price="${value.price}" onchange="calc_single_item_price()">
                <label style="line-height: 20px;vertical-align: top;" for="check${index_for_check}">${value.name}</label>
              </div>
              <div>
                [+ <span class="price fs-6">${value.price}</span> ${currency}]
              </div>
            </li>
          `
        }
        index_for_check++;
      })

      item_push += `
          </ul>
        </div>
      `
      $("#item_info").find(".options").append(item_push)
    })
  }
  $("#item_info button:last-of-type").prop("disabled", false)
  item_info.show();
}

$("#item_info button:last-of-type").click(function(){
  $("#item_info button:last-of-type").prop("disabled", true)
  var id = $("#item_info").attr("data-id")
  var current_count = parseInt($("#item_info").find("div:last-of-type .count").text())


  //Prepare size
  var size_id = undefined
  if(items_info[id].sizes.length > 0)
  {
    size_id = $("#item_info").find(".item-sizes ul input[name='size']:checked").val()
    if(size_id == undefined)
    {
      $("#item_info").find(".item-sizes h3").notify(lang.choose_size,{
        className: 'error',
        position: 'top right'
      })
      return false;
    }
    size_id = items_info[id].sizes[size_id].id
  }
  //Prepare options
  if(items_info[id].options.length > 0)
  {
    var options = []
    var element_check = undefined
    var can_be_missed = 0
    items_info[id].options.forEach(function(val, i){
      if(val.type == 0)
      {
        can_be_missed = 1
        element_check = $(`#item_info .options .item-option ul[data-option-id='${val.id}'] input:checked`).val()
          if(element_check == undefined)
          {
            $(`#item_info .options .item-option ul[data-option-id='${val.id}'] li:nth-of-type(1) input`).focus()
            $(`#item_info .options .item-option ul[data-option-id='${val.id}']`).notify(lang.please_choose,{
              className: 'error',
              position: 'top right'
            })
            $("#item_info button:last-of-type").prop("disabled", false)
            return false
          }
          // send info
          var option_data = {
            "option_id": val.id,
            "option_value": element_check
          }
          options.push(option_data)
      }
      else
      {
        var values = []
        var val_elem
        val_elem = $(`#item_info .options .item-option ul[data-option-id='${val.id}'] input:checked`)
        val_elem.each(function(i, elem){
          values.push($(elem).val())
        })
        if(values.length > 0)
        {
          var option_data = {
            "option_id": val.id,
            "option_value": values
          }
          options.push(option_data)
        }
      }

    })
    if(element_check == undefined && can_be_missed == 1)
      return false;
  }


  data = {
    id:id,
    count: current_count,
    size_id: size_id,
    options: options
  }
  $.post("ajax/add_item_cart.php", {data:data}, function(result){
    console.log(result)
    var data = JSON.parse(result)
    $(".order-footer").find("span.count").text(data.count)
    $(".order-footer").find("span.price").text(data.price + " " + currency)
    if(data.reached_min)
    {
      $(".order-footer button").removeAttr("style")
      $(".order-footer button").removeAttr("disabled")
      $(".order-footer button").removeAttr("data-min")
    }
    item_info.hide();
  })
})

/* End item window */
var cart_steps = 0
var user_steps = 0
var final_steps = 0
$(".order-footer button").click(function(){
  var button = this
  $(button).prop("disabled", true)
  $.post("ajax/get_card_info.php", function(result){
    $("#card_info .modal-body div.cart").html(result)
    card_info.show();
    cart_steps = 1
    $(button).prop("disabled", false)
  })
})

$("#card_info button:last-of-type").click(function(){
  card_info.hide();
  $(".coupon_activated p.code").text("")
  $(".coupon_activated input").val("")

  $(".coupon_activated").hide()
  $(".discounts_code .coupon").show()
  $(".discounts_code input").val("")
  
  $("#user_info button:last-of-type").prop("disabled", false)
  user_info.show();

  user_steps = 1
})


/* to be 0 */

$('#item_info').on('hidden.bs.modal', function (e) {
  step = 0
})

$('#card_info').on('hidden.bs.modal', function (e) {
  cart_steps = 0
})

$('#user_info').on('hidden.bs.modal', function (e) {
  user_steps = 0
})

$('#final_info').on('hidden.bs.modal', function (e) {
  $("#user_info button:last-of-type").prop("disabled", false)
  final_steps = 0
})


$("#user_info button:last-of-type").click(function(){
  var button = this
  $(button).prop("disabled", true)
  var discount_code = $("#user_info .coupon_activated input[name='coupon_code']").val().trim()
  var delivery = $("#user_info .modal-body #del-loc").val()
  $("#final_info .items").html("")
  $(".discounts_code .public").html("")
  $("#final_info #order_discount").hide()
  $("#final_info #delivery_discount").hide()
  $("#final_info div:has( > #total_tax)").css("display","none")
  $("#final_info div:has( > #visa_tax)").css("display","none")


  var data = [];
  var phone = $("#user_info input.phone").val()
  var discount_data = {
    code: discount_code,
    location: delivery,
    phone: phone
  }

  var how_pay = $("#user_info input[name='how_pay']:checked").val()
  
  var to_send = {"phone": phone, "discount": discount_data, "del" : delivery, 'method': how_pay}
  
  data.push($("#user_info input.name").val())
  data.push($("#user_info input.phone").val())
  data.push($("#user_info #del-loc option:selected").text())
  data.push($("#user_info .street").val())
  data.push($("#user_info .notice").val())


  if(data[0].trim() == "" || data[0].trim().split(" ").length < 2 || data[0].length < 3 || data[0].includes("<") || data[0].includes(">") || data[0].includes("'") || data[0].includes('"') || data[0].includes("/") || data[0].includes("&") || data[0].includes(";"))
  {
    $("#user_info input.name").notify(lang.enter_correct_name, "error");
  }
  else if (data[1].length != 11 || !(data[1].startsWith("010") || data[1].startsWith("011") || data[1].startsWith("015") || data[1].startsWith("012")) || isNaN(data[1]))
  {
    $("#user_info input.phone").notify(lang.enter_correct_phone, "error");
  }
  else if (data[2].trim() == "" || isNaN(delivery.trim()))
  {
    $("#user_info #del-loc").parent().notify(lang.enter_area, "error");
  }
  else if (data[3].trim() == "" || data[3].length < 5 || data[3].includes("<") || data[3].includes(">") || data[3].includes("'") || data[3].includes('"') || data[3].includes("/") || data[3].includes("&") || data[3].includes(";"))
  {
    $("#user_info .street").notify(lang.enter_address, "error");
  }
  else if(how_pay != 0 && how_pay != 1)
  {
    $("#user_info input[name='how_pay']").notify(lang.choose_payment, "error")
  }
  else
  {
    $("#final_info .how_pay").text((how_pay == 0) ? lang.payment_with_hands : lang.payment_with_visa)
    $("#final_info .name").text(data[0])
    $("#final_info .phone").text(data[1])
    $("#final_info .del-loc").text(data[2])
    $("#final_info .street").text(data[3])
    if(data[4].trim() != "")
    {
      $("#final_info .notice").text(data[4])
    }
    else
    {
      $("#final_info .notice").text(lang.nothing)
    }
    
    $.post("ajax/get_final_info.php", {data: to_send}, function(result){
      console.log(result)
      var data = JSON.parse(result)
      var discount = 0

      if(data.tax > 0)
      {
        $("#final_info #total_tax").text(data.tax + " " + currency)
        $("#final_info div:has( > #total_tax)").css("display","flex")
      }

      if(data.discount != undefined)
      {

        if(data.discount.discount_total > 0)
        {
          discount = data.discount.discount_total
          $("#final_info #order_discount").css("display", "flex")
          $("#final_info #order_discount span:last-of-type").text("- " + data.discount.discount_total + " " + currency)
        }
        if(data.discount.discount_delv > 0)
        {
          discount = data.discount.discount_delv
          $("#final_info #delivery_discount").css("display", "flex")
          $("#final_info #delivery_discount span:last-of-type").text("- " + discount + " " + currency)
        }
       
      }


      var total_price = Math.ceil(parseFloat(data.del) + parseFloat(data.price) - parseFloat(discount) + parseFloat(data.tax))
      $("#final_info .price").text(data.price + " " + currency)
      $("#final_info .del").text(data.del + " " + currency)
      $("#final_info .del_time").text(data.del_time)
      var visa_tax = 0
      if(data.visa_fixed_tax > 0 || data.visa_percent_tax > 0)
      {
        var visa_tax = parseFloat(((data.visa_percent_tax * total_price) / 100)) + parseFloat(data.visa_fixed_tax)

        $("#final_info div:has( > #visa_tax)").css("display","block") 
        $("#final_info div > #visa_tax").text(`${visa_tax} ${currency}`)
      }
      $("#final_info .total_price").text(parseFloat(total_price) + parseFloat(visa_tax) + " " + currency)

      data.items.forEach(function(item,index, arr){
        var content = `
        <div style="margin:auto;width:80%;background: #e5e5e5;padding: 10px;border-radius: 5px">`;


        content = content + `<div class="d-flex justify-content-between"><span>${item.count}<bdi> X </bdi>${item.name} </span>`;

        if(item.price > 0)
          content = content + `<span>${item.price * item.count} ${currency}</span></div>
                              `;
        else
          content = content + `<span></span></div>
                              `;

        if(item.size != undefined)
        {
          content = content + `<div class="ps-1" style="margin:auto;width:100%;">${lang.size}: ${item.size}</div>`;
        }
        


        if(item.options != undefined)
        {
          item.options.forEach(function(option){
            if(option.split == true)
            {
              var names = option.value.split(",")
              var price = option.price.split(",")
              content = content + `<div class="ps-4" style="margin:auto;width:100%;">${option.name}: </div>`;
              names.forEach(function(name,i){
                content = content + `
                <div class="d-flex justify-content-between ps-4" style="margin:auto;width:100%;">
                  <span class="ps-2">${item.count}<bdi> X </bdi>${name} </span>
                  <span>[+ ${item.count * price[i]} ${currency}]</span>
                </div>
                `;
              })
            }
            else
            {
              if(option.price > 0)
              {
                content = content + `
                  <div class="d-flex justify-content-between ps-4" style="margin:auto;width:100%;">
                    <span>${item.count} <bdi> X</bdi> ${option.name}: ${option.value} </span>
                    <span>[+ ${option.price * item.count} ${currency}]</span>
                  </div>
                `;
              }
              else
              {
                content = content + `<div style="margin:auto;width:100%;" class="ps-4">${option.name}: ${option.value}</div>`;
              }
            }

          })
        }
        
        content = content + "</div><br>"
        console.log(content)
        console.log('test')
        $("#final_info .items").append(content)
      })
      
    })
    

    if($('#save_my_info').is(':checked'))
    {
      data[2] = $("#user_info #del-loc option:selected").val()
      setCookie("order_info", JSON.stringify(data), 30)
    }
    else
    {
      eraseCookie("order_info")
    }

    user_info.hide();
    $("#final_info button:first-of-type").prop("disabled", false)
    final_info.show();
    final_steps = 1
  }
  $(button).prop("disabled", false)

})

function eraseCookie(name) {   
  document.cookie = name+'=; Max-Age=-99999999;';  
}


function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

$("#final_info button:last-of-type").click(async function(){
  $("#final_info button:last-of-type").prop("disabled", true)
  var discount_code = $("#user_info .coupon_activated input[name='coupon_code']").val().trim()
  var delivery = $("#user_info .modal-body #del-loc").val()
  var data = [];
  data.push($("#user_info input.name").val())
  data.push($("#user_info input.phone").val())
  data.push($("#user_info #del-loc option:selected").text())
  data.push($("#user_info .street").val())
  data.push($("#user_info .notice").val())

  var how_pay = $("#user_info input[name='how_pay']:checked").val()

    var phone = $("#user_info input.phone").val()
    var discount_data = {
      code: discount_code,
      location: delivery,
      phone: phone
    }
  
    var to_send = {"phone": phone, "discount": discount_data, "del" : delivery}
  
    var text = `
    ----------------
    ${lang.name}: ${data[0]}
    ${lang.phone}: ${data[1]}
    ${lang.area}: ${data[2]}
    ${lang.address}: ${data[3]}
    `
  
    if (how_pay == 0)
    {
      text = text + `${lang.payment_method}: ${lang.payment_with_hands}`
    }
    else if(how_pay == 1)
    {
      text = text + `${lang.payment_method}: ${lang.payment_with_visa}`
    }
  
  
    text = text + `
    ----------------
    ${lang.order_details}
    ----------------
    `
    var form_data = data;
    $.post("ajax/get_final_info.php", {data: to_send}, function(result){
      console.log(result)
      var data = JSON.parse(result)
      text = `
      ${data.del_time}` + text
      data.items.forEach(function(item,index, arr){
  
  
        text = text + `
        ${item.count} × ${item.name}`
        text = text + `- ${item.price} ${currency}
        `
  
        if(item.size != undefined)
        {
          text = text + ` ${lang.size}: ${item.size}
          `
        }
  
        if(item.options != undefined)
        {
          item.options.forEach(function(option){
            if(option.price != undefined)
            {
              text = text + ` ${option.name}:
              `;
              var val_by_val = option.value.split(",")
              var price_by_price = option.price.split(",")
              val_by_val.forEach(function(i,index){
                text = text + ` ${item.count} × ${val_by_val[index]}        ${price_by_price[index]} ${currency}
                `;
              })
            }
            else{
              text = text + ` ${item.count} × ${option.name}:  ${option.value}
              `
            }
          })
        }
  
      })
    var total_discount = 0
    var total = ""
    var delv = ""
    if(data.discount != undefined)
    {
  
      if(data.discount.discount_total > 0)
      {
        total_discount = data.discount.discount_total
        total = `
      ${lang.order_discount}: -${data.discount.discount_total} ${currency}`
      }
      if(data.discount.discount_delv > 0)
      {
        total_discount = data.discount.discount_delv
        delv = `
      ${lang.delivery_discount}: -${data.discount.discount_delv} ${currency}`
      }
    }
  
      let last_price = parseFloat(data.del) + parseFloat(data.price) - parseFloat(total_discount) + parseFloat(data.tax)

    var tax_line = ''
    if(data.tax > 0)
    {
      tax_line = `
      ${lang.tax}: ` + data.tax
    }

      if(form_data[4] != "")
      {
        text = text + `
        ----------------
        ${lang.info_notes}: ${form_data[4]}
        `
      }
    
      text = text + `
      ----------------
      ${lang.pay_info}
      ----------------
      ${lang.sum}: ${data.price} ${currency}${total}
      ${lang.delivery}: ${data.del} ${currency}${delv}${tax_line}
      ${lang.total_cost}: ${last_price} ${currency}
      `
  
      var order_info = {
        "client_name": form_data[0],
        "client_phone": form_data[1],
        "client_location": delivery,
        "client_address": form_data[3],
        "client_notice": form_data[4],
        "discount": discount_data,
      }

      if(how_pay == 1)
      {
        $.post("ajax/pay-visa.php", {del: delivery,name: $("#user_info input.name").val(), phone: phone, data: order_info}, function(res){
          console.log(res)
          var res = JSON.parse(res);
          if(res.res == "success")
          {
            var link = "https://checkout.kashier.io/?" + 
            "merchantId=" + encodeURIComponent(res.merchantId) + "&" +
            "orderId=" + encodeURIComponent(res.orderId) + "&" +
            "amount=" + encodeURIComponent(res.amount) + "&" +
            "currency=EGP&" +
            "hash=" + res.hash + "&" +
            "mode=live&" +
            "merchantRedirect=" + res.merchantRedirect + "&" +
            "allowedMethods=card,wallet&" +
            "redirectMethod=get&" +
            "display=ar&" +
            "serverWebhook=" + encodeURIComponent(res.webhook);
            location.href = link;
          }
          else
          {
            console.log(res.body)
            $("#final_info button:first-of-type").notify(`${lang.unexpected_error}!`, "error")
          }
        })
      }
      else
      {
        $.post("ajax/save_order.php", {data: order_info},function(res){
          text = `${lang.order_approved} #${res}` + text
          var url = `https://wa.me/${data.wh}?text=${encodeURIComponent(text)}`
          setTimeout(()=>{
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "/success_order.php");
            var hiddenField = document.createElement("input"); 
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "url");
            hiddenField.setAttribute("value",url);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit()
          })
        });
      }
    })
})

$("#check_discount").click(function(){
  var button = this

  var delivery = $("#user_info .modal-body #del-loc").val()
  var code = $(this).parent().find("input").val().trim()
  var phone = $("#user_info input.phone").val()
  var data = {
    code: code,
    location: delivery,
    phone: phone
  }
  $.post("ajax/check_discount_code.php", {data:data}, function(result){
    console.log(result)
    var data = JSON.parse(result)
    if(data.res == "fail")
    {
      $(".discounts_code input").notify(data.msg, {className:'error', position: 'top left'})
    }
    else
    {
      $(".discounts_code > span").notify(data.msg, {className:'success', position: 'top right'})
      $(".discounts_code .coupon").hide()

      $(".coupon_activated p.code").text(data.code_name)
      $(".coupon_activated input").val(code)
      $(".coupon_activated").show()
    }
  })
})

$(".coupon_activated span.remove").click(function(){
  $(".coupon_activated p.code").text("")
  $(".coupon_activated input").val("")

  $(".coupon_activated").hide()
  $(".discounts_code .coupon").show()
})
