<?php 

// Validate

if(empty($_POST["firstname"])){
    $info["errors"]["firstname"] = "A First Name Is Required!";
} else if(!preg_match("/^[\p{L}]+$/", $_POST["firstname"])){
    $info["errors"]["firstname"] = "First Name should only contain characters not numbers, symbols or spaces!!";
}

if(empty($_POST["lastname"])){
    $info["errors"]["lastname"] = "A Last Name Is Required!";
} else if(!preg_match("/^[\p{L}]+$/", $_POST["lastname"])){
    $info["errors"]["lastname"] = "Last Name should only contain characters not numbers, symbols or spaces!!";
}

if(empty($_POST["email"])){
    $info["errors"]["email"] = "An Email Is Required!";
} else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    $info["errors"]["email"] = "Enter a Valid Email!";
}

if(empty($_POST["password"])){
    $info["errors"]["password"] = "A Password Is Required!";
} else if($_POST["password"] !== $_POST["retype_password"]){
    $info["errors"]["password"] = "Enter a Matching Passwords!";
} else if(strlen($_POST["password"]) < 8){
    $info["errors"]["password"] = "Password must be at least 8 characters long!";
}

$genders = ["Male", "Female"];
if(empty($_POST["gender"])){
    $info["errors"]["gender"] = "A Gender Is Required!";
} else if(!in_array($_POST["gender"], $genders)){
    $info["errors"]["gender"] = "Gender is not valid!";
} 


if(empty($info["errors"])){
    // Send data to the database 

    $arr = [];
    $arr["firstname"] = $_POST["firstname"];
    $arr["lastname"] = $_POST["lastname"];
    $arr["email"] = $_POST["email"];
    $arr["phone_no"] = $_POST["phone_no"];
    $arr["gender"] = $_POST["gender"];
    $arr["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $arr["date"] = date("Y-m-d H:i:s");

    db_query("INSERT INTO users (firstname, lastname, email, phone_no, gender, password, date) VALUES (:firstname, :lastname, :email, :phone_no, :gender, :password, :date);", $arr);

    $info["success"] = true;
}