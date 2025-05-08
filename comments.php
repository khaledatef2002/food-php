<?php include "temps/settings.php"; ?>
<!DOCTYPE html>
<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
    <?php include "temps/head.php"; ?>
    <title><?php echo $site_setting['site-title']; ?> - Comments Page</title>
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>
    <?php include "temps/header.php"; ?>
    <?php
    session_start();
    $err = "";
    if (isset($_POST['client_name']) && isset($_POST['order_date']) && isset($_POST['order_num']) && isset($_POST['client_age']) && isset($_POST['client_phone']) && isset($_POST['client_address']) && isset($_POST['taste']) && isset($_POST['speed']) && isset($_POST['service']) && isset($_POST['clean']) && isset($_POST['general']) && isset($_POST['referal']) && isset($_POST['yes_no_1']) && isset($_POST['yes_no_2']) && isset($_POST['yes_no_3'])) {
        if (empty(trim($_POST['client_name'])) || strlen(trim($_POST['client_name'])) < 3 || strpos($_POST['client_name'], '<') !== false || strpos($_POST['client_name'], '>')  !== false || strpos($_POST['client_name'], '"')  !== false || strpos($_POST['client_name'], "'")  !== false || strpos($_POST['client_name'], '/')  !== false || strpos($_POST['client_name'], '&')  !== false || strpos($_POST['client_name'], ';')  !== false) {
            $err = "يرجى اختيار اسم مستخدم صحيح";
        }
        if (!valid_date($_POST['order_date']) || strtotime($_POST['order_date']) > time()) {
            $err = "يرجى ادخال تاريخ طلب صحيح";
        } else if (empty($_POST['order_num']) || $_POST['order_num'] < 1 || is_nan($_POST['order_num'])) {
            $err = "يرجى ادخال رقم طلب صحيح";
        } else if (strlen(trim($_POST['client_phone'])) != 11 || !(substr($_POST['client_phone'], 0, 3) != "010" || substr($_POST['client_phone'], 0, 3) != "011" || substr($_POST['client_phone'], 0, 3) != "012" || substr($_POST['client_phone'], 0, 3) != "015") || is_nan($_POST['client_phone'])) {
            $err = "يرجى ادخال رقم هاتف صحيح";
        } else if (empty($_POST['client_age']) || $_POST['client_age'] < 10 || $_POST['client_age'] > 100 || is_nan($_POST['client_age'])) {
            $err = "يرجى اختيار عمر صحيح";
        } else if (empty($_POST['client_address']) || strlen($_POST['client_address']) < 5 || strlen($_POST['client_address']) > 40) {
            echo strlen($_POST['client_address']);
            $err = "عنوان المستخدم يجب الا يقل عن 5 حروف ولا يزيد عن 40 حرف";
        } else if ($_POST['taste'] == "" || is_nan($_POST['taste']) || !in_array($_POST['taste'], array(0, 1, 2, 3))) {
            echo $_POST['taste'];
            $err = "يرجى تقييم الطعم";
        } else if ($_POST['speed'] == "" || is_nan($_POST['speed']) || !in_array($_POST['speed'], [0, 1, 2, 3])) {
            $err = "يرجى تقييم السرعة";
        } else if ($_POST['service'] == "" || is_nan($_POST['service']) || !in_array($_POST['service'], [0, 1, 2, 3])) {
            $err = "يرجى تقييم الخدمة";
        } else if ($_POST['clean'] == "" || is_nan($_POST['clean']) || !in_array($_POST['clean'], [0, 1, 2, 3])) {
            $err = "يرجى تقييم النظافه";
        } else if ($_POST['general'] == "" || is_nan($_POST['general']) || !in_array($_POST['general'], [0, 1, 2, 3])) {
            $err = "يرجى تقييم الجو العام";
        } else if ($_POST['referal'] == "" || is_nan($_POST['referal']) || !in_array($_POST['referal'], [0, 1, 2, 3, 4])) {
            $err = "يرجى تحدد طريقة معرفة المطعم";
        } else if ($_POST['yes_no_1'] == "" || is_nan($_POST['yes_no_1']) || !in_array($_POST['yes_no_1'], [0, 1])) {
            $err = "يرجى الاجابة عن سؤال هل دي اول تجربة للمطعم؟";
        } else if ($_POST['yes_no_2'] == "" || is_nan($_POST['yes_no_2']) || !in_array($_POST['yes_no_2'], [0, 1])) {
            $err = "يرجى الاجابة عن سؤال في حالة وجود مشكلة هل تم توضيحها لمدير المطعم؟";
        } else if ($_POST['yes_no_3'] == "" || is_nan($_POST['referal']) || !in_array($_POST['yes_no_3'], [0, 1])) {
            $err = "يرجى الاجابه عن سؤال هل التواصل كان بالشكل المطلوب؟";
        } else {
            $_POST['client_address'] = htmlspecialchars($_POST['client_address']);
            $_POST['advice'] = htmlspecialchars($_POST['advice']);

            $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO ratings(client_name, order_date, order_num, client_age, client_phone, client_address, taste, speed, service, clean, general, referal, yes_no_1, yes_no_2, yes_no_3, advice) VALUES('" . $_POST['client_name'] . "','" . strtotime($_POST['order_date']) . "','" . $_POST['order_num'] . "','" . $_POST['client_age'] . "','" . $_POST['client_phone'] . "','" . $_POST['client_address'] . "','" . $_POST['taste'] . "','" . $_POST['speed'] . "','" . $_POST['service'] . "','" . $_POST['clean'] . "','" . $_POST['general'] . "','" . $_POST['referal'] . "','" . $_POST['yes_no_1'] . "','" . $_POST['yes_no_2'] . "','" . $_POST['yes_no_3'] . "','" . $_POST['advice'] . "')");
            $success = "تم ارسال تقييمك بنجاح";
            $_POST = array();
        }
    }

    function valid_date($date)
    {
        $validator = explode("-", trim($date));
        $to_ret = true;
        foreach ($validator as $r) {
            if (is_nan($r)) {
                $to_ret = false;
                break;
            }
        }
        return $to_ret;
    }
    ?>
    <form method="post" style="height: calc(100% - 120px);
    position: relative;
    top: 30px;
    margin-top: 20px;">
        <div class="comments col-lg-6 col-lg-push-3 col-md-8 col-md-push-2 col-xs-12">
            <div class="row col-md-8 col-md-push-2 col-xs-12">
                <div class="item col-xs-12">
                    <?php
                    if (!empty($err))
                        echo '<div class="alert alert-danger" role="alert" style="text-align:center;direction:rtl;">' . $err . '</div>';
                    if (!empty($success))
                        echo '<div class="alert alert-success" role="alert" style="text-align:center;direction:rtl;">' . $success . '</div>';
                    ?>
                    <h2>رأيك يفرق معانا</h2>


                    <span class="col-xs-12">
                        <p>الأسم: </p>
                        <input name="client_name" value="<?php echo $_post['client_name'] ?? ''; ?>" type="text" minlength="3" maxlength="30" required>
                    </span>


                    <span class="col-xs-6">
                        <p>التاريخ: </p>
                        <input name="order_date" value="<?php echo $_post['order_date'] ?? ''; ?>" type="date" style="padding:0;text-align:center;font-size:small;" max="<?php echo date("Y-m-d", time()); ?>" required>
                    </span>
                    <span class="span-small-second col-xs-6">
                        <p>رقم الاوردر: </p>
                        <input name="order_num" value="<?php echo $_post['order_num'] ?? ''; ?>" type="number" min="0" oninput="this.value = Math.abs(this.value)" required>
                    </span>
                    <span class="col-xs-6">
                        <p>السن: </p>
                        <input name="client_age" value="<?php echo $_post['client_age'] ?? ''; ?>" type="number" min="10" oninput="this.value = Math.abs(this.value)" max="100" required>
                    </span>
                    <span class="span-small-second col-xs-6">
                        <p>التليفون: </p>
                        <input name="client_phone" value="<?php echo $_post['client_phone'] ?? ''; ?>" type="tel" minlength="11" maxlength="11" pattern="(010|011|012|015)[0-9]{8}" required>
                    </span>
                    <span class="col-xs-12">
                        <p>العنوان: </p>
                        <input name="client_address" value="<?php echo $_post['client_address'] ?? ''; ?>" type="text" minlength="5" maxlength="40" required>
                    </span>
                </div>
            </div>
            <div class="row col-md-8 col-md-push-2 col-xs-12">
                <div class="item col-xs-12" style="text-align:center;">
                    <h2>التقييم</h2>
                    <h2>✓</h2>

                    <table id="vote">
                        <thead>
                            <th></th>
                            <th>ضعيف</th>
                            <th>متوسط</th>
                            <th>جيد جدا</th>
                            <th>ممتاز</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>الطعم</td>
                                <td>
                                    <input type="radio" name="taste" value="0" <?php echo (($_POST['taste'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="taste" value="1" <?php echo (($_POST['taste'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="taste" value="2" <?php echo (($_POST['taste'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="taste" value="3" <?php echo (($_POST['taste'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                            <tr>
                                <td>السرعة:</td>
                                <td>
                                    <input type="radio" name="speed" value="0" <?php echo (($_POST['speed'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="speed" value="1" <?php echo (($_POST['speed'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="speed" value="2" <?php echo (($_POST['speed'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="speed" value="3" <?php echo (($_POST['speed'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                            <tr>
                                <td>الخدمة:</td>
                                <td>
                                    <input type="radio" name="service" value="0" <?php echo (($_POST['service'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="service" value="1" <?php echo (($_POST['service'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="service" value="2" <?php echo (($_POST['service'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="service" value="3" <?php echo (($_POST['service'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                            <tr>
                                <td>النضافة:</td>
                                <td>
                                    <input type="radio" name="clean" value="0" <?php echo (($_POST['clean'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="clean" value="1" <?php echo (($_POST['clean'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="clean" value="2" <?php echo (($_POST['clean'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="clean" value="3" <?php echo (($_POST['clean'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                            <tr>
                                <td>الجو العام:</td>
                                <td>
                                    <input type="radio" name="general" value="0" <?php echo (($_POST['general'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="general" value="1" <?php echo (($_POST['general'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="general" value="2" <?php echo (($_POST['general'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="general" value="3" <?php echo (($_POST['general'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row col-md-8 col-md-push-2 col-xs-12">
                <div class="item col-xs-12" style="text-align:center;">
                    <h2>عرفتنا منين؟</h2>
                    <h2>✓</h2>
                    <table>
                        <thead>
                            <th>صفحات السوشيال</th>
                            <th>بلوجر</th>
                            <th>صديق</th>
                            <th>إعلان</th>
                            <th>أخرى</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="radio" name="referal" value="0" <?php echo (($_POST['referal'] ?? '-1') === 0) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="referal" value="1" <?php echo (($_POST['referal'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="referal" value="2" <?php echo (($_POST['referal'] ?? '-1') == 2) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="referal" value="3" <?php echo (($_POST['referal'] ?? '-1') == 3) ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                    <input type="radio" name="referal" value="4" <?php echo (($_POST['referal'] ?? '-1') == 4) ? 'checked' : ''; ?> required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <span class="questions col-xs-12">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>نعم</th>
                                    <th>لا</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: right;">
                                        <p>هل دي اول تجربة للمطعم؟</p>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_1" value="1" <?php echo (($_POST['yes_no_1'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_1" value="0" <?php echo (($_POST['yes_no_1'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <p>في حالة وجود مشكلة هل تم توضيحها لمدير المطعم؟</p>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_2" value="1" <?php echo (($_POST['yes_no_2'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_2" value="0" <?php echo (($_POST['yes_no_2'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <p>هل التواصل كان بالشكل المطلوب؟</p>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_3" value="1" <?php echo (($_POST['yes_no_3'] ?? '-1') == 1) ? 'checked' : ''; ?> required>
                                    </td>
                                    <td>
                                        <input type="radio" name="yes_no_3" value="0" <?php echo (($_POST['yes_no_3'] ?? '-1') == 0) ? 'checked' : ''; ?> required>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </span>
                </div>
            </div>
            <div class="row col-md-8 col-md-push-2 col-xs-12">
                <div class="item col-xs-12" style="text-align:center;">
                    <h2 style="width: 80%;margin: 15px auto;">تقترح علينا اية؟</h2>
                    <textarea name="advice" style="direction:rtl;"><?php echo $_POST['advice'] ?? ''; ?></textarea>

                    <input type="submit" value="ارسال التقييمات">
                </div>
            </div>
        </div>
    </form>
    <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;">
        <?php include 'temps/footer.php'; ?>
    </div>
    <?php include 'temps/jslibs.php'; ?>
</body>

</html>