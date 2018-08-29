<?php
    require_once("common.php");

    security_check();

    if (isset($_GET['id'])) {
        //update existing product
        $id = $_GET['id'];

        $stmt = "SELECT * FROM products WHERE id = ?";

        $stmt = $db->prepare($stmt);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (isset($_POST['save'])) {

            //validate data
            $title = sanitize($_POST["title"]);
            $description = sanitize($_POST["description"]);
            $price = sanitize($_POST["price"]);

            if (empty($title) || empty($description) || empty($price)) {
                $err = "All fileds required";
            }

            if (empty($err)) {
                //validate & upload image
                $upload_dir = "images/";

                $file_name = $_FILES["image"]["name"];
                $tmp_name = $_FILES["image"]["tmp_name"];
                $file = $upload_dir . basename($_FILES["image"]["name"]);
                $image_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                if (!empty($file_name)) {
                    if ($_FILES["image"]["type"] != "image/jpg" && $_FILES["image"]["type"] != "image/jpeg" && $_FILES["image"]["type"] != "image/png") {
                        $err = translate("Only .jpg, .jpeg, .png files allowed!");
                    } elseif (file_exists($file)) {
                        $file_name = uniqid();
                    }
                }

                if (empty($file_name) && !empty($row['image'])) {
                    $image = $row["image"];
                }

                if (empty($err)) {
                    if (move_uploaded_file($tmp_name, $upload_dir . $file_name)) {
                        $image = $file_name;
                    }

                    $stmt2 = "UPDATE products SET title = ?, description = ?, price = ?, image = ? WHERE id = ?";

                    $stmt2 = $db->prepare($stmt2);
                    $stmt2->bind_param('ssdsi', $title, $description, $price, $image, $id);
                    $stmt2->execute();

                    header("location: products.php");
                    die();
                }
            }
        }
    } else {
        //add new product
        if (isset($_POST['save'])) {

            //validate data
            $title = sanitize($_POST["title"]);
            $description = sanitize($_POST["description"]);
            $price = sanitize($_POST["price"]);

            if (empty($title) || empty($description) || empty($price)) {
                $err = "All fileds required";
            }

            if (empty($err)) {
                //validate & upload image
                $upload_dir = "images/";

                $file_name = $_FILES["image"]["name"];
                $tmp_name = $_FILES["image"]["tmp_name"];
                $file = $upload_dir . basename($_FILES["image"]["name"]);
                $image_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                if (!empty($file_name)) {
                    if ($_FILES["image"]["type"] != "image/jpg" && $_FILES["image"]["type"] != "image/jpeg" && $_FILES["image"]["type"] != "image/png") {
                        $err = translate("Only .jpg, .jpeg, .png files allowed!");
                    } elseif (file_exists($file)) {
                        $file_name = uniqid();
                    }
                }

                if (empty($file_name)) {
                    $err = translate("All fields required!");
                }

                if (empty($err)) {
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
        } else {
            $price = "";
            $description = "";
            $title = "";
        }
    }

    if (isset($_GET['id'])) {
        if (isset($_POST['save'])) {
            $price = $_POST["price"];
            $description = $_POST["description"];
            $title = $_POST["title"];
            $image = translate("Image: ") . $_FILES["image"]["name"];
        } else {
            $price = $row['price'];
            $description = $row['description'];
            $title = $row['title'];
            $image = $row['image'];
        }
    } else {
        if (isset($_POST['save'])) {
            $price = $_POST["price"];
            $description = $_POST["description"];
            $title = $_POST["title"];
            $image = translate("Image: ") . $_FILES["image"]["name"];
        } else {
            $price = "";
            $description = "";
            $title = "";
            $image = "";
        }
    }

?>

<?php require_once("inc/header.php"); ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="<?= translate("Title"); ?>" value="<?= $title; ?>">
        <textarea cols="20" rows="5" name="description" placeholder="<?= translate("Description"); ?>"><?= $description; ?></textarea>
        <input type="text" name="price" placeholder="<?= translate("Price"); ?>" value="<?= $price ?>">

        <label for="image">
            <?= $image; ?>
        </label>

        <input type="file" name="image">

        <input type="submit" name="save" value="<?= translate("Save"); ?>">
    </form>

    <p><?= $err; ?></p>
    <a href="products.php"><?= translate("Cancel"); ?></a>

<?php require_once("inc/footer.php"); ?>