<?php
    require_once("common.php");

    if (isset($_GET['id'])) {
        array_push($_SESSION['cart'], $_GET['id']);

        header("location: /"); // redirect to remove the GET parameter
        die();
    }

    if (count($_SESSION['cart'])) {

        $params = [];
        foreach ($_SESSION['cart'] as $key => $value) {
            $params[] = &$_SESSION['cart'][$key];
        }

        $sql = "SELECT * FROM products WHERE id NOT IN(" . implode(', ', array_fill(0, count($_SESSION['cart']), '?')) . ");";
        $stmt = $db->prepare($sql);

        call_user_func_array(
            'mysqli_stmt_bind_param',
            array_merge(
                array($stmt, str_repeat('i', count($_SESSION['cart']))),
                $params
            )
        );

        $stmt->execute();
        $result = $stmt->get_result();

    } else {
        $sql = "SELECT * FROM products";
        $result = $db->query($sql);
    }

?>

<?php require_once('inc/header.php'); ?>

    <table>
        <?php foreach($result as $row): ?>
            <tr>
                <td><img src="images/<?= $row['image']; ?>"></td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <p><?= $row['title']; ?></p>
                    <p><?= $row['description']; ?></p>
                    <p>
                       <?= translate("$"); ?>
                       <?= $row['price']; ?>
                    </p>
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><a href="?id=<?= $row['id']; ?>"><?= translate("ADD"); ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="cart.php"><?= translate("Go to cart"); ?></a>

<?php require_once('inc/footer.php'); ?>