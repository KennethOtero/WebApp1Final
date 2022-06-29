<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Update Golfer</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/UpdateGolfer.css">
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
        // Start the session
        session_start();

        // Display errors
        if(FALSE) // toggle to false after debugging
        {
            ini_set( 'display_errors', 'true');
            error_reporting(-1);
        }

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

            // Get Golfer Information
            $GolferID = $_GET['ID'];
            $FirstName = $_POST['txtFirstName'];
            $LastName = $_POST['txtLastName'];
            $Address = $_POST['txtAddress'];
            $City = $_POST['txtCity'];
            $StateID = $_POST['cboStates'];
            $Zip = $_POST['txtZip'];
            $Phone = $_POST['txtPhoneNumber'];
            $Email = $_POST['txtEmail'];
            $ShirtSize = $_POST['cboShirtSize'];
            $Gender = $_POST['cboGenders'];

            if ($conn) {
                // Update query
                $query = "CALL uspUpdateGolfer($GolferID, '$FirstName', '$LastName', '$Address', '$City', $StateID, '$Zip', '$Phone', '$Email', $ShirtSize, $Gender)";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo 
                    '
                    <div class="ContentMessage">
                        <h1>Golfer Update Successful.</h1>
                        <a href="ShowGolfers.php" class="GoBack">View All Golfers</a>
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
                        <h1>Golfer Update Failed.</h1>
                        <a href="UpdateGolfer.php?ID='.$GolferID.'" class="GoBack">Go Back</a>
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

        // Close connection
        mysqli_close($conn);
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