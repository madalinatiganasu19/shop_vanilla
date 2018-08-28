<?php

    session_start();
    require_once("config/config.php");

    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    $err = "";
    $errors[] = array();

     function security_check() {

         if (!isset($_SESSION["logged"])) {
             header("location: login.php");
             die();
         }
     }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    function translate($word) {

        return $word;
    }

    function sanitize($input) {

        $input = trim($input);
        $input = strip_tags($input);

        return $input;
    }

    function validate_and_upload_image(&$image, &$err) {


            $upload_dir = "images/";

            $file_name = $_FILES["image"]["name"];
            $tmp_name = $_FILES["image"]["tmp_name"];
            $file = $upload_dir . basename($_FILES["image"]["name"]);
            $image_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (empty($err) && !empty($file_name)) {
                if ($image_ext != "jpg" && $image_ext != "jpeg" && $image_ext != "png") {
                    $err = translate("Only .jpg, .jpeg, .png files allowed!");
                } elseif (file_exists($file)) {
                    $err = translate("This file already exists!");
                }
            }

            if (empty($err)) {
                if (move_uploaded_file($tmp_name, $upload_dir . $file_name)) {
                    $image = $file_name;
                }
            }
        }


    function validate(&$title, &$description, &$price, &$err) {

        $title = sanitize($_POST["title"]);
        $description = sanitize($_POST["description"]);
        $price = sanitize($_POST["price"]);

        if (empty($title) || empty($description) || empty($price)) {
            $err = translate("All fields required!");
        }

    }