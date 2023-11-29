<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Email Sent</title>
    <link rel="stylesheet" href="verification.css">   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            width: 100%;
            background-color: #67b3b5;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            padding: 20px;
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: 36px;
        }

        .subheader {
            width: 100%;
            background-color: #fff;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h4 {
            margin: 0;
            font-size: 16px;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Verification Email Sent</h1>
        </header>
        <div class="subheader">
            <?php include('header.php'); ?>
        </div>
        <form>
            <h4>
                <p>A verification email has been sent to your provided email address.</p>
                <p>Please check your inbox (and spam/junk folder) for the verification email.</p>
                <p>Click the link in the email to verify your account.</p>
            </h4>
        </form>
    </div>
</body>
</html>

