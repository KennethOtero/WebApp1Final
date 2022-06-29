<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Status</title>
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Create login session variables and set to false
        if (!isset($_SESSION['blnLoggedIn'])) {
            $_SESSION['blnLoggedIn'] = false;
        }

        if ($_SESSION['blnLoggedIn'] == TRUE) {
            // Ask to log out
            echo '<li><a href="AdminHome.php" id="LogOut">Admin Home</a></li>';
            echo '<li><a href="LogOut.php" id="LogOut">Log Out</a></li>';
        } else {
            // Make the user sign in
            echo '<li><a href="http://itd1.cincinnatistate.edu/WAPP1-OteroK/Contents/Week15/Login.php" id="Login">Sign In</a></li>';
        }
    ?>
</body>
</html>