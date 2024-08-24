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

if(!empty($_POST["password"])){
    if($_POST["password"] !== $_POST["retype_password"]){
        $info["errors"]["password"] = "Enter Matching Passwords!";
    } else if(strlen($_POST["password"]) < 8){
        $info["errors"]["password"] = "Password must be at least 8 characters long!";
    }
}

$genders = ["Male", "Female"];
if(empty($_POST["gender"])){
    $info["errors"]["gender"] = "A Gender Is Required!";
} else if(!in_array($_POST["gender"], $genders)){
    $info["errors"]["gender"] = "Gender is not valid!";
} 

if(!empty($_FILES["image"]["name"])){
    // print_r($_FILES["image"]);

    $folder = "uploads/";
    if(!file_exists($folder)){ 
        mkdir($folder, 0777, true); 
        file_put_contents($folder."index.html","Access Denied!");
    }

    $allowed = ["image/jpeg", "image/jpg", "image/png"];

    if(in_array($_FILES["image"]["type"], $allowed)){
        $image = $folder . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    } else {
        $info["errors"]["image"] = "Only :". implode(", ", $allowed) ." images are files allowed!";
    }
}


if(empty($info["errors"]) && $row){
    // Send data to the database 

    $arr = [];
    $arr["firstname"] = $_POST["firstname"];
    $arr["lastname"] = $_POST["lastname"];
    $arr["email"] = $_POST["email"];
    $arr["phone_no"] = $_POST["phone_no"];
    $arr["gender"] = $_POST["gender"];
    $arr["date"] = date("Y-m-d H:i:s");
    $arr["id"] = $row["id"];
    
    $image_query = "";
    if(!empty($image)){
        $arr["image"] = $image;
        $image_query = ",image = :image";
    }

    $password_query = "";
    if(!empty($_POST["password"])){
        $arr["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $password_query = ",password = :password";
    }

    db_query("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, phone_no = :phone_no, gender = :gender, date = :date {$image_query} {$password_query} WHERE id = :id LIMIT 1;", $arr);

    if(!empty($image) && file_exists($row["image"])){
        unlink($row["image"]);
    }

    $row = db_query("SELECT * FROM users WHERE id = :id LIMIT 1", ["id" => $row["id"]]);
    if($row){
        $row = $row[0];
        $_SESSION["PROFILE"] = $row;
    }

    $info["success"] = true;
}

// UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, phone_no = :phone_no, gender = :gender, password = :password, date = :date WHERE id = :id;