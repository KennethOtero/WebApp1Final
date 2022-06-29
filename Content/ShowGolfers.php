<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Golfers</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/ShowGolfers.css">
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

            if ($conn) {
                // Get current year
                $result = mysqli_query($conn, "SELECT MAX(intEventYear) as Year FROM TEvents");
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $intEventYear = $row['Year'];
                }

                // Clear result set
                while($conn->more_results()){
                    $conn->next_result();
                    if($res = $conn->store_result())
                    {
                        $res->free(); 
                    }
                }

                // Display all Golfers
                $sql = "SELECT 
                            TG.intGolferID,
                            TG.strFirstName,
                            TG.strLastName,
                            TG.strAddress,
                            TG.strCity,
                            TS.strState,
                            TG.strZip,
                            TG.strPhoneNumber,
                            TG.strEmail,
                            TSS.strShirtSize,
                            TGD.strGender
                        FROM
                            TGolfers as TG JOIN TStates as TS
                                ON TG.intStateID = TS.intStateID
                            JOIN TShirtSizes as TSS
                                ON TSS.intShirtSizeID = TG.intShirtSizeID
                            JOIN TGenders as TGD
                                ON TGD.intGenderID = TG.intGenderID
                            JOIN TEventGolfers as TEG
                                ON TEG.intGolferID = TG.intGolferID
                            JOIN TEvents as TE
                                ON TE.intEventID = TEG.intEventID
                        WHERE TE.intEventYear = $intEventYear";
                $result = $conn->query($sql);

                // Formatting
                echo '<div class="Title"><h1>All Golfers</h1></div>';

                // Loop through all golfers
                while ($obj = $result->fetch_assoc()) {
                    echo '
                        <div class="ShowGolfers">
                            <span class="details">First Name:</span>
                            <span>' . $obj['strFirstName'] . '</span><br>
                            <span class="details">Last Name:</span>
                            <span>' . $obj['strLastName'] . '</span><br>
                            <span class="details">Address:</span>
                            <span>' . $obj['strAddress'] . '</span><br>
                            <span class="details">City:</span>
                            <span>' . $obj['strCity'] . '</span><br>
                            <span class="details">State:</span>
                            <span>' . $obj['strState'] . '</span><br>
                            <span class="details">Zip Code:</span>
                            <span>' . $obj['strZip'] . '</span><br>
                            <span class="details">Phone Number:</span>
                            <span>' . $obj['strPhoneNumber'] . '</span><br>
                            <span class="details">Email:</span>
                            <span>' . $obj['strEmail'] . '</span><br>
                            <span class="details">Shirt Size:</span>
                            <span>' . $obj['strShirtSize'] . '</span><br>
                            <span class="details">Gender:</span>
                            <span>' . $obj['strGender'] . '</span><br>
                            <a href="Donate.php?ID= ' . $obj['intGolferID'] .'"><input type="button" name="btnDonate" id="btnDonate" value="Donate"</a>
                            <a href = "UpdateGolfer.php?ID= ' . $obj['intGolferID'] .'"><input type="button" name="btnUpdate" id="btnUpdate" value="Update"></a>
                        </div>';
                }
            }

            // Close DB connection
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