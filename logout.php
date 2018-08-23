<?php
    require_once("common.php");

    session_destroy();
    header("location: /");
    die();

