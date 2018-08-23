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

            if (empty($err)) {

                $stmt2 = "INSERT INTO products (title, description, price) VALUES (?,?,?)";

                $stmt2 = $db->prepare($stmt2);
                $stmt2->bind_param('ssd', $title, $description, $price);
                $stmt2->execute();

                header("location: products.php");
                die();
            }
        }
    }
?>


<?php require_once("inc/header.php"); ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="<?= translate("Title"); ?>" value="<?= isset($_GET['id']) ? isset($_POST["save"]) ? $_POST["title"] : $row['title'] : isset($_POST["save"]) ? $_POST["title"] : ""; ?>">
        <textarea cols="20" rows="5" name="description" placeholder="<?= translate("Description"); ?>"><?= isset($_GET['id']) ? isset($_POST["save"]) ? $_POST["description"] : $row['description'] : isset($_POST["save"]) ? $_POST["description"] : ""; ?></textarea>
        <input type="text" name="price" placeholder="<?= translate("Price"); ?>" value="<?= isset($_GET['id']) ? isset($_POST["save"]) ? $_POST["price"] : $row['price'] : isset($_POST["save"]) ? $_POST["price"] : ""; ?>">
        <input type="file" name="image" id="image">

        <input type="submit" name="save" value="<?= translate("Save"); ?>">
    </form>

    <p><?= $err; ?></p>
    <a href="products.php"><?= translate("Cancel"); ?></a>

<?php require_once("inc/footer.php"); ?>