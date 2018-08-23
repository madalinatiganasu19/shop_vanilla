<?php
    require_once("common.php");

    if (isset($_GET['id'])) {
        //update existing product


    } else {
        //add new product
        if (isset($_POST['save'])) {

            $title = sanitize($_POST["title"]);
            $description = sanitize($_POST["description"]);
            $price = sanitize($_POST["price"]);

            if (empty($title) || empty($description) || empty($price)) {
                $err = translate("All fields required!");
            }

            //upload image
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
            }

            if (empty($err) && isset($_FILES['image'])) {
                if (move_uploaded_file($tmp_name, $upload_dir . $file_name)) {
                    $image = $file_name;
                }

                $stmt2 = "INSERT INTO products (title, description, price, image) VALUES (?,?,?,?)";

                $stmt2 = $db->prepare($stmt2);
                $stmt2->bind_param('ssds', $title, $description, $price, $image);
                $stmt2->execute();

                header("location: products.php");
                die();
            }
        }
    }
?>

<?php require_once("inc/header.php"); ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="<?= translate("Title"); ?>" value="<?= isset($_GET['id']) ? (isset($_POST["save"]) ? $_POST["title"] : $row["title"]) : (isset($_POST["save"]) ? $_POST["title"] : ""); ?>">
        <textarea cols="20" rows="5" name="description" placeholder="<?= translate("Description"); ?>"><?= isset($_GET['id']) ? (isset($_POST["save"]) ? $_POST["description"] : $row['description']) : (isset($_POST["save"]) ? $_POST["description"] : ""); ?></textarea>
        <input type="text" name="price" placeholder="<?= translate("Price"); ?>" value="<?= isset($_GET['id']) ? (isset($_POST["save"]) ? $_POST["price"] : $row['price']) : (isset($_POST["save"]) ? $_POST["price"] : ""); ?>">
        <input type="file" name="image" id="image" >

        <input type="submit" name="save" value="<?= translate("Save"); ?>">
    </form>

    <p><?= $err; ?></p>
    <a href="products.php"><?= translate("Cancel"); ?></a>

<?php require_once("inc/footer.php"); ?>