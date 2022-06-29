<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golfathon Statistics</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/GolferStats.css">
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
            // Get current event
            $sql = "SELECT MAX(intEventYear) as NewYear FROM TEvents";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $EventYear = $row['NewYear'];
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Get total pledges
            $sql = "CALL uspTotalPledges($EventYear)";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $TotalPledge = $row['TotalPledge'];
                $TotalPledge = round($TotalPledge, 2);
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Get total donations
            $sql = "CALL uspTotalCountOfDonations($EventYear)";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $TotalDonations = $row['TotalDono'];
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }

            // Get average donation
            $sql = "CALL uspAverageDonation($EventYear)";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $AverageDonation = $row['AverageDono'];
                $AverageDonation = round($AverageDonation, 2);
            }

            // Clear result set
            while($conn->more_results()){
                $conn->next_result();
                if($res = $conn->store_result())
                {
                    $res->free(); 
                }
            }
        }
    ?>

    <div class="content">
        <div class="stats">
            <h3>Total Pledges This Year</h3>
            <p>We had over $<?php echo $TotalPledge;?> in pledges this year!</p>
        </div>
        
        <div class="stats">
            <h3>Total Donations This Year</h3>
            <p>We had <?php echo $TotalDonations;?> donations this year!</p>
        </div>
        
        <div class="stats">
            <h3>Average Donation Amount This Year</h3>
            <p>On average our sponsors donated $<?php echo $AverageDonation;?>!</p>
        </div>
        
        <div class="popularity">
            <h3>Our Most Popular Golfers</h3>
            <?php 
                // Get top 3 golfers based on donations
                $sql = "CALL uspTop3Golfers($EventYear)";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $intGolferID = (int)$row['intGolferID'];
                    $GolferName = $row['GolferName'];
                    echo '<p>'.$GolferName.' | <a href="GolferDonors.php?ID='. $intGolferID .'">See Donors</a></p>';
                }
            ?>
        </div>
    </div>

    <?php 
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