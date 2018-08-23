<?php
    require_once('common.php');

    $sql = "SELECT * FROM products";
    $result = $db->query($sql);


?>

<?php require_once("inc/header.php"); ?>

    

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