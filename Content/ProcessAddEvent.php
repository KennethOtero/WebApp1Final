<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Event</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/AddEvent.css">
</head>
<body>
    <?php 
        // Start the session
        if (!isset($_SESSION)) {
            session_start();
        }

        // Check login status
        if ($_SESSION['blnLoggedIn'] == FALSE) {
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
                // Get form input
                $Year = $_POST['txtYear'];
                $_SESSION['txtYear'] = $Year;

                // Insert year into TEvents
                $sql = "CALL uspAddEvent($Year)";
                $result = mysqli_query($conn, $sql);

                // Clear result set
                while($conn->more_results()){
                    $conn->next_result();
                    if($res = $conn->store_result())
                    {
                        $res->free(); 
                    }
                }

                // Update event year
                $sql = "SELECT MAX(intEventYear) as Year FROM TEvents";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['intEventYear'] = $row['Year'];
                }

                if ($result) {
                    // Head back to admin homepage
                    header('Location: AdminHome.php');
                } else {
                    // Display error
                    echo 
                    '
                    <div class="ContentMessage">
                        <h1>Event Insert Failed.</h1>
                        <a href="InsertGolfer.php" class="GoBack">Go Back</a>
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