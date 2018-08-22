<?php

    session_start();
    require_once("config.php");

    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    $err = "";
    $errors[] = array();

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
