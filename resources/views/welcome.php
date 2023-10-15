<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
  </head>

  <body style="background-color: #555;">

    <p>
      <?php
      if ( ! empty( $_SESSION[ 'email_not_verified' ] ) )
      {
        echo $_SESSION[ 'email_not_verified' ];
        session_destroy ();
      }
      ?>
    </p>
    <h1>WELCOME TO MY APP</h1>

  </body>

</html>