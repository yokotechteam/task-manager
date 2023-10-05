<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager | Login</title>
    <!-- BOOTSTRAP CSS LINK -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>

  <body class=" bg-dark-subtle ">

    <div class="d-flex justify-content-center mt-5">

      <!-- REGISTER START -->
      <form action="register" method="post" class=" row container bg-body-secondary py-3 rounded-3 ">
        <h3 class="text-center ">USER LOGIN</h3>

        <!-- USER EMAIL -->
        <div class="mb-3 col-12">
          <label for="user_email" class="form-label">EMAIL</label>
          <input type="text" name="user_email" id="user_email" class="form-control">
        </div>

        <!-- USER PASSWORD -->
        <div class="mb-3 col-12">
          <label for="user_password" class="form-label">PASSWORD</label>
          <input type="text" name="user_password" id="user_password" class="form-control">
        </div>

        <!-- REGISTER OR LOGIN BUTTONS -->
        <div class="mb-3 col-12">
          <button class="btn btn-outline-dark col-12 mb-3">Login</button>
          <span class=" d-flex justify-content-center ">Don't have an account? <a href="register"
              class="btn btn-sm btn-outline-secondary ms-2 "> Register </a> </span>
        </div>

      </form>
      <!-- REGISTER END -->

    </div>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
      </script>
  </body>

</html>