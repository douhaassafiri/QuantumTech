<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Manager Registration</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Manager Registration</h1>
        <br>
        <hr>
        <br>

        <?php
        require_once("settings.php"); // connection info
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db); // checks if connection is successful
        if (!$conn) {
            echo "<p>Database connection failure</p>"; // not in production script
        } else {
            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate username
                if (empty($_POST["username"])) {
                    $usernameErr = "Please enter a username.";
                } else {
                    $username = test_input($_POST["username"]);
                    // Check if username already exists
                    $sql = "SELECT manager_id FROM managers WHERE username = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("s", $username);
                        if ($stmt->execute()) {
                            $stmt->store_result();
                            if ($stmt->num_rows == 1) {
                                $usernameErr = "This username is already taken.";
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        $stmt->close();
                    }
                }

                // Validate password
                if (empty($_POST["password"])) {
                    $passwordErr = "Please enter a password.";
                } else {
                    $password = test_input($_POST["password"]);
                }

                // Check input errors before inserting in database
                if (empty($usernameErr) && empty($passwordErr)) {
                    // Prepare an insert statement
                    $sql = "INSERT INTO managers (username, `password`) VALUES (?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        // Bind variables to the prepared statement as parameters
                        $stmt->bind_param("ss", $username, $password);
                        // Attempt to execute the prepared statement
                        if ($stmt->execute()) {
                            // Redirect to login page
                            header("location: manager_login.php");
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        // Close statement
                        $stmt->close();
                    }
                }
                // Close connection
                mysqli_close($conn);
            }
        }
        ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="">
            <span></span>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="">
            <span></span>
            <br><br>
            <input type="submit" value="Register">
        </form>


    </main>
    <?php include 'footer.php'; ?>

</body>

</html>