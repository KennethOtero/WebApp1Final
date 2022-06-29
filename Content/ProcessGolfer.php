<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Add Golfer</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/InsertGolfer.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
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

    <?php 
        try {
            // Display errors
            if(TRUE) // toggle to false after debugging
            {
                ini_set( 'display_errors', 'true');
                error_reporting(-1);
            }

            // Populate session variables
            $_SESSION["txtFirstName"] = $_POST["txtFirstName"];
            $_SESSION["txtLastName"] = $_POST["txtLastName"];
            $_SESSION["txtAddress"] = $_POST["txtAddress"];
            $_SESSION["txtCity"] = $_POST["txtCity"];
            $_SESSION["cboStates"] = $_POST["cboStates"];
            $_SESSION["txtZip"] = $_POST["txtZip"];
            $_SESSION["txtPhoneNumber"] = $_POST["txtPhoneNumber"];
            $_SESSION["txtEmail"] = $_POST["txtEmail"];
            $_SESSION["cboShirtSize"] = $_POST["cboShirtSize"];
            $_SESSION["cboGenders"] = $_POST["cboGenders"];

            // Insert variables
            $FirstName = $_SESSION["txtFirstName"];
            $LastName = $_SESSION["txtLastName"];
            $Address = $_SESSION["txtAddress"];
            $City = $_SESSION["txtCity"];
            $StateID = $_SESSION["cboStates"];
            $Zip = $_SESSION["txtZip"];
            $Phone = $_SESSION["txtPhoneNumber"];
            $Email = $_SESSION["txtEmail"];
            $ShirtSizeID = $_SESSION["cboShirtSize"];
            $GenderID = $_SESSION["cboGenders"];

            //Connect to MySQL
            $servername = "itd2.cincinnatistate.edu";
            $username = "kjotero2";
            $password = "0646911";
            $dbname = "02WAPP1400OteroK";

            //Create connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            //Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Proceed if connection established 
            if ($conn) {
                // Insert form data into TGolfers
                $sql = "CALL uspAddGolfer('$FirstName', '$LastName', '$Address', '$City', $StateID, '$Zip', '$Phone', '$Email', $ShirtSizeID, $GenderID)";
                $result = $conn->query($sql);

                if ($result) {
                    // Head back to the homepage
                    header('Location: homepage.php');
                } else {
                    // Display error
                    echo 
                    '
                    <div class="ContentMessage">
                        <h1>Golfer Insert Failed.</h1>
                        <a href="InsertGolfer.php" class="GoBack">Correct Errors</a>
                    </div>
                    <style>
                        .ContentMessage, .GoBack {
                            font-family: Arial, Helvetica, sans-serif;
                            color: #424242;
                            text-align: center;
                            margin-bottom: 575px;
                        }
                        
                        .ContentMessage a, .GoBack {
                            color: #418534;
                            text-decoration: none;
                        }
                    </style>
                    ';
                }
            }

            // Close connection
            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e;
        }
    ?>

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