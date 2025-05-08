<?php
    include '../../includes/conn.php';
    include '../functions/main.php';
    $response = array();
    if(is_logged())
    {
        $response['status'] = "error";
        $response['msg'] = "انت مسجل للدخول بالفعل";
    }
    else if(isset($_POST['username']) && isset($_POST['password']))
    {
        $username = mysqli_real_escape_string($GLOBALS['conn'], $_POST['username']);
        $password = mysqli_real_escape_string($GLOBALS['conn'], $_POST['password']);
        if(empty($username) || empty($password))
        {
            $response['status'] = "error";
            $response['msg'] = "رجاء ادخال بياناتك!";
        }
        else
        {
            if(check_login($username, $password))
            {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $response['status'] = "success";

                $admin = get_admin_info()['nickname'];

                logg("login", "لقد قام $admin بتسجيل الدخول");
            }
            else
            {
                $response['status'] = "error";
                $response['msg'] = "بيانات تسجيل دخول غير صحيحة!";
            }
        }
    }
    else
    {
        $response['status'] = "error";
        $response['msg'] = "رجاء ادخال بياناتك!";
    }

    echo json_encode($response);
?>