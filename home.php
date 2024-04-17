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
            </div>
            <!-- call action content -->
         </div>
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== CALL TO ACTION FOUR PART ENDS ======-->

<!--====== ADD GAME PART START ======-->
<section class="call-action-area call-action-four" style="background-color: black; color: white;">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="call-action-content text-center">
               <br><h2 class="action-title">Anything Missing? Add A Game!</h2><br>
               <?php
               // Check if the user is logged in
               if (isset($_SESSION["user_id"])) {
                   echo '<div class="call-action-form"><div class="action-btn"><a class="primary-btn" href="/create_page.php">Add Game</a></div></div>';
               } else {
                   echo '<p>You must be logged in to add a game. Please <a href="login.php">log in</a>.</p>';
               }
               ?><br><br>
            </div>
            <!-- call action content -->
         </div>
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== ADD GAME PART ENDS ======-->


<!--====== BLOG PART START ======-->
<section class="blog-area pb-5">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="call-action-content text-center">
               <br><br><h2 class="action-title">Recently Added Games</h2>
            </div>
         </div>
         <?php include('recent_pages.php'); // Displays the top 6 most recently created games ?> 
      </div>
      <!-- row -->
   </div>
   <!-- container -->
</section>
<!--====== BLOG PART ENDS ======-->

<?php
include 'views/footer.php';
?>

