<?php

session_start();

function signup($data)
{
    // Validate 
    if (!preg_match('/^[a-zA-Z]+$/', $data['username']) ||
        !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
        strlen(trim($data['password'])) < 4 ||
        database_run("SELECT 1 FROM user WHERE email = :email LIMIT 1", ['email' => $data['email']]))
    {
        return ["Invalid input or email already exists"];
    }

    // Save
    $arr = [
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => hash('sha256', $data['password']),
        'date' => date("Y-m-d H:i:s")
    ];

    database_run("INSERT INTO user (username, email, password, date) VALUES (:username, :email, :password, :date)", $arr);

    // Redirect to login.php after successful signup
    header("Location: login.php");
    exit;
}

function login($data)
{
    // Validate
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
        strlen(trim($data['password'])) < 4)
    {
        return ["Invalid email or password"];
    }

    // Check
    $arr = ['email' => $data['email']];
    $password = hash('sha256', $data['password']);

    $row = database_run("SELECT * FROM user WHERE email = :email LIMIT 1", $arr);

    if (is_array($row)) {
        $row = $row[0];

        if ($password === $row->password) {
            $_SESSION['USER'] = $row;
            $_SESSION['LOGGED_IN'] = true;
        } else {
            return ["Wrong email or password"];
        }
    } else {
        return ["Wrong email or password"];
    }

    return [];
}

function database_run($query, $vars = [])
{
    try {
        $con = new PDO("mysql:host=localhost;dbname=verify_database", 'root', '');

        $stm = $con->prepare($query);
        $stm->execute($vars);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

function check_login($redirect = true)
{
    if (!isset($_SESSION['USER']) || !isset($_SESSION['LOGGED_IN'])) {
        if ($redirect) {
            header("Location: login.php");
            die;
        }
        return false;
    }
    return true;
}

function check_verified()
{
    if (isset($_SESSION['USER']->id)) {
        $id = $_SESSION['USER']->id;
        $row = database_run("SELECT * FROM user WHERE id = '$id' LIMIT 1");

        if (is_array($row) && isset($row[0]->email_verified)) {
            $row = $row[0];
            return $row->email == $row->email_verified;
        }
    }

    return false;
}
