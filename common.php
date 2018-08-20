<?php

    session_start();
    require_once("config.php");

    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    function translate($word) {

        return $word;
    }