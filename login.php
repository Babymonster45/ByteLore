<?php
include 'views/pageBuilder.php';
include 'views/header.php';
?>

<!-- Start Account Sign In Area -->
<div class="account-login section">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-xl-6 col-lg-8">
            <form class="card login-form inner-content" method="post">
               <div class="card-body">
                  <div class="title">
                     <h3>Sign In Now</h3>
                     <p>Sign in by entering the information below.</p>
                  </div>
                  <div class="input-head">
                     <div class="form-group input-group">
                        <label> <i class="lni lni-user"></i> </label>
                        <input class="form-control" type="text" id="reg-email" placeholder="Enter your user name" required />
                     </div>
                     <div class="form-group input-group">
                        <label> <i class="lni lni-lock-alt"></i> </label>
                        <input class="form-control" type="password" id="reg-pass" placeholder="Enter your password"
                           required />
                     </div>
                  </div>
                  <div class="d-flex flex-wrap justify-content-between bottom-content">
                     <div class="form-check">
                        <input type="checkbox" class="form-check-input width-auto" id="exampleCheck1" />
                        <label class="form-check-label">Remember me</label>
                     </div>
                     <a class="lost-pass" href="reset-password.html">
                     Forgot password?
                     </a>
                  </div>
                  <div class="light-rounded-buttons">
                     <button class="btn primary-btn" type="submit">Sign In</button>
                     <button class="btn primary-btn-outline">
                     Create Account
                     </button>
                  </div>

               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End Account Sign In Area -->

<?php
include 'views/footer.php';
?>