<?php

include '../../includes/conn.php';
include '../functions/main.php';


if (isset($_FILES['image']) && is_logged() && check_user_perm(['manual-menu-add'])) {
    $msg = mysqli_real_escape_string($GLOBALS['conn'], upload_image($_FILES['image']));
    if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
        return $msg;
    } else {
        $get_last_menu_sort = mysqli_query($GLOBALS['conn'], "SELECT * FROM menu ORDER BY sort DESC");
        $sort = (mysqli_fetch_assoc($get_last_menu_sort)['sort'] ?? 0) + 1;

        mysqli_query($GLOBALS['conn'], "INSERT INTO menu(sort,url) VALUES('$sort', '$msg')");
        $id = mysqli_insert_id($GLOBALS['conn']);

        $admin = get_admin_info()['nickname'];

        logg("menu", "لقد قام $admin باضافة صورة منيو جديده بمعرف $id");
?>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $id; ?>" data-sort="<?php echo $sort; ?>">
            <div class="card mb-2 h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="my-0">
                        <span class="badge badge-sm bg-gradient-primary mx-auto mb-1 d-block cursor-pointer">
                            <i class="fas fa-grip-horizontal"></i>
                            <span class="order"><?php echo $sort; ?></span>
                            <i class="fas fa-grip-horizontal"></i>
                        </span>
                    </div>
                    <div class="icon" style="width:100%;height:fit-content;">
                        <img src="<?php echo "../" . $msg; ?>" style="width:100%;">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_social_modal(<?php echo $id; ?>)">تعديل</button>
                        <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_menu(<?php echo $id; ?>,this)">حذف</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>