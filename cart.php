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


    if (isset($_POST['checkout']) && count($_SESSION['cart'])) {

        $email = sanitize($_POST["email"]);
        $name = sanitize($_POST["name"]);
        $comments = sanitize($_POST["comments"]);


        if (empty($email) || empty($name)) {
            $err = translate("Name and email required!");

        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = translate("Invalid email format!");
            } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $err = translate("Only letters and white spaces allowed!");
            }
        }

        if (empty($err)) {

            //insert order details into database
            $stmt2 = "INSERT INTO orders(name, email, comment) VALUES (?,?,?)";
            $stmt2 = $db->prepare($stmt2);
            $stmt2->bind_param('sss', $name, $email, $comments);
            $stmt2->execute();

            //get last order id
            $order_id = $stmt2->insert_id;

            
            foreach ($_SESSION['cart'] as $key => $value) {
                $stmt3 = "INSERT INTO orders_products (order_id, product_id) VALUES (?, ?)";
                $stmt3 = $db->prepare($stmt3);
                $stmt3->bind_param('ii', $order_id, $value);
                $stmt3->execute();
            }

            //create email
            $subject = translate("Order confirmation | Shop vanilla");

            $header = "MIME-Version:1.0" . "\r\n";
            $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $header .= "From:" . EMAIL . "\r\n";

            $message = "
                    <h1>" . translate("Hello ") . $name . ",</h1>
                    <h5>" . translate("Thank you for buying from us.") . "</h5>
                    
                    <p>" . translate("Here are your order details:") . "</p>
                    
                    <table>
                        <tr>
                            <th>" . translate("NO.") . "</th>
                            <th></th>
                            <th>" . translate("PRODUCT DETAILS") . "</th>
                            <th></th>
                            <th>" . translate("PRICE") . "</th>
                        </tr>";

            $sum = $no = 0;
            foreach ($result as $row) {
                $sum += $row['price'];

                $message .= "<tr>
                                 <td><p>" . ++$no . "</p></td>
                                 <td><img src='" . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_SCHEME) . "://" . $_SERVER["HTTP_HOST"] . "/images/" . $row['image'] . "' width='100em'></td>
                                 <td>
                                     <p>" . $row['title'] . "</p>
                                     <p>" . $row['description'] . "</p>
                                 </td>
                                 <td></td>
                                 <td><p>" . translate("$") . $row['price'] . "</p></td>
                             </tr>";
            }

            $message .= "<tr>
                             <th>" . translate("TOTAL") . "</th>
                             <th></th>
                             <th></th>
                             <th></th>
                             <th>" . translate("$") . $sum . "</th>
                         </tr>
                     </table>";

            $message .= translate("OBSERVATIONS: " . $comments);

            //send mail
            mail($email, $subject, $message, $header);

            session_destroy();
            header("location: /");
            die();
        }
    }

?>

<?php require_once('inc/header.php'); ?>


    <a class="auth" href="login.php"><?= translate("Login"); ?></a>

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

    <form method="POST">
        <input type="text" name="name" placeholder="<?= translate("Name");?>" value="<?= isset($_POST["checkout"]) ? $_POST["name"] : ""; ?>">
        <input type="text" name="email" placeholder="<?= translate("Email");?>" value="<?= isset($_POST["checkout"]) ? $_POST["email"] : ""; ?>">
        <textarea cols="20" rows="5" name="comments" placeholder="<?= translate("Comments");?>"><?= isset($_POST["checkout"]) ? $_POST["comments"] : ""; ?></textarea>

        <input type="submit" name="checkout" value="<?= translate("Checkout");?>">
        <p><?= $err; ?></p>
    </form>

<?php require_once('inc/footer.php'); ?>

