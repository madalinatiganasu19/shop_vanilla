<?php

    require_once ("config.php");

    $db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);


    function translate($word) {

        return $word;
    }