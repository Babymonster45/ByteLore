<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'views/pageBuilder.php';
include 'views/header.php';
?>

<!--====== CALL TO ACTION FOUR PART START ======-->
<section class="call-action-area call-action-four">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="call-action-content text-center">
               <h2 class="action-title">Welcome to ByteLore!</h2>
               <p class="text">
               Dive into your ultimate gaming community hub <br />
               discover new games and join the conversation.
               </p>
               <!-- call action form -->
            </div>
            <!-- call action content -->
         </div>
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== CALL TO ACTION FOUR PART ENDS ======-->

<!--====== BLOG PART START ======-->
<section class="blog-area pb-5">
   <div class="container">
      <div class="row justify-content-center">
         <?php include('recent_pages.php'); // Displays the top 6 most recently created games ?> 
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== BLOG PART ENDS ======-->

<main>
    <h2>Create a Page</h2>
    <?php
    // Check if the user is logged in
    if (isset($_SESSION["user_id"])) {
        echo '<div><a class="button" href="/create_page.php">Create Page</a></div>';
    } else {
        echo '<p>You must be logged in to create a page. Please <a href="login.php">log in</a>.</p>';
    }
    ?>
</main>

<?php
include 'views/footer.php';
?>
