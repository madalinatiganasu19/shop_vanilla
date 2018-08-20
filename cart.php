<?php
    require_once('common.php');

    if (isset($_GET['id'])) {
        foreach ($_SESSION['cart'] as $key => $value) {

            if ($value == $_GET['id']) {
                unset($_SESSION['cart'][$key]);
            }

            header("location: cart.php");
            die();
        }
    }

    if (count($_SESSION['cart'])) {
        $cart = array();

        foreach ($_SESSION['cart'] as $key => $value) {
            $cart[] = $value;
        }

        $params = [];
        foreach ($cart as $key => $value) {
            $params[] = &$cart[$key];
        }

        $sql = "SELECT * FROM products WHERE id IN(" . implode(', ', array_fill(0, count($cart), '?')) . ");";
        $stmt = $db->prepare($sql);

        call_user_func_array(
            'mysqli_stmt_bind_param',
            array_merge(
                array($stmt, str_repeat('i', count($cart))),
                $params
            )
        );

        $stmt->execute();
        $result = $stmt->get_result();

    } else {

        $result = array();
    }

?>

<?php require_once ('inc/header.php'); ?>

    <table>
        <?php foreach($result as $row) : ?>

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
                <td><a href="?id=<?= $row['id']; ?>"><?= translate("REMOVE"); ?></a></td>
            </tr>

        <?php endforeach; ?>
    </table>

    <a href="/"><?= translate("Go to index"); ?></a>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="<?= translate("Name");?>">
        <input type="text" name="email" placeholder="<?= translate("Email");?>">
        <textarea cols="20" rows="5" name="comments" placeholder="<?= translate("Comments");?>"></textarea>

        <input type="submit" value="<?= translate("Checkout");?>">
    </form>

<?php require_once ('inc/footer.php'); ?>

