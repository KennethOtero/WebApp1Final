<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Golfer</title>
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

            // Get GolferID
            $GolferID = $_GET['ID'];

            // Get all golfer info from GolferID passed in from the URL
            $sql = "SELECT * FROM TGolfers WHERE intGolferID = $GolferID";
            $result = $conn->query($sql);

            // Print results
            while($row = mysqli_fetch_assoc($result)) {
                $_SESSION['GolferID']  = $row['intGolferID'];
                $_SESSION['FirstName'] = $row['strFirstName'];
                $_SESSION['LastName']  = $row['strLastName'];
                $_SESSION['Address']   = $row['strAddress'];
                $_SESSION['City']      = $row['strCity'];
                $_SESSION['StateID']   = $row['intStateID'];
                $_SESSION['Zip']       = $row['strZip'];
                $_SESSION['Phone']     = $row['strPhoneNumber'];
                $_SESSION['Email']     = $row['strEmail'];
                $_SESSION['ShirtSizeID'] = $row['intShirtSizeID'];
                $_SESSION['GenderID']  = $row['intGenderID'];
            }

            // Combobox Queries
            $Genders = mysqli_query($conn, "SELECT intGenderID, strGender FROM TGenders");
            $States = mysqli_query($conn, "SELECT intStateID, strState FROM TStates");
            $ShirtSize = mysqli_query($conn, "SELECT intShirtSizeID, strShirtSize FROM TShirtSizes");
        } catch (Exception $e) {
            echo $e;
        }
    ?>

    <div class="UpdateGolfer">
        <div class="GolferForm">
            <h1>Update Golfer</h1>
            <form name="frmPlayerInfo" action="ProcessUpdateGolfer.php?ID=<?php echo $_SESSION['GolferID']?>" method="post" onsubmit="return validateForm()">
                <span class="details">First Name:</span>
                <input type="text" value="<?php echo $_SESSION["FirstName"];?>" name="txtFirstName" id="txtFirstName" placeholder="Enter your first name" required>
                <span class="details">Last Name:</span>
                <input type="text" value="<?php echo $_SESSION["LastName"];?>" name="txtLastName" id="txtLastName" placeholder="Enter your last name" required>
                <span class="details">Address:</span>
                <input type="text" value="<?php echo $_SESSION["Address"];?>" name="txtAddress" id="txtAddress" placeholder="Enter your address" required>
                <span class="details">City:</span>
                <input type="text" value="<?php echo $_SESSION["City"];?>" name="txtCity" id="txtCity" placeholder="Enter your city" required>
                <span class="details">State:</span>
                <select name="cboStates" id="cboStates" required>
                    <?php 
                        echo '<option value="0">Select State</option>';
                        if (mysqli_num_rows($States) > 0) {
                            while($row = mysqli_fetch_assoc($States)) {
                                // Select the golfer's state
                                $selected = $_SESSION['StateID'];
                                if ($row['intStateID'] == $selected) {
                                    echo "<option value=".$row['intStateID']." selected>" .$row['strState']."</option>";
                                } else {
                                    echo "<option value =".$row["intStateID"].">" .$row["strState"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <span class="details">Zip Code:</span>
                <input type="text" value="<?php echo $_SESSION["Zip"];?>" name="txtZip" id="txtZip" placeholder="Enter your zip code" required>
                <span class="details">Phone Number:</span>
                <input type="text" value="<?php echo $_SESSION["Phone"];?>" name="txtPhoneNumber" id="txtPhoneNumber" placeholder="Enter your phone number" required>
                <span class="details">Email:</span>
                <input type="email" value="<?php echo $_SESSION["Email"];?>" name="txtEmail" id="txtEmail" placeholder="Enter your email" required>
                <span class="details">Shirt Size:</span>
                <select name="cboShirtSize" id="cboShirtSize" required>
                    <?php 
                        echo '<option value="0">Select Shirt Size</option>';
                        if (mysqli_num_rows($States) > 0) {
                            while($row = mysqli_fetch_assoc($ShirtSize)) {
                                // Select the golfer's shirt size
                                $selected = $_SESSION['ShirtSizeID'];
                                if ($row['intShirtSizeID'] == $selected) {
                                    echo "<option value =".$row["intShirtSizeID"]." selected>" .$row["strShirtSize"]. "</option>";
                                } else {
                                    echo "<option value =".$row["intShirtSizeID"].">" .$row["strShirtSize"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <span class="details">Gender:</span>
                <select name="cboGenders" id="cboGenders">
                    <?php 
                        echo '<option value="0">Select Gender</option>';
                        if (mysqli_num_rows($Genders) > 0) {
                            while($row = mysqli_fetch_assoc($Genders)) {
                                // Select the golfer's gender
                                $selected = $_SESSION['GenderID'];
                                if ($row['intGenderID'] == $selected) {
                                    echo "<option value =".$row["intGenderID"]." selected>" .$row["strGender"]. "</option>";
                                } else {
                                    echo "<option value =".$row["intGenderID"].">" .$row["strGender"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Update"/>
            </form>
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

    <script>
        function validateForm() {
            // Boolean result
            var blnResult = true;

            // Get form input
            var FirstName = document.getElementById('txtFirstName').value.trim();
            var LastName = document.getElementById('txtLastName').value.trim();
            var Address = document.getElementById('txtAddress').value.trim();
            var City = document.getElementById('txtCity').value.trim();
            var State = document.getElementById('cboStates').value;
            var Zip = document.getElementById('txtZip').value.trim();
            var Phone = document.getElementById('txtPhoneNumber').value.trim();
            var Email = document.getElementById('txtEmail').value.trim();
            var ShirtSize = document.getElementById('cboShirtSize').value;
            var Gender = document.getElementById('cboGenders').value;

            // Check for empty strings
            if (FirstName == "") {
                alert("Enter a first name.");
                document.getElementById("txtFirstName").focus();
                blnResult = false;
                return blnResult;
            }
            if (LastName == "") {
                alert("Enter a last name.");
                document.getElementById("txtLastName").focus();
                blnResult = false;
                return blnResult;
            }
            if (Address == "") {
                alert("Enter an address.");
                document.getElementById("txtAddress").focus();
                blnResult = false;
                return blnResult;
            }
            if (City == "") {
                alert("Enter a city.");
                document.getElementById("txtCity").focus();
                blnResult = false;
                return blnResult;
            }
            if (State == 0) {
                alert("Select a state.");
                document.getElementById("cboStates").focus();
                blnResult = false;
                return blnResult;
            }
            if (Zip == "") {
                alert("Enter a zip code.");
                document.getElementById("txtZip").focus();
                blnResult = false;
                return blnResult;
            }
            if (Phone == "") {
                alert("Enter a phone number.");
                document.getElementById("txtPhoneNumber").focus();
                blnResult = false;
                return blnResult;
            }
            if (Email == "") {
                alert("Enter an email.");
                document.getElementById("txtEmail").focus();
                blnResult = false;
                return blnResult;
            }
            if (ShirtSize == 0) {
                alert("Select a shirt size.");
                document.getElementById("cboShirtSize").focus();
                blnResult = false;
                return blnResult;
            }
            if (Gender == 0) {
                alert("Select a gender.");
                document.getElementById("cboGenders").focus();
                blnResult = false;
                return blnResult;
            }

            // Return result
            return blnResult;
        }
    </script>
</body>
</html>