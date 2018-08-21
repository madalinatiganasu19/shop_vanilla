<?php

    session_start();
    require_once("config.php");

    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    $err = "";

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    function translate($word) {

        return $word;
    }

    function validate($input) {

        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input);

        return $input;

    }