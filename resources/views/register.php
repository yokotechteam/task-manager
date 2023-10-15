<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager | Register</title>
    <!-- BOOTSTRAP CSS LINK -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>

  <body class=" bg-dark-subtle ">

    <div class="d-flex justify-content-center mt-5">

      <!-- REGISTER START -->
      <form action="register" method="post" class=" row container bg-body-secondary py-3 rounded-3 ">
        <h3 class="text-center ">USER REGISTRATION</h3>
        <!-- USER NAME -->
        <div class="mb-3 col-12">
          <label for="name" class="form-label">NAME</label>
          <input type="text" name="name" value="Ab123456" id="name" class="form-control 
          <?= isset( $_COOKIE[ 'form_empty' ] ) || isset( $_COOKIE[ 'name_error' ] ) ? "border-danger" : ""; ?>">
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'form_empty' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'form_empty' ]; ?>
            <?php setcookie ( "form_empty", '', time () - 3600 ); ?>
          </span>
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'name_error' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'name_error' ]; ?>
            <?php setcookie ( "name_error", '', time () - 3600 ); ?>
          </span>
        </div>

        <!-- USER EMAIL -->
        <div class="mb-3 col-12">
          <label for="email" class="form-label">EMAIL</label>
          <input type="email" name="email" value="www.waihlanphyo528@gmail.com" id="email" class="form-control
          <?= isset( $_COOKIE[ 'email_error' ] ) || isset( $_COOKIE[ 'email_taken' ] ) || isset( $_COOKIE[ 'mail_send_error' ] ) ? "border-danger" : ""; ?>
          ">
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'email_error' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'email_error' ]; ?>
            <?php setcookie ( "email_error", '', time () - 3600 ); ?>
          </span>
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'email_taken' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'email_taken' ]; ?>
            <?php setcookie ( "email_taken", '', time () - 3600 ); ?>
          </span>
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'mail_send_error' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'mail_send_error' ]; ?>
            <?php setcookie ( "mail_send_error", '', time () - 3600 ); ?>
          </span>
        </div>

        <!-- USER PASSWORD -->
        <div class="mb-3 col-12">
          <label for="password" class="form-label">PASSWORD</label>
          <input type="password" name="password" value="YoKo@@1423" id="password" class="form-control
          <?= isset( $_COOKIE[ 'password_error' ] ) ? "border-danger" : ""; ?>
          ">
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'password_error' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'password_error' ]; ?>
            <?php setcookie ( "password_error", '', time () - 3600 ); ?>
          </span>
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-3 col-12">
          <label for="confirm_password" class="form-label">CONFIRM PASSWORD</label>
          <input type="password" name="confirm_password" value="YoKo@@1423" id="confirm_password" class="form-control
           <?= isset( $_COOKIE[ 'confirm_password_error' ] ) ? "border-danger" : ""; ?>
          ">
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'confirm_password_error' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'confirm_password_error' ]; ?>
            <?php setcookie ( "confirm_password_error", '', time () - 3600 ); ?>
          </span>
        </div>
        <span class="text-bg-warning 
          <?= isset( $_COOKIE[ '_csrf_invalid' ] ) ? "d-flex" : "d-none"; ?>">
          <?= $_COOKIE[ '_csrf_invalid' ]; ?>
          <?php setcookie ( "_csrf_invalid", '', time () - 3600 ); ?>
        </span>
        <input type="hidden" name="_token" value="<?php echo $_SESSION[ '_token' ]; ?>">
        <!-- REGISTER OR LOGIN BUTTONS -->
        <div class="mb-3 col-12">
          <button class="btn btn-outline-dark col-12 mb-3">Register</button>
          <span class=" d-flex justify-content-center ">Already have an account? <a href="login"
              class="btn btn-sm btn-outline-secondary ms-2 "> Login </a> </span>
        </div>

      </form>
      <!-- REGISTER END -->

    </div>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
      </script>
  </body>

</html>