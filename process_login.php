<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]);

    // Initialize separate error arrays for Username, Email, and Password
    $errorMessages = array();

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the database to find the user
    $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password_hash);

    // Check if a user was found
    if ($stmt->fetch()) {
        // Verify the user's password
        if (password_verify($password, $db_password_hash)) {
            // Password is correct
            $_SESSION["user_id"] = $user_id; // Set a session variable to indicate the user is logged in

            // If "Remember Me" is checked, create a cookie to remember the user's login
            if ($remember_me) {
                $cookie_name = "remember_me_cookie";
                $cookie_value = base64_encode($username . "|" . $db_password_hash);
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 30 days
            }

            header("Location: /"); // Redirect to the homepage or another page
            exit();
        } else {
            // Password is incorrect
            $errorMessages[0] = "Username and/or password is incorrect.";
            header("Location: login.php?error=1"); // Redirect back to the login page with an error message
            exit();
        }
    } else {
        // User not found
        $errorMessages[0] = "Username and/or password is incorrect.";
        header("Location: login.php?error=2"); // Redirect back to the login page with an error message
        exit();
    }

    // If there are errors, redirect back to signup.php with the error messages
    if (!empty($errorMessages)) {
        $errorMessagesString = http_build_query(array("error" => implode("<br>", $errorMessages)));
        header("Location: login.php?" . $errorMessagesString);
        exit();
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}else {
    // Check if remember me cookie exists and log in user
    if (isset($_COOKIE["remember_me_cookie"])) {
        list($stored_username, $stored_password_hash) = explode("|", base64_decode($_COOKIE["remember_me_cookie"]));
        
        include('/secure_config/config.php');
        
        $query = "SELECT id, username FROM users WHERE username = ? AND password_hash = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $stored_username, $stored_password_hash);
        $stmt->execute();
        $stmt->bind_result($user_id, $db_username);
        
        if ($stmt->fetch()) {
            $_SESSION["user_id"] = $user_id;
            header("Location: /");
            exit();
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
