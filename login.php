<?php
    require_once('common.php');

    if (isset($_POST['login'])) {

        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['password']);

        if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = translate("Invalid credentials");

        } elseif ($email == EMAIL && $password == ADMIN_PASSWORD) {
            header("location: products.php");
            die();

        } else {
            $err = translate("Invalid credentials");
        }
    }
?>

<?php require_once("inc/header.php"); ?>

    <form method="POST">
        <input type="text" name="email" placeholder="<?= translate("Email"); ?>" value="<?= isset($_POST["login"]) ? htmlspecialchars($_POST["email"]) : ""; ?>">
        <input type="password" name="password" placeholder="<?= translate("Password"); ?>" value="<?= isset($_POST["login"]) ? htmlspecialchars($_POST["password"]) : ""; ?>">

        <input type="submit" name="login" value="<?= translate("Login"); ?>">
        <p><?= $err; ?></p>
    </form>

    <a href="/"><?= translate("Go to index"); ?></a>

<?php require_once("inc/footer.php"); ?>