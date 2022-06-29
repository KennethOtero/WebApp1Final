<?php 
    // Check if the session has started
    if (!isset($_SESSION)) {
        // Start the session
        session_start();
    }

    // Set session login variables to false (log out)
    $_SESSION['blnLoggedIn'] = false;

    // Destroy the session
    session_unset();
    session_destroy();

    // Return to homepage
    header('Location: Homepage.php');
?>