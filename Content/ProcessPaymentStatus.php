<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment Status</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/PaymentStatus.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Display errors
        if(TRUE) // toggle to false after debugging
        {
            ini_set( 'display_errors', 'true');
            error_reporting(-1);
        }

        // Check login status
        if ($_SESSION['blnLoggedIn'] == FALSE) {
            // Go back to homepage
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

    <?php 
        try {
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

            if ($conn) {
                // Get intGolferID
                $intGolferID = $_GET['ID'];

                // Get intSponsorID
                $intSponsorID = $_SESSION['intSponsorID'];

                // Get updated payment status
                $intPaymentStatusID = $_POST['selPayment'];
                $sql = "CALL uspUpdatePaymentStatus($intSponsorID, $intPaymentStatusID)";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    // Display error
                    echo 
                    '
                    <div class="ContentMessage">
                        <h1>Payment Update Successful.</h1>
                        <a href="AdminHome.php" class="GoBack">Go Home</a>
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
                } else {
                    // Display error
                    echo 
                    '
                    <div class="ContentMessage">
                        <h1>Payment Update Failed.</h1>
                        <a href="AdminHome.php" class="GoBack">Go Home</a>
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