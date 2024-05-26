<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Manager Login</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Manager Login</h1>
        <br>
        <hr>
        <br>

        <?php
        // Enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // Start the session
        session_start();
        $username = $password = "";
        $usernameErr = $passwordErr = "";
        $loginDelay = 30; // delay in seconds after 3 failed attempts
        $maxAttempts = 3;

        require_once("settings.php"); // connection info
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db); // checks if connection is successful
        if (!$conn) {
            echo "<p>Database connection failure</p>"; // not in production script
        } else {
            // Function to clean data input
            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Check if username is set
                if (isset($_POST["username"])) {
                    $username = test_input($_POST["username"]);
                } else {
                    $usernameErr = "Username is required";
                }

                // Check if password is set
                if (isset($_POST["password"])) {
                    $password = test_input($_POST["password"]);
                } else {
                    $passwordErr = "Password is required";
                }

                // If there are no errors, check the credentials in the database
                if (empty($usernameErr) && empty($passwordErr)) {
                    // Check if the session variable for login attempts is set
                    if (!isset($_SESSION["attempts"])) {
                        $_SESSION["attempts"] = 0;
                        $_SESSION["last_attempt_time"] = time();
                    }

                    // Check if the user has failed too many times and if the delay period has passed
                    if ($_SESSION["attempts"] >= $maxAttempts && (time() - $_SESSION["last_attempt_time"]) < $loginDelay) {
                        echo "Too many failed login attempts. Please try again after " . ($loginDelay - (time() - $_SESSION["last_attempt_time"])) . " seconds.";
                    } else {
                        if ((time() - $_SESSION["last_attempt_time"]) >= $loginDelay) {
                            // Reset attempts if the delay period has passed
                            $_SESSION["attempts"] = 0;
                        }

                        // Prepare a select statement
                        $sql = "SELECT `password` FROM managers WHERE username = ?";
                        if ($stmt = $conn->prepare($sql)) {
                            // Bind variables to the prepared statement as parameters
                            $stmt->bind_param("s", $username);

                            // Attempt to execute the prepared statement
                            if ($stmt->execute()) {
                                // Store result
                                $stmt->store_result();

                                // Check if username exists
                                if ($stmt->num_rows == 1) {
                                    // Bind result variables
                                    $stmt->bind_result($db_password);
                                    if ($stmt->fetch()) {
                                        // Check if password matches
                                        if ($password === $db_password) {
                                            // Password is correct, so start a new session
                                            $_SESSION["manager_logged_in"] = true;
                                            $_SESSION["username"] = $username;

                                            // Redirect user to manage page
                                            header("location: manage.php");
                                            exit;
                                        } else {
                                            // Display an error message if password is not valid
                                            $passwordErr = "The password you entered was not valid.";
                                            $_SESSION["attempts"]++;
                                            $_SESSION["last_attempt_time"] = time();
                                        }
                                    } else {
                                        // No account found with that username
                                        $usernameErr = "No account found with that username.";
                                        $_SESSION["attempts"]++;
                                        $_SESSION["last_attempt_time"] = time();
                                    }
                                } else {
                                    // No account found with that username
                                    $usernameErr = "No account found with that username.";
                                    $_SESSION["attempts"]++;
                                    $_SESSION["last_attempt_time"] = time();
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            // Close statement
                            $stmt->close();
                        }
                    }
                    // Close connection
                    $conn->close();
                }
            }
        }
        ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="" value="<?php echo htmlspecialchars($username); ?>">
            <span class="error"><?php echo "<br>$usernameErr"; ?></span>

            <br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="">
            <span class="error"><?php echo "<br>$passwordErr"; ?></span>

            <br>

            <input type="submit" value="Login">
        </form>
        <br>
        <p>Not a user?</p>
        <a class="btn-job register" href="manager_register.php">Register</a>
        <br><br><br><br>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>