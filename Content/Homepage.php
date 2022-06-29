<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <title>Golfathon</title>
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

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

        // Get current year
        $sql = "SELECT intEventYear FROM TEvents ORDER BY intEventID DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $intEventYear = $row['intEventYear'];
            $_SESSION['intEventYear'] = $intEventYear;
        }
        mysqli_free_result($result);

        // Get total donations
        $result = mysqli_query($conn, "CALL uspTotalDonations($intEventYear)");
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $TotalAmount = round($row['TotalDono'], 2);
            }
        }

        // Clear result set
        while($conn->more_results()){
            $conn->next_result();
            if($res = $conn->store_result())
            {
                $res->free(); 
            }
        }

        // Get the #1 golfer
        $result = mysqli_query($conn, "CALL uspTopGolferDonations($intEventYear)");
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $TopGolferName = $row['GolferName'];
                $TopGolferDono = round($row['TotalPledges'], 2);
            }
        }

        // Clear result set
        while($conn->more_results()){
            $conn->next_result();
            if($res = $conn->store_result())
            {
                $res->free(); 
            }
        }

        // Get the number of all playing golfers
        $result = mysqli_query($conn, "CALL uspViewGolfersInEvent($intEventYear)");
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $TotalGolferNumber = $row['TotalGolfers'];
            }
        }
        
        // Clear result set
        while($conn->more_results()){
            $conn->next_result();
            if($res = $conn->store_result())
            {
                $res->free(); 
            }
        }

        // Get the most recent donation
        $result = mysqli_query($conn, "SELECT * FROM vMostRecentDonation");
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $NewDonator = $row['SponsorName'];
                $Donation = round($row['Donation'], 2);
            }
        }

        // Clear result set
        while($conn->more_results()){
            $conn->next_result();
            if($res = $conn->store_result())
            {
                $res->free(); 
            }
        }

        // Close connection
        mysqli_close($conn);
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

    <div class="Welcome">
        <p class="Cincy">Cincinnati State's</p>
        <p class="Golfathon">GOLFATHON</p>
        <hr class="GolfSeparate">
    </div>
    
    <div class="Content">
        <img src="../../Images/NewGolfImage.png" class="GolfImage" alt="GolfImage">
        <div class="Info">
            <h2>See How Much We've Raised!</h2>
            <p>We've raised $<?php echo $TotalAmount;?> this year!</p>
        </div>
        <div class="Info">
            <h2>See Our Most Popular Golfer!</h2>
            <p><?php echo $TopGolferName;?> has amassed the most donations this year with over $<?php echo $TopGolferDono;?>!</p>
        </div>
        <div class="Info">
            <h2>Shout Out To All Our Golfers!</h2>
            <p>We had <?php echo $TotalGolferNumber;?> golfers this year!</p>
        </div>
        <div class="RecentDono">
            <h2>Most Recent Donation</h2>
            <p><?php echo $NewDonator;?> is our most recent donator with $<?php echo $Donation;?>!</p>
        </div>
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