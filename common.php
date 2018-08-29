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
