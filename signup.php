<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

include './views/pageBuilder.php';
include './views/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: red;
            margin-top: 5px;
        } 
    </style>
    <script>
        // JavaScript to display error messages under the corresponding text boxes
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const usernameError = urlParams.get("username-error");
            const emailError = urlParams.get("email-error");
            const passwordError = urlParams.get("password-error");

            if (usernameError) {
                const usernameErrorDiv = document.querySelector(".username-error");
                usernameErrorDiv.innerHTML = usernameError;
            }

            if (emailError) {
                const emailErrorDiv = document.querySelector(".email-error");
                emailErrorDiv.innerHTML = emailError;
            }

            if (passwordError) {
                const passwordErrorDiv = document.querySelector(".password-error");
                passwordErrorDiv.innerHTML = passwordError;
            }
        });
    </script>
</head>
<!--====== SIGNIN ONE PART START ======-->
<section class="signin-area signin-one">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-5">
            <form action="process_signup.php" method="post">
               <div class="signin-form form-style-two rounded-buttons">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-input">
                           <label>Your account will be under this email</label>
                           <div class="input-items default">
                              <input type="text" name="email" placeholder="Email" required />
                              <i class="lni lni-envelope"></i>
                           </div>
                           <div class="email-error error-message"></div>
                        </div>
                        <!-- form input -->
                     </div>
                     <div class="col-md-12">
                        <div class="form-input">
                           <label>
                           Name will be used to personalize your experience
                           </label>
                           <div class="input-items default">
                              <input type="text" name="username" placeholder="Name" required />
                              <i class="lni lni-user"></i>
                           </div>
                           <div class="username-error error-message"></div>
                        </div>
                        <!-- form input -->
                     </div>
                     <div class="col-md-12">
                        <div class="form-input">
                           <label>Password for your account</label>
                           <div class="input-items default">
                              <input type="password" name="password" placeholder="Password" required />
                              <i class="lni lni-key"></i>
                           </div>
                           <div class="password-error error-message"></div>
                        </div>
                        <!-- form input -->
                     </div>
                     <div class="col-md-6">
                        <div class="form-input rounded-buttons">
                           <button
                              class="btn primary-btn rounded-full"
                              type="submit"
                              >
                           Sign Up
                           </button>
                        </div>
                        <!-- form input -->
                     </div>
                     <div class="col-md-6">
                        <div class="form-input rounded-buttons">
                           <button
                              href="signup.php"
                              class="btn primary-btn-outline rounded-full"
                              >
                           Sign In!
                           </button>
                        </div>
                        <!-- form input -->
                     </div>
                        <!-- form input -->
                     </div>
                  </div>
               </div>
               <!-- signin form -->
            </form>
         </div>
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== SIGNIN ONE PART ENDS ======-->
<?php
include 'views/footer.php';
?>
</html>
