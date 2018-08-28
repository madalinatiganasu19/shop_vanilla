<?php
    require_once("common.php");

    security_check();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = "SELECT p.* FROM products AS p
                 JOIN orders_products AS op ON op.product_id = p.id
                 WHERE op.order_id = ?;";

        $stmt = $db->prepare($stmt);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
?>

<?php require_once("inc/header.php"); ?>

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
            </tr>

        <?php endforeach; ?>


    </table>


<?php require_once("inc/footer.php"); ?>