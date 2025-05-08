<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['name']) && isset($_POST['active']) && is_logged() && check_user_perm(['categories-add'])) {
    $name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars(trim($_POST['name'])));
    $active = ($_POST['active'] == "true") ? 1 : 0;
    $get_last_sort = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories ORDER BY sort DESC");
    $last_sort = mysqli_fetch_assoc($get_last_sort);
    $sort = $last_sort['sort'] + 1;
    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO food_categories(category_name,active,sort) VALUES('$name','$active','$sort')");

    $id = mysqli_insert_id($GLOBALS['conn']);

    $admin = get_admin_info()['nickname'];

    logg("categories", "لقد قام $admin باضافة تصنيف جديد باسم $name ومعرف $id");

    if (!$insertion) {
        echo "error";
    } else {
        $id = mysqli_insert_id($GLOBALS['conn']);
?>
        <div class="row my-1" data-id="<?php echo $id; ?>" data-sort="<?php echo $sort; ?>">
            <div class="card mb-2">
                <div class="card-body p-3 py-1 d-flex flex-row align-items-center justify-content-between">
                    <span class="badge badge-sm bg-gradient-primary mb-1 cursor-pointer">
                        <i class="fas fa-grip-horizontal"></i>
                        <span class="order"><?php echo $sort; ?></span>
                        <i class="fas fa-grip-horizontal"></i>
                    </span>
                    <div class="vr mx-3"></div>
                    <p class="my-0">
                        <?php echo $name; ?>
                    </p>
                    <div class="vr"></div>
                    <p class="my-0">
                        عدد الاصناف:
                        0
                    </p>
                    <div class="vr"></div>
                    <p class="my-0 form-check d-flex">
                        التفعيل:
                        <input class="form-check-input" type="checkbox" name="cat<?php echo $id; ?>" onchange="change_cat_active(<?php echo $id; ?>,this)" style="cursor:pointer;" <?php echo ($active == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                    </p>
                    <div class="vr"></div>
                    <div class="d-flex justify-content-between">
                        <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_category_modal(this)">تعديل</button>
                        <button class="btn bg-gradient-danger text-white my-1 py-1 mx-2" onclick="remove_category(<?php echo $id; ?>,this)">حذف</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo $_POST['icon'];
}

?>