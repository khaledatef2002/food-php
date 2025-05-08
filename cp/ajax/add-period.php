<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['fromD']) && isset($_POST['fromT']) && isset($_POST['toT']) && isset($_POST['toD']) && is_logged() && check_user_perm(['working-period-add'])) {
    $fromD = filter_var($_POST['fromD'], FILTER_SANITIZE_NUMBER_INT);
    $toD = filter_var($_POST['toD'], FILTER_SANITIZE_NUMBER_INT);

    $fromT = htmlspecialchars($_POST['fromT']);
    $toT = htmlspecialchars($_POST['toT']);

    $start_time = explode(':', $_POST['fromT']);
    $from = mysqli_real_escape_string($GLOBALS['conn'], $fromD . $start_time[0] . $start_time[1] . "00");

    $end_time = explode(':', $_POST['toT']);
    $to = mysqli_real_escape_string($GLOBALS['conn'], $toD . $end_time[0] . $end_time[1] . "00");

    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO work_periods(from_date, to_date) VALUES('$from','$to')");

    $id = mysqli_insert_id($GLOBALS['conn']);

    $admin = get_admin_info()['nickname'];

    logg("work periods", "لقد قام $admin باضافة فترة عمل جديده من $from الى $to ومعرف $id");

    if (!$insertion) {
        echo "error";
    } else {
        $id = mysqli_insert_id($GLOBALS['conn']);
?>
        <div data-id="<?php echo $id; ?>" class="col-lg-3 col-md-4 col-sm-6 mb-2">
            <div class="card mb-2 h-100 align-content-between">
                <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                    <p class="font-weight-bold my-0">
                        من:
                        <select name="from_day" class="form-control border px-1 mb-2">
                            <option value="0" <?php echo ($fromD == 0) ? 'SELECTED' : ''; ?>>السبت</option>
                            <option value="1" <?php echo ($fromD == 1) ? 'SELECTED' : ''; ?>>الاحد</option>
                            <option value="2" <?php echo ($fromD == 2) ? 'SELECTED' : ''; ?>>الاثنين</option>
                            <option value="3" <?php echo ($fromD == 3) ? 'SELECTED' : ''; ?>>الثلاثاء</option>
                            <option value="4" <?php echo ($fromD == 4) ? 'SELECTED' : ''; ?>>الاربعاء</option>
                            <option value="5" <?php echo ($fromD == 5) ? 'SELECTED' : ''; ?>>الخميس</option>
                            <option value="6" <?php echo ($fromD == 6) ? 'SELECTED' : ''; ?>>الجمعة</option>
                        </select>
                        <input name="from_time" class="form-control border px-1" type="time" value="<?php echo $fromT; ?>">
                    </p>
                    <p class="font-weight-bold my-0">
                        الى:
                        <select name="to_day" class="form-control border px-1 mb-2">
                            <option value="0" <?php echo ($toD == 0) ? 'SELECTED' : ''; ?>>السبت</option>
                            <option value="1" <?php echo ($toD == 1) ? 'SELECTED' : ''; ?>>الاحد</option>
                            <option value="2" <?php echo ($toD == 2) ? 'SELECTED' : ''; ?>>الاثنين</option>
                            <option value="3" <?php echo ($toD == 3) ? 'SELECTED' : ''; ?>>الثلاثاء</option>
                            <option value="4" <?php echo ($toD == 4) ? 'SELECTED' : ''; ?>>الاربعاء</option>
                            <option value="5" <?php echo ($toD == 5) ? 'SELECTED' : ''; ?>>الخميس</option>
                            <option value="6" <?php echo ($toD == 6) ? 'SELECTED' : ''; ?>>الجمعة</option>
                        </select>
                        <input name="to_time" class="form-control border px-1" type="time" value="<?php echo $toT; ?>">
                    </p>
                    <div class="m-auto mb-0 mt-1">
                        <button class="btn btn-success mx-1" onclick="save_period(<?php echo $id; ?>, this)">حفظ</button>
                        <button class="btn btn-danger mx-1" onclick="remove_period(<?php echo $id; ?>, this)">حذف</button>
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