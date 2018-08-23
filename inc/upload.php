<?php

    if (isset($_FILES["image"])) {
        $upload_dir = "images/";

        $file_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];
        $file = $upload_dir . basename($_FILES["image"]["name"]);
        $image_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (empty($file_name) && empty($_POST['image'])) {
            $err = translate("All fields required!");
        }

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