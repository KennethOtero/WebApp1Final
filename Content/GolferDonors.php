<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golfer Donors</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/GolferDonors.css">
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

    <div class="content">
        <?php 
            // Get golfer ID
            $intGolferID = $_GET['ID'];

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
                    // Get golfer name
                    $sql = "SELECT CONCAT(strFirstName, ' ', strLastName) as GolferName FROM TGolfers WHERE intGolferID = $intGolferID";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $GolferName = $row['GolferName'];
                    }

                    // Clear result set
                    while($conn->more_results()){
                        $conn->next_result();
                        if($res = $conn->store_result())
                        {
                            $res->free(); 
                        }
                    }

                    // Get list of donors
                    $sql = "CALL uspShowDonors($intGolferID)";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        echo '<h1>List of Donors For '. $GolferName .'</h1>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            $SponsorName = $row['SponsorName'];
                            $Donation = $row['decPledgePerHole'];
                            $PaymentStatus = $row['strPaymentStatus'];
                            $intSponsorID = $row['intSponsorID'];
                            $PaymentStatus = strtolower($PaymentStatus);
                            // Make list of donors
                            echo '<p>'. $SponsorName .' has donated '. $Donation .' that is currently <a href="PaymentStatus.php?ID='. $intGolferID .'&SID='. $intSponsorID .'">'. $PaymentStatus .'</a>.</p>';
                        }
                    }
                }

                // Close connection
                mysqli_close($conn);
            } catch (Exception $e) {
                echo $e;
            }
        ?>
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