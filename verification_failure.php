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

include 'views/pageBuilder.php';
include 'views/header.php';
?>

<head>
    <title>Verification Failed</title>
</head>

<!--====== CALL TO ACTION FOUR PART START ======-->
<section class="call-action-area call-action-four">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="call-action-content text-center">
                    <h2 class="action-title">Verification Failed</h2>
                    <p class="text">
                        We're sorry, but the verification process failed.<br />
                        This could be due to an invalid or expired verification link.
                    </p>
                </div>
                <!-- call action content -->
            </div>
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</section>
<!--====== CALL TO ACTION FOUR PART ENDS ======-->

<?php
include 'views/footer.php';
?>
