<?php
    require_once("common.php");

    security_check();

    $sql = "SELECT o.id, o.name, o.email, ROUND(SUM(p.price),2) AS total
            FROM orders AS o
            JOIN orders_products AS op ON o.id = op.order_id
            JOIN products AS p ON op.product_id = p.id
            GROUP BY o.id;";

    $result = $db->query($sql);
?>

<?php require_once("inc/header.php"); ?>

    <table>
        <tr>
            <th><?= translate("NAME"); ?></th>
            <td>&nbsp;</td>
            <th><?= translate("EMAIL"); ?></th>
            <td>&nbsp;</td>
            <th><?= translate("TOTAL"); ?></th>
        </tr>
        <?php foreach($result as $row): ?>
            <tr>
                <td><?= $row['name']; ?></td>
                <td>&nbsp;</td>
                <td><?= $row['email']; ?></td>
                <td>&nbsp;</td>
                <td><?= $row['total']; ?></td>
                <td>&nbsp;</td>
                <td><a href="order.php?id=<?=$row['id'];?>"><?= translate("VIEW ORDER"); ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php require_once("inc/footer.php"); ?>
