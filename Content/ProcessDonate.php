<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Donation</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/Donate.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Display errors
        if (TRUE) {// toggle to false after debugging
            ini_set( 'display_errors', 'true');
            error_reporting(-1);
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

            // Get form data
            $_SESSION['intGolferID'] = $_POST['selGolfer'];
            $_SESSION['txtFirstName'] = $_POST['txtFirstName'];
            $_SESSION['txtLastName'] = $_POST['txtLastName'];
            $_SESSION['txtAddress'] = $_POST['txtAddress'];
            $_SESSION['txtCity'] = $_POST['txtCity'];
            $_SESSION['intStateID'] = $_POST['selState'];
            $_SESSION['txtZip'] = $_POST['txtZip'];
            $_SESSION['txtPhone'] = $_POST['txtPhone'];
            $_SESSION['txtEmail'] = $_POST['txtEmail'];
            $_SESSION['txtDonation'] = $_POST['txtDonation'];
            $_SESSION['intPaymentTypeID'] = $_POST['selPayment'];

            // Transfer session form data to local variables for insert
            $intGolferID = $_SESSION['intGolferID'];
            $FirstName = $_SESSION['txtFirstName'];
            $LastName = $_SESSION['txtLastName'];
            $Address = $_SESSION['txtAddress'];
            $City = $_SESSION['txtCity'];
            $State = $_SESSION['intStateID'];
            $Zip = $_SESSION['txtZip'];
            $Phone = $_SESSION['txtPhone'];
            $Email = $_SESSION['txtEmail'];
            $Donation = $_SESSION['txtDonation'];
            $Payment = $_SESSION['intPaymentTypeID'];

            // Create intSponsorID variable in the database
            $sql = "SET @intSponsorID = 0";
            $result = mysqli_query($conn, $sql);

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Insert into TSponsors
            $sql = "CALL uspAddSponsor('$FirstName', '$LastName', '$Address', '$City', $State, '$Zip', '$Phone', '$Email', @intSponsorID)";
            $result = mysqli_query($conn, $sql);

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Retrieve new sponsor ID
            $sql = "SELECT MAX(intSponsorID) as ID FROM TSponsors";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $intSponsorID = $row['ID'];
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Get eventgolfer ID
            $sql = "SELECT intEventGolferID FROM TEventGolfers WHERE intGolferID = $intGolferID";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $intEventGolferID = $row['intEventGolferID'];
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Insert into TEventGolferSponsors
            $sql = "CALL uspAddEventGolferSponsor($intEventGolferID, $intSponsorID, $Donation, $Payment)";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Clear result set
                while($conn->more_results()){
                    $conn->next_result();
                    if($res = $conn->store_result())
                    {
                        $res->free(); 
                    }
                }

                // Redirect to thank you page
                echo 
                '
                <div class="ContentMessage">
                    <h1>Thank You For Donating!</h1>
                    <a href="Homepage.php" class="GoBack">Return to Home</a>
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
                echo 
                '
                <div class="ContentMessage">
                    <h1>Donation Failed.</h1>
                    <a href="Donate.php" class="GoBack">Go Back</a>
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