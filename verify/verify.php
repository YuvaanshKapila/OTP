<?php

require "mail.php";
require "functions.php";
check_login();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verified()) {

    // send email
    $vars['code'] = rand(10000, 99999);

    // save to database
    $vars['expires'] = (time() + (60 * 10));
    $vars['email'] = $_SESSION['USER']->email;

    $query = "INSERT INTO verify (code,expires,email) VALUES (:code,:expires,:email)";
    database_run($query, $vars);

    $message = "Your code is " . $vars['code'];
    $subject = "Email verification";
    $recipient = $vars['email'];
    send_mail($recipient, $subject, $message);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (!check_verified()) {

        $query = "SELECT * FROM verify WHERE code = :code AND email = :email";
        $vars = array();
        $vars['email'] = $_SESSION['USER']->email;
        $vars['code'] = $_POST['code'];

        $row = database_run($query, $vars);

        if (is_array($row) && count($row) > 0) {
            $row = $row[0];
            $time = time();

            if (isset($row->expires) && $row->expires > $time) {
                // Mark the user as verified
                $_SESSION['USER']->verified = true;
                // Redirect to the home page
                header("Location: index.php");
                exit();
            } else {
                echo "Code expired";
            }
        } else {
            echo "Wrong code";
        }
    } else {
        echo "You're already verified";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify</title>
</head>
<body>

    <h1>Verify</h1>

    <?php include('header.php'); ?>

    <br><br>
    <div>
        <br>An email was sent to your address. Paste the code from the email here<br>
        <div>
            <?php if (count($errors) > 0) : ?>
                <?php foreach ($errors as $error) : ?>
                    <?= $error ?> <br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div><br>
        <form method="post">
            <input type="text" name="code" placeholder="Enter your Code"><br>
            <br>
            <input type="submit" value="Verify">
        </form>
    </div>

</body>
</html>
