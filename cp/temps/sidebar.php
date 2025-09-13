<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret  bg-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="nav-link text-body font-weight-bold px-0 text-white text-center d-flex justify-content-center align-items-center" href="profile.php">
      <img style="width:40px;height:40px;border-radius:50%;margin-left:10px;" src="../<?php echo get_admin_info()['img']; ?>" alt="">
      <span class="d-sm-inline text-white fs-5"><?php echo get_admin_info()['nickname']; ?></span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-0">
  <div class="collapse navbar-collapse px-0 w-auto d-flex flex-column justify-content-between" id="sidenav-collapse-main" style="height:calc(100% - 79px);overflow:auto;">
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <ul id="side-bar" class="navbar-nav nav-list overflow-auto">
      <li class="nav-item">
        <a class="nav-link py-2 <?php echo ($currentPage == 'index.php') ? 'active bg-gradient-primary' : ''; ?>" href="index.php">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-home"></i>
          </div>
          <span class="nav-link-text me-1">الصفحة الرئيسية</span>
        </a>
      </li>
      <?php if (check_user_perm(['qrcode-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" role="button">
          <a class="nav-link justify-content-between mx-0 px-1 py-2" href="qrcode.php">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-qrcode mx-2 mr-0"></i> QR Code</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['general-settings-visa-view', "general-settings-view", "main-page-cover-view", "colors-settings-view", "working-period-view", "branches-view", "locations-view"])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#settings" aria-expanded="false" role="button">
          <a class="nav-link justify-content-between mx-0 px-1 py-2">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-cogs mx-2 mr-0"></i> اعدادات</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'working-periods.php' || $currentPage == 'delivery-areas.php' || $currentPage == 'main-cover.php' || $currentPage == 'general-settings.php') ? 'show' : ''; ?>" id="settings">
          <?php if (check_user_perm(['main-page-cover-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'main-cover.php') ? 'active bg-gradient-primary' : ''; ?>" href="main-cover.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-image"></i>
                </div>
                <span class="nav-link-text me-1">غلاف الصفحة الرئيسية</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['general-settings-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'general-settings.php') ? 'active bg-gradient-primary' : ''; ?>" href="general-settings.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-cogs"></i>
                </div>
                <span class="nav-link-text me-1">الاعدادات العامة</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['colors-settings-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'colors-settings.php') ? 'active bg-gradient-primary' : ''; ?>" href="colors-settings.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-palette"></i>
                </div>
                <span class="nav-link-text me-1">اعدادات الالوان</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['working-period-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'working-periods.php') ? 'active bg-gradient-primary' : ''; ?>" href="working-periods.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-list-alt"></i>
                </div>
                <span class="nav-link-text me-1">فترات العمل</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['branches-view', 'locations-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'delivery-areas.php' || $currentPage == 'branches.php') ? 'active bg-gradient-primary' : ''; ?>" href="branches.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-truck"></i>
                </div>
                <span class="nav-link-text me-1">الفروع ومناطق التوصيل</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (check_user_perm(['social-page-view', 'rating-page-view', 'order-page-view', 'main-page-icon-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#general" aria-expanded="false" role="button">
          <a class="nav-link py-2 justify-content-between mx-0 px-1">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-globe mx-2 mr-0"></i> عام</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'main-page-icon.php' || $currentPage == 'social-media.php' || $currentPage == 'ratings.php' || $currentPage == 'order-delivery.php') ? 'show' : ''; ?>" id="general">
          <?php if (check_user_perm(['main-page-icon-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'main-page-icon.php') ? 'active bg-gradient-primary' : ''; ?>" href="main-page-icon.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-user-circle"></i>
                </div>
                <span class="nav-link-text me-1"> ايقونات الصفحة الرئيسية</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['social-page-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'social-media.php') ? 'active bg-gradient-primary' : ''; ?>" href="social-media.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-user-circle"></i>
                </div>
                <span class="nav-link-text me-1">وسائل التواصل</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['rating-page-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'ratings.php') ? 'active bg-gradient-primary' : ''; ?>" href="ratings.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="nav-link-text me-1">التقييمات</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['order-page-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'order-delivery.php') ? 'active bg-gradient-primary' : ''; ?>" href="order-delivery.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-shopping-basket"></i>
                </div>
                <span class="nav-link-text me-1">اطلب دليفري</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (check_user_perm(['offers-page-cover-view', 'offers-page-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#offers" aria-expanded="false" role="button">
          <a class="nav-link py-2 justify-content-between mx-0 px-1">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-percent mx-2 mr-0"></i> العروض</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'offers-cover.php' || $currentPage == 'offers.php') ? 'show' : ''; ?>" id="offers">
          <?php if (check_user_perm(['offers-page-cover-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'offers-cover.php') ? 'active bg-gradient-primary' : ''; ?>" href="offers-cover.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-images"></i>
                </div>
                <span class="nav-link-text me-1">غلاف العروض</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['offers-page-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'offers.php') ? 'active bg-gradient-primary' : ''; ?>" href="offers.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-percentage"></i>
                </div>
                <span class="nav-link-text me-1">العروض</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (check_user_perm(['safwa-card-cover-view', 'safwa-card-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#safwa" aria-expanded="false" role="button">
          <a class="nav-link py-2 justify-content-between mx-0 px-1">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-star mx-2 mr-0"></i> الصفوة</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'safwa-card.php' || $currentPage == 'safwa-card-cover.php' || $currentPage == 'safwa-cover.php' || $currentPage == 'safwa.php') ? 'show' : ''; ?>" id="safwa">
          <?php if (check_user_perm(['safwa-card-cover-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'safwa-card-cover.php') ? 'active bg-gradient-primary' : ''; ?>" href="safwa-card-cover.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="far fa-file-image"></i>
                </div>
                <span class="nav-link-text me-1">غلاف كارت الصفوة</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['safwa-card-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'safwa-card.php') ? 'active bg-gradient-primary' : ''; ?>" href="safwa-card.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="far fa-id-card"></i>
                </div>
                <span class="nav-link-text me-1">كارت الصفوة</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (check_user_perm(['manual-menu-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'menu.php') ? 'active bg-gradient-primary' : ''; ?>" href="menu.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-cart-arrow-down"></i>
            </div>
            <span class="nav-link-text me-1">المنيو</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['categories-view', 'items-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#elec-items" aria-expanded="false" role="button">
          <a class="nav-link py-2 justify-content-between mx-0 px-1">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-sitemap mx-2 mr-0"></i> منتجات المطعم</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'categories.php' || $currentPage == 'show-items.php' || $currentPage == 'add-item.php' || $currentPage == 'edit-item.php') ? 'show' : ''; ?>" id="elec-items">
          <?php if (check_user_perm(['categories-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'categories.php') ? 'active bg-gradient-primary' : ''; ?>" href="categories.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-list-ol"></i>
                </div>
                <span class="nav-link-text me-1">التصنيفات</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['items-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'show-items.php' || $currentPage == 'add-item.php' || $currentPage == 'edit-item.php') ? 'active bg-gradient-primary' : ''; ?>" href="show-items.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-utensils"></i>
                </div>
                <span class="nav-link-text me-1">الاصناف</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (check_user_perm(['discounts-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2" href="discounts.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-percent"></i>
            </div>
            <span class="nav-link-text me-1">الخصومات</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['live-orders-view']) && check_user_perm(['orders-data-view'])) : ?>
        <hr class="bg-white my-1">
        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#order-online" aria-expanded="false" role="button">
          <a class="nav-link justify-content-between mx-0 px-1 py-2">
            <span class="nav-link-text me-1 font-weight-bolder"><i class="fas fa-globe-asia mx-2 mr-0"></i> اوردر اونلاين</span>
            <i class="fas fa-angle-double-left fs-6"></i>
          </a>
        </li>
        <div class="collapse <?php echo ($currentPage == 'live-order.php' || $currentPage == 'orders-data.php') ? 'show' : ''; ?>" id="order-online">
          <?php if (check_user_perm(['live-orders-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'live-order.php') ? 'active bg-gradient-primary' : ''; ?>" href="live-order.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-bolt"></i>
                </div>
                <span class="nav-link-text me-1">الطلبات لايف</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if (check_user_perm(['orders-data-view'])) : ?>
            <li class="nav-item">
              <a class="nav-link py-2 <?php echo ($currentPage == 'orders-data.php') ? 'active bg-gradient-primary' : ''; ?>" href="orders-data.php">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-history"></i>
                </div>
                <span class="nav-link-text me-1">سجل الطلبات</span>
              </a>
            </li>
          <?php endif; ?>
        </div>
      <?php elseif (check_user_perm(['live-orders-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'live-order.php') ? 'active bg-gradient-primary' : ''; ?>" href="live-order.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-bolt"></i>
            </div>
            <span class="nav-link-text me-1">الطلبات لايف</span>
          </a>
        </li>
      <?php elseif (check_user_perm(['orders-data-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'orders-data.php') ? 'active bg-gradient-primary' : ''; ?>" href="orders-data.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history"></i>
            </div>
            <span class="nav-link-text me-1">سجل الطلبات</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['switch-items'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'switch-items.php') ? 'active bg-gradient-primary' : ''; ?>" href="switch-items.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history"></i>
            </div>
            <span class="nav-link-text me-1">تعطيل/تشغيل صنف</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['roles-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'roles.php' || $currentPage == 'edit-role.php' || $currentPage == 'add-role.php') ? 'active bg-gradient-primary' : ''; ?>" href="roles.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-universal-access"></i>
            </div>
            <span class="nav-link-text me-1">الادوار</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (check_user_perm(['accounts-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'accounts.php' || $currentPage == 'edit-account.php' || $currentPage == 'add-account.php') ? 'active bg-gradient-primary' : ''; ?>" href="accounts.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-user-edit"></i>
            </div>
            <span class="nav-link-text me-1">الحسابات</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (check_user_perm(['log-view'])) : ?>
        <li class="nav-item">
          <a class="nav-link py-2 <?php echo ($currentPage == 'logs.php') ? 'active bg-gradient-primary' : ''; ?>" href="logs.php">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-database"></i>
            </div>
            <span class="nav-link-text me-1">السجلات</span>
          </a>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link py-2" href="logout.php">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-sign-out-alt"></i>
          </div>
          <span class="nav-link-text me-1">تسجيل خروج</span>
        </a>
      </li>
    </ul>
  </div>
</aside>