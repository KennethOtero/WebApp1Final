<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Golfer</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/InsertGolfer.css">
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

        // Queries
        $Genders = mysqli_query($conn, "SELECT intGenderID, strGender FROM TGenders");
        $States = mysqli_query($conn, "SELECT intStateID, strState FROM TStates");
        $ShirtSize = mysqli_query($conn, "SELECT intShirtSizeID, strShirtSize FROM TShirtSizes");
    ?>

    <div class="AddGolfer">
        <div class="GolferForm">
            <h1>Register to Golf</h1>
            <form name="frmPlayerInfo" action="ProcessGolfer.php" method="post" onsubmit="return validateForm()">
                <span class="details">First Name:</span>
                <input type="text" value="<?php echo $_SESSION["txtFirstName"];?>" name="txtFirstName" id="txtFirstName" placeholder="Enter your first name" required>
                <span class="details">Last Name:</span>
                <input type="text" value="<?php echo $_SESSION["txtLastName"];?>" name="txtLastName" id="txtLastName" placeholder="Enter your last name" required>
                <span class="details">Address:</span>
                <input type="text" value="<?php echo $_SESSION["txtAddress"];?>" name="txtAddress" id="txtAddress" placeholder="Enter your address" required>
                <span class="details">City:</span>
                <input type="text" value="<?php echo $_SESSION["txtCity"];?>" name="txtCity" id="txtCity" placeholder="Enter your city" required>
                <span class="details">State:</span>
                <select name="cboStates" id="cboStates" required>
                    <?php 
                        echo '<option value="0">Select State</option>';
                        if (mysqli_num_rows($States) > 0) {
                            while($row = mysqli_fetch_assoc($States)) {
                                echo "<option value ='".$row["intStateID"]."'>" .$row["strState"]. "</option>";
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <span class="details">Zip Code:</span>
                <input type="text" value="<?php echo $_SESSION["txtZip"];?>" name="txtZip" id="txtZip" placeholder="Enter your zip code" required>
                <span class="details">Phone Number:</span>
                <input type="text" value="<?php echo $_SESSION["txtPhoneNumber"];?>" name="txtPhoneNumber" id="txtPhoneNumber" placeholder="Enter your phone number" required>
                <span class="details">Email:</span>
                <input type="email" value="<?php echo $_SESSION["txtEmail"];?>" name="txtEmail" id="txtEmail" placeholder="Enter your email" required>
                <span class="details">Shirt Size:</span>
                <select name="cboShirtSize" id="cboShirtSize" required>
                    <?php 
                        echo '<option value="0">Select Shirt Size</option>';
                        if (mysqli_num_rows($States) > 0) {
                            while($row = mysqli_fetch_assoc($ShirtSize)) {
                                echo "<option value ='".$row["intShirtSizeID"]."'>" .$row["strShirtSize"]. "</option>";
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
                                echo "<option value ='".$row["intGenderID"]."'>" .$row["strGender"]. "</option>";
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Submit"/>
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