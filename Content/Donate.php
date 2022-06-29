<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <link rel="stylesheet" href="../../Styles/Homepage.css">
    <link rel="stylesheet" href="../../Styles/Donate.css">
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
        // Create variable
        $GolferID = 0;

        // Get the GolferID
        $GolferID = $_GET['ID'];

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
                // Get select box info
                $intEventYear = $_SESSION['intEventYear'];
                $sql = 
                    "SELECT
                        CONCAT(TG.strFirstName, ' ', TG.strLastName) as GolferName,
                        TG.intGolferID
                    FROM
                        TGolfers as TG JOIN TEventGolfers as TEG
                            ON TG.intGolferID = TEG.intGolferID
                        JOIN TEvents as TE
                            ON TE.intEventID = TEG.intEventID
                    WHERE
                        TE.intEventYear = $intEventYear";
                $GetGolfers = mysqli_query($conn, $sql);
                $States = mysqli_query($conn, "SELECT * FROM TStates");
                $PaymentTypes = mysqli_query($conn, "SELECT * FROM TPaymentTypes");
            }
        } catch (Exception $e) {
            echo $e;
        }
    ?>

    <div class="content">
        <div class="Donate">
            <h1>Make A Donation</h1>
            <form name="frmSponsor" action="ProcessDonate.php" method="post" onsubmit="return validateForm()">
                <span class="details">Pick Golfer:</span>
                <select name="selGolfer" id="selGolfer">
                    <?php 
                        echo '<option value="0">Select Golfer</option>';
                        if (mysqli_num_rows($GetGolfers) > 0) {
                            while($row = mysqli_fetch_assoc($GetGolfers)) {
                                if ($row['intGolferID'] == $GolferID || $row['intGolferID'] == $_SESSION['intGolferID']) {
                                    echo "<option value ='".$row["intGolferID"]."' selected>" .$row["GolferName"]. "</option>";
                                } else {
                                    echo "<option value ='".$row["intGolferID"]."'>" .$row["GolferName"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <span class="details">First Name:</span>
                <input type="text" name="txtFirstName" id="txtFirstName" value="<?php echo $_SESSION['txtFirstName'];?>" placeholder="Enter your first name">
                <span class="details">Last Name:</span>
                <input type="text" name="txtLastName" id="txtLastName" value="<?php echo $_SESSION['txtLastName'];?>" placeholder="Enter your last name">
                <span class="details">Address:</span>
                <input type="text" name="txtAddress" id="txtAddress" value="<?php echo $_SESSION['txtAddress'];?>" placeholder="Enter your address">
                <span class="details">City:</span>
                <input type="text" name="txtCity" id="txtCity" value="<?php echo $_SESSION['txtCity'];?>" placeholder="Enter your city">
                <span class="details">State:</span>
                <select name="selState" id="selState">
                    <?php 
                        echo '<option value="0">Select State</option>';
                        if (mysqli_num_rows($States) > 0) {
                            while($row = mysqli_fetch_assoc($States)) {
                                if ($row['intStateID'] == $_SESSION['intStateID']) {
                                    echo "<option value ='".$row["intStateID"]."' selected>" .$row["strState"]. "</option>";
                                } else {
                                    echo "<option value ='".$row["intStateID"]."'>" .$row["strState"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <span class="details">Zip Code:</span>
                <input type="text" name="txtZip" id="txtZip" value="<?php echo $_SESSION['txtZip'];?>" placeholder="Enter your zip code">
                <span class="details">Phone Number:</span>
                <input type="text" name="txtPhone" id="txtPhone" value="<?php echo $_SESSION['txtPhone'];?>" placeholder="Enter your phone number">
                <span class="details">Email:</span>
                <input type="email" name="txtEmail" id="txtEmail" value="<?php echo $_SESSION['txtEmail'];?>" placeholder="Enter your email">
                <span class="details">Donation Amount:</span>
                <input type="text" name="txtDonation" id="txtDonation" value="<?php echo $_SESSION['txtDonation'];?>" placeholder="Enter your donation amount">
                <span class="details">Payment Type:</span>
                <select name="selPayment" id="selPayment">
                    <?php 
                        echo '<option value="0">Select Payment Method</option>';
                        if (mysqli_num_rows($PaymentTypes) > 0) {
                            while($row = mysqli_fetch_assoc($PaymentTypes)) {
                                if ($row['intPaymentTypeID'] == $_SESSION['intPaymentTypeID']) {
                                    echo "<option value ='".$row["intPaymentTypeID"]."' selected>" .$row["strPaymentType"]. "</option>";
                                } else {
                                    echo "<option value ='".$row["intPaymentTypeID"]."'>" .$row["strPaymentType"]. "</option>";
                                }
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </select>
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Donate">
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
            // Result
            var blnResult = true;

            // Get form input
            var GolferID = document.getElementById('selGolfer').value;
            var FirstName = document.getElementById('txtFirstName').value.trim();
            var LastName = document.getElementById('txtLastName').value.trim();
            var Address = document.getElementById('txtAddress').value.trim();
            var City = document.getElementById('txtCity').value.trim();
            var StateID = document.getElementById('selState').value;
            var Zip = document.getElementById('txtZip').value.trim();
            var Phone = document.getElementById('txtPhone').value.trim();
            var Email = document.getElementById('txtEmail').value.trim();
            var Donation = document.getElementById('txtDonation').value.trim();
            var Payment = document.getElementById('selPayment').value.trim();

            // Validate form
            if (GolferID == 0) {
                alert("Select a golfer.");
                document.getElementById("selGolfer").focus();
                blnResult = false;
                return blnResult;
            }
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
            if (StateID == 0) {
                alert("Select a state.");
                document.getElementById("selState").focus();
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
                document.getElementById("txtPhone").focus();
                blnResult = false;
                return blnResult;
            }
            if (Email == "") {
                alert("Enter an email.");
                document.getElementById("txtEmail").focus();
                blnResult = false;
                return blnResult;
            }
            if (Donation == "" || !isNumeric(Donation)) {
                alert("Enter a donation.");
                document.getElementById("txtDonation").focus();
                blnResult = false;
                return blnResult;
            }
            if (Payment == 0) {
                alert("Enter a payment method.");
                document.getElementById("selPayment").focus();
                blnResult = false;
                return blnResult;
            }

            // Return result
            return blnResult;
        }

        // Validate a number
        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
    </script>
</body>
</html>