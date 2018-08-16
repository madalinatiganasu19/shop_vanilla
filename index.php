<?php

    require_once("common.php");

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_GET['add'])) {
        array_push($_SESSION['cart'], $_GET['add']);

        header("location: /"); // redirect to remove the GET parameter
        die();
    }

    if (count($_SESSION['cart'])) {
        $cart = array();

        foreach ($_SESSION['cart'] as $key => $value) {
            $cart[] = $value;
        }

        $sql = "SELECT * FROM products WHERE id NOT IN(" . implode(', ', $cart) . ");";
    } else {
        $sql = "SELECT * FROM products;";
    }

    $rows = $db->query($sql);

?>

<?php require_once('inc/header.php'); ?>

    <table>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td>
                    <img src="images/<?= $row['image']; ?>">
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <p><?= $row['title']; ?></p>
                    <p><?= $row['description']; ?></p>
                    <p>$<?= $row['price']; ?></p>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <a href="?add=<?= $row['id']; ?>"><?= translate("ADD"); ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="cart.php"><?= translate("Go to cart"); ?></a>

<?php require_once ('inc/footer.php'); ?>