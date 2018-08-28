<?php
    require_once('common.php');

    security_check();

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        $statement = "SELECT image FROM products WHERE id = ?";

        $statement = $db->prepare($statement);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        $stmt = "DELETE FROM products WHERE id = ?";

        $stmt = $db->prepare($stmt);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        unlink("images/" . $row['image']);

        header("location: products.php");
        die();

    }

    $sql = "SELECT * FROM products";
    $result = $db->query($sql);

?>

<?php require_once("inc/header.php"); ?>

    <div class="auth">
        <a href="orders.php"><?= translate("Orders"); ?></a>
        <a href="product.php"><?= translate("Add Product"); ?></a>
        <a href="logout.php"><?= translate("Logout"); ?></a>
    </div>

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
                <td>
                    <p><a href="product.php?id=<?= $row['id']; ?>"><?= translate("UPDATE"); ?></a></p>
                    <p><a href="?id=<?= $row['id']; ?>"><?= translate("DELETE"); ?></a></p>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php require_once("inc/footer.php"); ?>