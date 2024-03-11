<?php

require "functions.php";
check_login();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
</head>
<body>

    <h1>Welcome to the Home Page</h1>

    <?php include('header.php'); ?>

    <div>
        <?php if (isset($_SESSION['USER']->verified) && $_SESSION['USER']->verified) : ?>
            <p>Congratulations! You are verified. Here's a cool game for you:</p>

            <?php
            // Guessing Game
            $number_to_guess = rand(1, 10);

            if (isset($_POST['guess'])) {
                $user_guess = intval($_POST['guess']);

                if ($user_guess == $number_to_guess) {
                    echo "<p>Congratulations! You guessed the correct number: $number_to_guess</p>";
                    $number_to_guess = rand(1, 10); // Reset the number to guess
                } else {
                    echo "<p>Wrong guess. Try again!</p>";
                }
            }
            ?>

            <p>Guess a number between 1 and 10:</p>
            <form method="post">
                <input type="number" name="guess" min="1" max="10" required>
                <input type="submit" value="Submit Guess">
            </form>

        <?php else : ?>
            <p>You need to be verified to access the cool game. Verify your email to unlock it!</p>
        <?php endif; ?>
    </div>

</body>
</html>
