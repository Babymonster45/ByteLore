<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start a session to manage user login state
session_start();

// Include your database connection script
include('/secure_config/config.php');

    if (isset($_COOKIE["remember_me_token"])) {
        // User is not logged in and a "Remember Me" cookie exists

        // Retrieve the token from the cookie
        $token = $_COOKIE["remember_me_token"];

        // Look up the token in the database
        $stmt = $conn->prepare("SELECT user_id, expires FROM remember_me_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($user_id, $expires);

        if ($stmt->fetch() && new DateTime() < new DateTime($expires)) {
            // Token is valid and has not expired
        
            // Log the user in
            $_SESSION["user_id"] = $user_id;
        
            // Close the previous statement
            $stmt->close();
        
            // Delete the used token from the database
            $stmt = $conn->prepare("DELETE FROM remember_me_tokens WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();  // Close this statement as well
        
            // Generate a new token and store it in the database and in a cookie
            $token = bin2hex(random_bytes(16)); // Generate a random token
            $expires = new DateTime('NOW');
            $expires->add(new DateInterval('P30D')); // Token expires after 30 days
        
            // Store the token in the database
            $stmt = $conn->prepare("INSERT INTO remember_me_tokens (user_id, token, expires) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $token, $expires->format('Y-m-d H:i:s'));
            $stmt->execute();
            $stmt->close();  // Close this statement as well
        } else {
            // Token is invalid or has expired
        
            // Delete the cookie
            setcookie("remember_me_token", "", time() - 3600, "/", "", true, true);
        }
        
    }

?>

