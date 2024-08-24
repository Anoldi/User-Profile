<?php 

require "functions.php";

if(!empty($_POST["data_type"]))
{
    $info["data_type"] = $_POST["data_type"];
    $info["errors"] = [];
    $info["success"] = false;

    if($_POST["data_type"] == "signup"){
        require "includes/signup.php";
    } else if($_POST["data_type"] == "profile-edit"){
        $id = user("id"); 
        $row = db_query("SELECT * FROM users WHERE id = :id LIMIT 1", ["id" => $id]);
        if($row){$row = $row[0];}

        require "includes/profile-edit.php";
    } else if($_POST["data_type"] == "profile-delete"){
        $id = user("id"); 
        $row = db_query("SELECT * FROM users WHERE id = :id LIMIT 1", ["id" => $id]);
        if($row){$row = $row[0];}

        require "includes/profile-delete.php";
    } else if($_POST["data_type"] == "login"){
        require "includes/login.php";
    }

    echo json_encode($info);
}

/*

=============================
===== AJAX DB STRUCTURE =====
=============================

CREATE DATABASE profile_db;

USE profile_db;

CREATE TABLE users (
    id  INT(11) NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_no VARCHAR(100) NOT NULL,
    password VARCHAR(1024) NOT NULL,
    image VARCHAR(1024),
    gender VARCHAR(6) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIME,
    PRIMARY KEY (id)
);


 */

