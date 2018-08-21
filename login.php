<?php
    require_once('common.php');

?>

<?php require_once("inc/header.php"); ?>

    <form action="" method="POST">
        <input type="text" name="email" placeholder="<?= translate("Email"); ?>" value="<?= isset($_POST["login"]) ? $_POST["email"] : ""; ?>">
        <input type="password" name="password" placeholder="<?= translate("Password"); ?>">

        <input type="submit" name="login" value="<?= translate("Login"); ?>">
        <p><?= $err; ?></p>
    </form>

<?php require_once("inc/footer.php"); ?>