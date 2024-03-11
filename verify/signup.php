<?php  

require "functions.php";

$validationErrors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $validationErrors = signup($_POST);


    if (empty($validationErrors)) {
        header("Location: login.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Registration</title>
</head>
<body>
    <h1>User Registration</h1>

    <?php include('header.php')?>

    <div>
        <div>
            <?php if (!empty($validationErrors)):?>
                <?php foreach ($validationErrors as $error):?>
                    <?= $error?> <br>    
                <?php endforeach;?>
            <?php endif;?>

        </div>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username"><br>
            <input type="text" name="email" placeholder="Enter Email"><br>
            <input type="password" name="password" placeholder="Enter Password"><br>
            <br>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
