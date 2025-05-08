<?php

include '../../includes/conn.php';
include '../functions/main.php';


if (isset($_POST['title']) && isset($_POST['des']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['active']) && isset($_FILES['image']) && is_logged() && check_user_perm(['safwa-card-add'])) {
    $msg = mysqli_real_escape_string($GLOBALS['conn'], upload_image($_FILES['image']));
    $title = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['title']));
    $des = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['des']));
    $start = strtotime($_POST['start']);
    $end = strtotime($_POST['end']);
    $active = ($_POST['active'] == "true") ? 1 : 0;

    $id = mysqli_insert_id($GLOBALS['conn']);

    $admin = get_admin_info()['nickname'];

    logg("safwa card", "لقد قام $admin باضافة جديدة الى كارت الصفوه بعنوان $title ومعرف $id");

    if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
        return $msg;
    } else {

        mysqli_query($GLOBALS['conn'], "INSERT INTO safwa_cards(url, title, description, active, start_date, last_date) VALUES('$msg', '$title', '$des', '$active', '$start', '$end')");
        $get_offer = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_cards ORDER BY last_date DESC");
        while ($offer = mysqli_fetch_assoc($get_offer)) {
?>
            <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $offer['id']; ?>">
                <div class="card mb-2 h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="icon" style="width:100%;height:fit-content;">
                            <img src="<?php echo "../" . $offer['url']; ?>" style="width:100%;">
                        </div>
                        <div>
                            <p class="text-center font-weight-bold text-dark mb-0">
                                <?php echo $offer['title']; ?>
                            </p>
                            <p class="text-center">
                                <?php echo $offer['description']; ?>
                            </p>
                            <p class="text-right">
                                <label class="text-dark font-weight-bold">يبدأ من: </label>
                                <span><bdi><?php echo date("Y-m-d", $offer['start_date']); ?></bdi></span>
                            </p>
                            <p class="text-right">
                                <label class="text-dark font-weight-bold">ينتهي في: </label>
                                <span><bdi><?php echo date("Y-m-d", $offer['last_date']); ?></bdi></span>
                            </p>
                            <p class="text-right">
                                <label class="text-dark font-weight-bold">الحالة: </label>
                                <span><?php echo ($offer['active']) ? 'مُفعل' : 'غير مُفعل'; ?></span>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_offer_modal(<?php echo $cover['id']; ?>, '<?php echo $cover['url']; ?>' , '<?php echo $cover['title']; ?>', '<?php echo $cover['description']; ?>', this)">تعديل</button>
                            <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_offer(<?php echo $offer['id']; ?>,this)">حذف</button>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>