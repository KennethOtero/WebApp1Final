<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/Login.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Display errors
        if (TRUE) { // toggle to false after debugging
            ini_set( 'display_errors', 'true');
            error_reporting(-1);
        }

        //$_SESSION['blnLoginFailed'] = TRUE;
        if (isset($_SESSION['blnLoginFailed'])) {
            //get retry flag
            $blnIsRetry = $_SESSION['blnLoginFailed'];
            //reset flag for next try
            $_SESSION['blnLoginFailed'] = FALSE;
            //if flag is set
            if ($blnIsRetry == TRUE) {
                //grab value
                $strLoginID = $_SESSION['LoginID'];
            } else {
                //set login text to blank
                $strLoginID = "";
            }       
        } else {
            //set email text to blank
            $strLoginID = "";
        }
    ?>

    <div class="Header">
        <div>
            <ul class="TopBar">
                <li>CINCINNATI STATE</li>
            </ul>
        </div>
        <div>
            <ul class="MenuBar">
                <li><a href="Homepage.php">Home</a></li>
                <li><a href="InsertGolfer.php">Register to Golf</a></li>
                <li><a href="ShowGolfers.php">All Golfers</a></li>
                <li><a href="Donate.php">Donate</a></li>
                <li><a href="GolferStats.php">Statistics</a></li>
                <?php 
                    // Check login status
                    require('IsLoggedIn.php');
                ?>
            </ul>
        </div>
    </div>

    <div class="LogIn">
        <h1>Sign In</h1>
        <form name="frmLogin" method="post" action="ProcessLogin.php">
            <span class="details">Login ID:</span>
            <input type="text" name="txtLogin" maxlength="100" value="<?php echo $strLoginID;?>" required>
            <span class="details">Password:</span>
            <input type="password" name="txtPassword" maxlength="100" required>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Login">
        </form>
    </div>

    <div class="Footer">
        <h3>GOLFATHON</h3>
        <ul>
            <li>Kenneth Otero</li>
            <li>Final Project</li>
            <li>Web App 1</li>
            <li>April 14, 2022</li>
        </ul>
    </div>
    
</body>
</html>