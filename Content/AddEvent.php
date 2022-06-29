<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add An Event</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/AddEvent.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Check login status
        if ($_SESSION['blnLoggedIn'] == FALSE) {
            header('Location: Homepage.php');
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

    <div class="AddEvent">
        <h1>Create An Event</h1>
        <form name="frmEvent" action="ProcessAddEvent.php" method="post" onsubmit="return validateForm()">
            <span class="details">Enter a Year</span>
            <input type="text" name="txtYear" id="txtYear" maxlength="4" value="<?php echo $_SESSION['txtYear'];?>" placeholder="Enter a year">
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Add Event">
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

    <script>
        function validateForm() {
            // Create result
            var blnResult = true;

            // Get form input
            var Year = document.getElementById('txtYear').value.trim();

            // Validate year
            if (Year == "" || !isNumeric(Year)) {
                // Display error
                alert("Enter a year.");
                document.getElementById("txtYear").focus();
                blnResult = false;
                return blnResult;
            }

            // Return result
            return blnResult;
        }

        // Validate a number
        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
    </script>
</body>
</html>