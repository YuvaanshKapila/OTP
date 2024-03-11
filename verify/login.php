<?php
require "functions.php";

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = login($_POST);

    if (empty($errors)) {
        // Redirect to a dashboard or home page after successful login
        header("Location: profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php include('header.php')?>

    <div>
        <div>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
            }
            ?>
        </div>
        <form method="post">
            <input type="text" name="email" placeholder="Enter Email"><br>
            <input type="password" name="password" placeholder="Enter Password"><br>
            <br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
