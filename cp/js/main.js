/* Start Login Page */
$("#login-form").on('submit', function(e){
    e.preventDefault();
    var form = this;
    var data = new FormData(this)
    var username = data.get("username")
    var password = data.get("password")
    if(username.length == 0 || password.length == 0)
    {
        Swal.fire({
            icon: "error",
            text: "برجاء ادخال بياناتك!",
          });
    }
    else
    {
        $("#login-form input[type='submit']").attr("disabled", true)
        $.ajax({
            url: 'ajax/login.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false, 
            success: function(response){
                $("#login-form input[type='submit']").attr("disabled", false)
                var data = JSON.parse(response)
                if(data.status == "error")
                {
                    Swal.fire({
                        icon: "error",
                        text: data.msg,
                      });
                }
                else
                {
                    location.reload();
                }
            }
        })
    }
})
/* End Login Page */


/* Start Live Order */

$("input[name='filter_live_order']").change(function(e){
    var val = $(this).val()
    switch(val)
    {
        case '0':
            $("#parent > div").show()
            break;
        case '1':
            $("#parent > div").hide()
            $("#parent > div[data-marked='0']").show()
            break;
        case '2':
            $("#parent > div").hide()
            $("#parent > div[data-marked='1']").show()
            break;
        case '3':
            $("#parent > div").hide()
            $("#parent > div[data-marked='2']").show()
        break;

    }
})

function check_live_order_visibility()
{
    var val = $("input[name='filter_live_order']:checked").val()
    switch(val)
    {
        case '0':
            $("#parent > div").show()
            break;
        case '1':
            $("#parent > div").hide()
            $("#parent > div[data-marked='0']").show()
            break;
        case '2':
            $("#parent > div").hide()
            $("#parent > div[data-marked='1']").show()
            break;
        case '3':
            $("#parent > div").hide()
            $("#parent > div[data-marked='2']").show()
        break;

    }

    var total = $("#parent > div").length
    var waiting = $("#parent > div[data-marked='0']").length
    var accepted = $("#parent > div[data-marked='1']").length
    var canceled = $("#parent > div[data-marked='2']").length

    $("#total_num").text(total)
    $("#waiting_num").text(waiting)
    $("#approved_num").text(accepted)
    $("#canceled_num").text(canceled)

    return waiting
}

function approve_order(id,me)
{
    $(me).attr("disabled", true)
    $.post("ajax/approve_order.php", {id:id}, function(res){
        if(res == "error")
        {
            Swal.fire({
                icon: "error",
                text: "حدث خطاء في قبول الطلب",
            });
        }
    })
}

function cancel_button(id, me)
{
    $("#cancel_modal").attr("data-order-id", id)
    $("#cancel_modal").modal("show")
}
$("#submit_cancel").click(function(){
    var id = $("#cancel_modal").attr("data-order-id")
    var reason = $("#cancel_modal select option:selected").val()

    $(this).attr("disabled", true)
    var that = this
    $.post("ajax/cancel_order.php", {id:id, reason:reason}, function(res){
        if(res == "error")
        {
            Swal.fire({
                icon: "error",
                text: "حدث خطاء في الغاء الطلب",
            });
        }
        else
        {
            $("#cancel_modal").modal("hide")
        }
    })
})
/* End Live Order */

/* Start Show Order */

function print_rec(id){
    var printWindow = window.open('print.php?id=' + id);
    printWindow.focus(); // Focus on the new window
}

// change food active

function change_cat_active(id,me)
{
    var active = $(me).prop("checked")
    $.post("ajax/change_cat_active.php", {id:id, active:active}, function(res){
        if(res == "error")
        {
            Swal.fire({
                icon: "error",
                text: "حدث خطاء اثناء محاولة التغيير",
            });
        }
    })
}
function change_item_active(id,me)
{
    var active = $(me).prop("checked")
    $.post("ajax/change_item_active.php", {id:id, active:active}, function(res){
        if(res == "error")
        {
            Swal.fire({
                icon: "error",
                text: "حدث خطاء اثناء محاولة التغيير",
            });
        }
    })
}

/* End Show Order */

/* Start Order Details */

$("form#change_limit select").change(function(){
    $(this).parent().submit()
})

function remove_order(id, me)
{
    Swal.fire({
        icon: 'warning',
        title: "هل انت متأكد من رغبتك في إزالة هذا الطلب؟",
        showCancelButton: true,
        confirmButtonText: "متأكد",
        cancelButtonText: `إغلاق`
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            submit_remove(id, me)
          Swal.fire("تم إزالة الطلب", "", "success");
        }
      });
}

function submit_remove(id, td)
{
    $(td).attr("disabled", true)
    $.post("ajax/remove_order.php", {id:id},function(res){
        if(res == "error")
        {
            $(td).attr("disabled", false)
            Swal.fire({
                icon: "error",
                text: "حدث خطاء في ازالة الطلب",
            });
        }
        else
        {
            $(td).parent().parent().parent().remove()
        }
    })
}

/* End Order Details */

/* Start ratings */

function remove_comment(id, me)
{
    $.post("ajax/remove-comment.php", {id:id}, function(res){
        if(res != "error")
        {
            $(me).parent().parent().remove()
        }
    })
}

/* End ratings */

var $myGroup = $('#side-bar');
$myGroup.on('show.bs.collapse','.collapse', function() {
    $myGroup.find('.collapse.show').collapse('hide');
});
var path = window.location.pathname;
var page = path.split("/").pop();
$.post("ajax/set_online_admin.php",{page:page});
setInterval(function(){
  var path = window.location.pathname;
  var page = path.split("/").pop();
  $.post("ajax/set_online_admin.php",{page:page});
}, 1000)