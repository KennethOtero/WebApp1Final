<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/Login.css">
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
        if(TRUE) // toggle to false after debugging
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
                // Get form data
                $strLoginID = strtolower($_POST['txtLogin']);        
                $strPassword = $_POST['txtPassword'];
                $_SESSION['LoginID'] = $strLoginID;

                // Check if admin exists
                $sql = "CALL uspValidateLogin('$strLoginID', '$strPassword')";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = $result->fetch_assoc();
                    $$_SESSION['intAdminID'] = $row['intAdminID'];
                    $_SESSION['blnLoggedIn'] = TRUE;

                    // Clear result set
                    while($conn->more_results()){
                        $conn->next_result();
                        if($res = $conn->store_result())
                        {
                            $res->free(); 
                        }
                    }

                    // Go to admin homepage
                    header('Location: AdminHome.php');
                } else {
                    echo '
                    <div class="ContentMessage">
                        <h1>Login failed.</h1>
                        <a href="Login.php" class="GoBack">Go back</a>
                    </div>
                    <style>
                        .ContentMessage, .GoBack{
                            font-family: Arial, Helvetica, sans-serif;
                            color: #424242;
                            text-align: center;
                            margin-bottom: 575px;
                        }
                        
                        .ContentMessage a, .GoBack {
                            color: #418534;
                            text-decoration: none;
                        }
                    </style>';
                    $_SESSION['blnLoginFailed'] = TRUE;
                }
            }

            // Close database connection
            $conn->close();
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