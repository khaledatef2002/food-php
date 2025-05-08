<?php
if(isset($_POST['signup'])){
    echo '<pre>';
     var_dump($_POST);
     die();
 }else{
     echo "error";
 }
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
$file = 'users.json';

$firstName =$_POST['fname'];
$lastName = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];



$newUser = [
    'firsttName' => $firstName,
    'lastName' => $lastName,
    'email' =>  $email,
    'password' => $password,
];

$jsonArr = [];

if(file_exists($file)){
    $data = file_get_contents($file);
    $jsonArr = json_decode($data , true);
}

$jsonArr[] = $newUser;

if(file_put_contents($file , json_encode($jsonArr , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))){
    echo "data saved successsfully";
}else{
    echo "error";
}};
?>