<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['icon']) && isset($_POST['data']) && is_logged() && check_user_perm(['order-page-add'])) {
    //validate icon
    if (!in_array($_POST['icon'], ['order_online', 'phone', 'whatsapp'])) {
        echo "error";
        exit;
    }

    $icon = mysqli_real_escape_string($GLOBALS['conn'], $_POST['icon']);
    $data = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['data']));
    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO order_page(type, value) VALUES('$icon','$data')");

    $id = mysqli_insert_id($GLOBALS['conn']);

    $admin = get_admin_info()['nickname'];

    logg("order page", "لقد قام $admin باضافة ايقونة اطلب دليفري جديدة بمعرف $id");

    if (!$insertion) {
        echo "error";
    } else {
        $id = mysqli_insert_id($GLOBALS['conn']);
?>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $id; ?>">
            <div class="card mb-2 h-100">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="my-0">
                        <div class="icon" style="width:100%;height:fit-content;">
                            <?php
                            switch ($icon) {
                                case 'order_online':
                                    include "../../imgs/wb-esite.svg";
                                    break;
                                case 'phone':
                                    include '../../imgs/newhot.svg';
                                    break;
                                case 'whatsapp':
                                    include '../../imgs/whatsapp.svg';
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                    <p class="font-weight-bold my-0 fs-6 d-flex align-items-center justify-content-center flex-fill text-break text-center">
                        <?php echo $data; ?>
                    </p>
                    <div class="d-flex justify-content-center">
                        <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_order_page_icon(<?php echo $id; ?>,this)">حذف</button>
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