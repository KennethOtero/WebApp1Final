<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
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
            // Go back to golfer stats
            header('Location: GolferStats.php');
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
        <div class="payment">
            <?php 
                try {
                    // Get Golfer ID
                    $GolferID = $_GET['ID'];

                    // Get Sponsor ID
                    $SponsorID = $_GET['SID'];
                    $_SESSION['intSponsorID'] = $SponsorID;

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
                        // Load payment statuses
                        $PaymentStatuses = mysqli_query($conn, "SELECT * FROM TPaymentStatuses");
                        $Sponsor = mysqli_query($conn, "SELECT CONCAT(strFirstName, ' ', strLastName) as SponsorName FROM TSponsors WHERE intSponsorID = $SponsorID");
                        $row = mysqli_fetch_assoc($Sponsor);
                        $SponsorName = $row['SponsorName'];

                        // Clear result set
                        while($conn->more_results()){
                            $conn->next_result();
                            if($res = $conn->store_result())
                            {
                                $res->free(); 
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo $e;
                }
            ?>
            <h1>Update Payment Status</h1>
            <form name="frmPayment" method="post" action="ProcessPaymentStatus.php?ID=<?php echo $GolferID;?>" onsubmit="return validateForm()">
                <span class="heading">Sponsor: <?php echo $SponsorName;?></span>
                <span class="details">Update payment status:</span>
                <select name="selPayment" id="selPayment">
                    <?php 
                        echo '<option value="0">Update Payment Status</option>';
                        while ($row = mysqli_fetch_assoc($PaymentStatuses)) {
                            $intPaymentStatusID = $row['intPaymentStatusID'];
                            $strPaymentStatus = $row['strPaymentStatus'];
                            echo '<option value="'. $intPaymentStatusID .'">'. $strPaymentStatus .'</option>';
                        }
                    ?>
                </select>
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Update">
            </form>
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

    <script>
        function validateForm() {
            var blnResult = true;

            var Pay = document.getElementById('SelPayment').value

            if (Pay == 0) {
                alert("Select a payment status.");
                document.getElementById("selPayment").focus();
                blnResult = false;
                return blnResult;
            }

            return blnResult;
        }
    </script>
</body>
</html>