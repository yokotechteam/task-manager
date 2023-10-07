<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager | Email Verify</title>
    <!-- BOOTSTRAP CSS LINK -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>

  <body class=" bg-dark-subtle ">

    <div class="d-flex justify-content-center mt-5">

      <!-- REGISTER START -->
      <form action="email-verify" method="post" class=" row container bg-body-secondary py-3 rounded-3 ">
        <h3 class="text-center ">EMAIL VERIFICATION</h3>

        <!-- USER EMAIL -->
        <div class="mb-3 col-12">
          <label for="opt" class="form-label">VERIFICATION CODE</label>
          <input type="text" name="opt" id="opt" class="form-control 
          <?= isset( $_COOKIE[ '_jwtTokenExpired' ] ) || isset( $_COOKIE[ 'optError' ] ) ? "border-danger" : ""; ?>
          ">
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ '_jwtTokenExpired' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ '_jwtTokenExpired' ]; ?>
            <?php setcookie ( "_jwtTokenExpired", '', time () - 3600 ); ?>
          </span>
          <span class="text-bg-warning 
          <?= isset( $_COOKIE[ 'optError' ] ) ? "d-flex" : "d-none"; ?>">
            <?= $_COOKIE[ 'optError' ]; ?>
            <?php setcookie ( "optError", '', time () - 3600 ); ?>
          </span>
        </div>

        <input type="hidden" name="_token" value="<?= $_SESSION[ '_token' ]; ?>">
        <span class="text-bg-warning 
          <?= isset( $_COOKIE[ '_csrf_invalid' ] ) ? "d-flex" : "d-none"; ?>">
          <?= $_COOKIE[ '_csrf_invalid' ]; ?>
          <?php setcookie ( "_csrf_invalid", '', time () - 3600 ); ?>
        </span>

        <!-- REGISTER OR LOGIN BUTTONS -->
        <div class="mb-3 col-12">
          <button class="btn btn-outline-dark col-12 mb-3">Register</button>
        </div>

      </form>
      <!-- REGISTER END -->

    </div>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
      </script>
  </body>

</html>