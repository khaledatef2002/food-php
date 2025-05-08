<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <?php include 'temps/head.php'; ?>
  <?php if (!is_logged()) header('Location: login.php'); ?>
  <?php
  if (!check_user_perm(['locations-view'])) :
    header('Location: 403.php');
    exit;
  endif;
  if (isset($_GET['branch'])) :
    $id = filter_var($_GET['branch'], FILTER_SANITIZE_NUMBER_INT);
    $get_branches = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches WHERE id=$id");
    if (mysqli_num_rows($get_branches) == 0) :
      exit("Invalid branch id");
    endif;
  else :
    exit("No id provided");
  endif;
  ?>

  <title>
    Powered by diafh
  </title>
</head>

<body class="g-sidenav-show rtl bg-gray-200">
  <?php include 'temps/sidebar.php'; ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    <?php include 'temps/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row my4 justify-content-center">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card text-center">
            <h3 class="my-0 font-weight-bold py-2">المناطق</h3>
          </div>
        </div>
      </div>
      <?php if (check_user_perm(['locations-add'])) : ?>
        <div class="row mt-4 justify-content-center">
          <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_location">اضافة جديد</a>
        </div>
      <?php endif; ?>
      <div id="parent" class="row my-2 justify-content-center">
        <?php
        $get_locations = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE branch_id=$id ORDER BY id DESC");
        while ($location = mysqli_fetch_assoc($get_locations)) {
        ?>
          <div data-id="<?php echo $location['id']; ?>" class="col-lg-3 col-md-4 col-sm-6 mb-2">
            <div class="card mb-2 h-100 align-content-between">
              <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                <p class="font-weight-bold my-0">
                  اسم المنطقة:
                  <input class="form-control border px-3" type="text" value="<?php echo $location['name']; ?>">
                </p>
                <p class="font-weight-bold my-0">
                  سعر المنطقة:
                  <input name="price" class="form-control border px-3" type="number" value="<?php echo $location['price']; ?>">
                </p>
                <div class="form-check bg-gradient-dark mt-2 px-3 radius-2 py-1 pb-0 rounded" style="width:fit-content">
                  <input class="form-check-input" type="checkbox" name="location" style="cursor:pointer;" <?php echo ($location['active'] == 1) ? 'checked' : '' ?>>
                  <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                    التفعيل
                  </label>
                </div>
                <?php if (check_user_perm(['locations-edit']) || check_user_perm(['locations-remove'])) : ?>
                  <div class="m-auto mb-0 mt-1">
                    <?php if (check_user_perm(['locations-edit'])) : ?>
                      <button class="btn btn-success mx-1" onclick="save_location(<?php echo $location['id']; ?>, this)">حفظ</button>
                    <?php endif; ?>
                    <?php if (check_user_perm(['locations-remove'])) : ?>
                      <button class="btn btn-danger mx-1" onclick="remove_location(<?php echo $location['id']; ?>, this)">إزالة</button>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>
      <?php include 'temps/footer.php'; ?>
    </div>
  </main>

  <!-- Start Add New Social modal -->
  <div class="modal fade" data-bs-backdrop="static" id="add_new_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">اضافة منطقة توصيل جديدة</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="font-weight-bold my-0">
            <label for="location_name">اسم المنطقة</label>
            <input id="location_name" name="location_name" class="form-control border px-1" type="text">
          </p>
          <p class="font-weight-bold my-0">
            <label for="location_price">سعر المنطقة</label>
            <input id="location_price" name="location_price" class="form-control border px-1" type="number" step="0.01">
          </p>
          <div class="form-check bg-gradient-dark mt-2 px-3 radius-2 py-1 pb-0 rounded" style="width:fit-content">
            <input class="form-check-input" type="checkbox" id="active" name="active" style="cursor:pointer;">
            <label class="form-check-label text-bold text-white mx-1" for="active">
              التفعيل
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
          <button type="button" class="btn btn-primary" onclick="add_new_location()">اضافة</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add New Social modal -->

  <?php include 'temps/jslibs.php'; ?>
  <script>
    function save_location(id, me) {
      var price = $(me).parent().parent().find("input[name='price']").val()
      var active = $(me).parent().parent().find("input[name='location']").prop("checked")

      $.post("ajax/save-location.php", {
        id: id,
        price: price,
        active: active
      }, function(res) {
        if (res != "error") {
          Swal.fire({
            icon: "success",
            text: "تم التحديث بنجاح"
          })
        }
      })
    }

    function remove_location(id, me) {
      $.post("ajax/remove-location.php", {
        id: id,
      }, function(res) {
        if (res == "error") {
          Swal.fire({
            icon: "error",
            text: "فشل إزالة المنطقة"
          })
        } else {
          Swal.fire({
            icon: "success",
            text: "تم إزالة المنطقة بنجاح"
          })
          $(me).parent().parent().parent().parent().remove()
        }
      })
    }


    function add_new_location() {

      var location_name = $("#add_new_location input#location_name").val()
      var location_price = $("#add_new_location input#location_price").val()
      var active = $("#add_new_location input#active").prop("checked")
      console.log(location_name)
      console.log(location_price)
      console.log(active)

      $.post("ajax/add-location.php", {
        id: <?php echo $id; ?>,
        location_name: location_name,
        location_price: location_price,
        active: active
      }, function(res) {
        if (res == "error") {
          console.log(res)
          Swal.fire({
            icon: "error",
            text: "فشل اضافة المنطقة"
          })
        } else {
          Swal.fire({
            icon: "success",
            text: "تم اضافة منطقة"
          })
          $("#add_new_location input#location_name").val("")
          $("#add_new_location input#location_price").val("")
          $("#add_new_location input#active").prop("checked", false)
          $("#add_new_location").modal("hide")
          $("#parent").prepend(`
          <div data-id="<?php echo $id; ?>" class="col-lg-3 col-md-4 col-sm-6 mb-2">
            <div class="card mb-2 h-100 align-content-between">
              <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                <p class="font-weight-bold my-0">
                  اسم المنطقة:
                  <input class="form-control border px-3" type="text" value="${location_name}">
                </p>
                <p class="font-weight-bold my-0">
                  سعر المنطقة:
                  <input name="price" class="form-control border px-3" type="number" value="${location_price}">
                </p>
                <div class="form-check bg-gradient-dark mt-2 px-3 radius-2 py-1 pb-0 rounded" style="width:fit-content">
                  <input class="form-check-input" type="checkbox" name="location" style="cursor:pointer;" ${(active == true) ? 'checked' : ''}>
                  <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                    التفعيل
                  </label>
                </div>
                <div class="m-auto mb-0 mt-1">
                  <button class="btn btn-success mx-1" onclick="save_location(<?php echo $id; ?>, this)">حفظ</button>
                  <button class="btn btn-danger mx-1" onclick="remove_location(<?php echo $id; ?>, this)">إزالة</button>
                </div>
              </div>
            </div>
          </div>
          `)
        }
      })
    }
  </script>
</body>

</html>