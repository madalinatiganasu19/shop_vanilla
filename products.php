<?php
    require_once('common.php');

    $sql = "SELECT * FROM products";
    $result = $db->query($sql);

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        $stmt = "DELETE FROM products WHERE id = ?";

        $stmt = $db->prepare($stmt);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        header("location: products.php");
        die();
    }
?>

<?php require_once("inc/header.php"); ?>

    <div class="auth">
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
                    <p><?= wordwrap($row['description'],50,"<br>\n",TRUE); ?></p>
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