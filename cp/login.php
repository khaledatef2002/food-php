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
<html lang="en">

<head>
  <?php include 'temps/head.php'; ?>
  <title>
    cPanel V2
  </title>
</head>
<?php

  if(is_logged())
  {
    header('Location: index.php');
  }

?>
<style>
  body {
    direction: rtl;
}

.input-group.input-group-outline .form-label:before {
    margin-left: 4px;
    border-right: solid 1px transparent;
    border-radius: 0 5px;

    margin-right: 0;
    border-left: 0;
}
  
.input-group.input-group-outline .form-label:after {
    margin-right: 4px;
    border-left: solid 1px transparent;

    border-radius: 0;
    margin-left: 0;
    border-right: 0;
}
</style>
<body class="bg-white">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow-none position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0 justify-content-center">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 ">
              Powered By Diafh
            </a>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100">
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom shadow-none">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex flex-column justify-content-center align-items-center">
                <img class="rounded mx-auto mb-2" src="../<?php echo $site_setting['site-logo']; ?>" alt="" srcset="" height="80">
                <div class="border-radius-lg py-2 pe-1">
                  <h4 class="font-weight-bolder text-center my-1 text-dark mb-0 fs-2">تـسـجـيـل الـدخـول</h4>
                </div>
              </div>
              <div class="card-body">
                <form id="login-form" method="POST" role="form" class="text-start">
                  <div class="input-group input-group-outline my-3 mt-0">
                    <label for="username" class="form-label" style="z-index: 9;">اسم المستخدم</label>
                    <input id="username" name="username" type="text" class="form-control text-dark" style="background:#f0f0f0;">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label for="password" class="form-label" style="z-index: 9;">كلمة المرور</label>
                    <input id="password" name="password" type="password" class="form-control text-dark" style="background:#f0f0f0;">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary shadow-none w-100 my-3 mb-0 spinner-button-loading"><span class="content-button-loading">تسجيل الدخول</span><div class="lds-dual-ring"></div></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'temps/footer.php'; ?>
    </div>
  </main>
  <?php include 'temps/jslibs.php'; ?>
</body>

</html>