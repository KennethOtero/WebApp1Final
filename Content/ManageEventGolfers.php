<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events and Golfers</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/ManageEventGolfers.css">
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
        <div class="EventGolfers">
            <h1>Manage Event Golfers</h1>
            <form name="frmEventGolfer" action="" method="post" onsubmit="return validateForm()">
                <span class="details">Enter event year:</span>
                <input type="text" name="txtYear" id="txtYear" maxlength="4" placeholder="Enter a year">
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Submit">
            </form>
        </div>
        <div class="GolferInfo">
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
                        if (isset($_POST['btnSubmit'])) {
                            // Get current event year
                            $intEventYear = $_POST['txtYear'];
                            $_SESSION['txtYear'] = $intEventYear;

                            // Get event collected total
                            $sql = "CALL uspTotalCollected($intEventYear)";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $EventCollected = $row['TotalCollected'];
                                }
                            }
                            if (!isset($EventCollected)) {
                                $EventCollected = 0;
                            }

                            // Clear result set
                            while($conn->more_results()){
                                $conn->next_result();
                                if($res = $conn->store_result())
                                {
                                    $res->free(); 
                                }
                            }

                            // Get event pledged total
                            $sql = "CALL uspTotalPledged($intEventYear)";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $EventPledged = $row['TotalPledge'];
                                }
                            }
                            if (!isset($EventPledged)) {
                                $EventPledged = 0;
                            }

                            // Clear result set
                            while($conn->more_results()){
                                $conn->next_result();
                                if($res = $conn->store_result())
                                {
                                    $res->free(); 
                                }
                            }

                            echo '<h3>Event Totals</h3>';
                            echo '<p>In '. $intEventYear .' we had $'. $EventPledged .' pledged and $'. $EventCollected .' collected.</p>';
                            echo '<h3>Golfers Playing In '. $intEventYear .'</h3>';

                            // Get all the golfers for the current year
                            $sql = "SELECT 
                                        TG.intGolferID,
                                        CONCAT(TG.strFirstName, ' ', TG.strLastName) as GolferName
                                    FROM
                                        TGolfers as TG JOIN TEventGolfers as TEG
                                            ON TG.intGolferID = TEG.intGolferID
                                        JOIN TEvents as TE
                                            ON TE.intEventID = TEG.intEventID
                                    WHERE TE.intEventYear = $intEventYear";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Get data
                                    $GolferName = $row['GolferName'];
                                    $GolferID = $row['intGolferID'];

                                    // Get total pledged for a golfer
                                    $GolferTotalPledge = "CALL uspGolferTotalPledged($GolferID)";
                                    $GolferTotalPledgeResult = mysqli_query($conn, $GolferTotalPledge);
                                    if ($GolferTotalPledgeResult) {
                                        while ($row = mysqli_fetch_assoc($GolferTotalPledgeResult)) {
                                            $TotalPledged = $row['TotalPledged'];
                                        }
                                    }
                                    if (!isset($TotalPledged)) {
                                        $TotalPledged = 0;
                                    }

                                    // Clear result set
                                    while($conn->more_results()){
                                        $conn->next_result();
                                        if($res = $conn->store_result())
                                        {
                                            $res->free(); 
                                        }
                                    }

                                    // Get total collected for a golfer
                                    $GolferTotalCollected = "CALL uspGolferTotalCollected($GolferID)";
                                    $GolferTotalCollectedResult = mysqli_query($conn, $GolferTotalCollected);
                                    if ($GolferTotalCollectedResult) {
                                        while ($row = mysqli_fetch_assoc($GolferTotalCollectedResult)) {
                                            $TotalCollected = $row['TotalCollected'];
                                        }
                                    }
                                    if (!isset($TotalCollected)) {
                                        $TotalCollected = 0;
                                    }

                                    // Clear result set
                                    while($conn->more_results()){
                                        $conn->next_result();
                                        if($res = $conn->store_result())
                                        {
                                            $res->free(); 
                                        }
                                    }

                                    // Display results
                                    echo '<p><a href="GolferDonors.php?ID='. $GolferID .'">'. $GolferName .'</a> has $'. $TotalPledged .' in pledges and $'. $TotalCollected .' collected.</p>';
                                }
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
            // Result
            var blnResult = true;

            // Get form data
            var Year = document.getElementById('txtYear').value.trim();

            // Validate
            if (Year == "" || !isNumeric(Year)) {
                // Display error
                alert("Enter a year.");
                document.getElementById("txtYear").focus();
                blnResult = false;
                return blnResult;
            }
            return blnResult;
        }

        // Validate a number
        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
    </script>
</body>
</html>