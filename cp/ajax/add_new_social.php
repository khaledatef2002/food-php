<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['icon']) && isset($_POST['link']) && is_logged() && check_user_perm(['social-page-add'])) {
    $icon = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['icon'], FILTER_SANITIZE_URL));
    $link = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['link'], FILTER_SANITIZE_URL));
    $get_last_sort = mysqli_query($GLOBALS['conn'], "SELECT * FROM social_media ORDER BY sort DESC");
    $last_sort = mysqli_fetch_assoc($get_last_sort);
    $sort = $last_sort['sort'] + 1;
    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO social_media(sort,img_url,link) VALUES('$sort','$icon','$link')");

    $id = mysqli_insert_id($GLOBALS['conn']);

    $admin = get_admin_info()['nickname'];

    logg("social", "لقد قام $admin باضافة أيقونة وسائل تواصل جديده بمعرف $id");

    if (!$insertion) {
        echo "error";
    } else {
        $id = mysqli_insert_id($GLOBALS['conn']);
?>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $id; ?>" data-sort="<?php echo $sort; ?>">
            <div class="card mb-2 h-100">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="my-0">
                        <span class="badge badge-sm bg-gradient-primary mx-auto mb-1 d-block cursor-pointer">
                            <i class="fas fa-grip-horizontal"></i>
                            <span class="order"><?php echo $sort; ?></span>
                            <i class="fas fa-grip-horizontal"></i>
                        </span>
                        <div class="icon" style="width:100%;height:fit-content;">
                            <?php include "../../" . $icon; ?>
                        </div>
                    </div>
                    <p class="font-weight-bold my-0 fs-6 d-flex align-items-center justify-content-center flex-fill text-break text-center">
                        <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
                    </p>
                    <div class="d-flex justify-content-between">
                        <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_social_modal(<?php echo $id; ?>, '<?php echo trim($icon); ?>', '<?php echo trim($link); ?>')">تعديل</button>
                        <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_social(<?php echo $id; ?>,this)">حذف</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "error";
}

?>