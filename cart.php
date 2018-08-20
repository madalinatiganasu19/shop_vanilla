<?php
    require_once('common.php');

    if (isset($_GET['id'])) {
        $key = array_search($_GET['id'], $_SESSION['cart']);
        unset($_SESSION['cart'][$key]);

        header("location: cart.php");
        die();
    }

    if (count($_SESSION['cart'])) {

        $params = [];
        foreach ($_SESSION['cart'] as $key => $value) {
            $params[] = &$_SESSION['cart'][$key];
        }

        $sql = "SELECT * FROM products WHERE id IN(" . implode(', ', array_fill(0, count($_SESSION['cart']), '?')) . ");";
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

        $result = array();
    }

/*------------------email--------------------*/
    $err = "";
    if (isset($_POST['checkout'])) {

        $email = htmlspecialchars($_POST["email"]);
        $name = htmlspecialchars($_POST["name"]);

        $subject = translate("Order confirmation | Shop vanilla");

        $message = "
                <h1>" . translate("Hello") . "," . $name . "</h1>
                <h5>" . traslate("Thank you for buying from us.") . "</h5>
                
                <p>" . translate("Here are your order details:") . "</p>
                
                <table>
                    <tr>
                        <th>" . translate("NO.") . "</th>
                        <th>" . translate("PRODUCT NAME") . "</th>
                        <th>" . translate("PRICE") . "</th>
                    </tr>";

        $sum = 0;
        foreach ($result as $row):
            $sum += $row['price'];

            $message .= "<tr>
                             <td><p>" . $row['id'] . "</p></td>
                             <td><p>" . $row['title'] . "</p></td>
                             <td><p>" . translate("$") . $row['price'] . "</p></td>
                         </tr>";

        endforeach;

        $message .= "<tr>
                         <th>" . translate("Total") . "</th>
                         <th></th>
                         <th>" . translate("$") . $sum . "</th>
                     </tr>
                   </table>";

        $message = wordwrap($message, 72);

        $header = "MIME-Version 1.0\r\n";
        $header .= "Content-type: text/plain; charset:iso-8859-1\r\n";
        $header .= "From: " . EMAIL . "\r\n";
        $header .= "Date: " . date("r (T)") . "\r\n";
        $header .= "X-Priority: 1\r\n"; //into inbox

        if (empty($email) || empty($name)) {
            $err = "All fields required!";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = "Invalid email format!";
            }
            elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $err = "Only letters and white spaces allowed!";
            } else {
                //send mail
                mail($email, $subject, $message, $header);
                $success = "Order sent!";
            }
        }
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

        <input type="submit" name="checkout" value="<?= translate("Checkout");?>">
        <p><?= translate("$err"); ?></p>
    </form>

<?php require_once ('inc/footer.php'); ?>

