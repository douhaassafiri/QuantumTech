<?php
session_start();

// Set secure session cookie
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

// Set session timeout in seconds
$timeout = 1800; // 30 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    // Last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

$_SESSION['last_activity'] = time(); // update last activity time stamp

if (!isset($_SESSION['manager_logged_in'])) {
    // Redirect to the login page if the manager is not logged in
    header('Location: manager_login.php');
    exit;
}

require_once ("settings.php"); // connection info


$conn = @mysqli_connect($host, $user, $pwd, $sql_db); // checks if connection is successful
if (!$conn) {
    // displays an error message
    echo "<p>Database connection failure</p>"; // not in production script
} else {
    // upon successful connection
    if (!mysqli_select_db($conn, $sql_db)) {
        echo "<p>Database selection failure</p>";
    }

    // Function to list all EOIs
    function listAllEOIs($conn)
    {
        $query = "SELECT EOI.*, Address.street_address, Address.suburb, Address.`state`, Address.postcode 
                  FROM EOI 
                  JOIN `Address` ON EOI.address_id = Address.address_id";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                echo "<table border='1'>";
                echo "<tr><th>EOI ID</th><th>Job Reference Number</th><th>First Name</th><th>Last Name</th><th>Date Of Birth</th><th>Gender</th><th>Street Address</th><th>Suburb/Town</th><th>State</th><th>Postcode</th><th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['eoi_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['job_reference_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_of_birth']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['street_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['suburb']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['postcode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['other_skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Error executing query: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<p>Error preparing statement: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    }

    // Function to list EOIs by position
    function listEOIsByPosition($conn, $jobReferenceNumber)
    {
        $query = "SELECT EOI.*, Address.street_address, Address.suburb, Address.`state`, Address.postcode 
                  FROM EOI 
                  JOIN `Address` ON EOI.address_id = Address.address_id
                  WHERE job_reference_number = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $jobReferenceNumber);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                echo "<table border='1'>";
                echo "<tr><th>EOI ID</th><th>Job Reference Number</th><th>First Name</th><th>Last Name</th><th>Date Of Birth</th><th>Gender</th><th>Street Address</th><th>Suburb/Town</th><th>State</th><th>Postcode</th><th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['eoi_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['job_reference_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_of_birth']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['street_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['suburb']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['postcode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['other_skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Error executing query: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<p>Error preparing statement: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    }

    // Function to list EOIs by applicant
    function listEOIsByApplicant($conn, $firstName, $lastName)
    {
        $query = "SELECT EOI.*, Address.street_address, Address.suburb, Address.`state`, Address.postcode 
                  FROM EOI 
                  JOIN `Address` ON EOI.address_id = Address.address_id
                  WHERE first_name = ? AND last_name = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $firstName, $lastName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                echo "<table border='1'>";
                echo "<tr><th>EOI ID</th><th>Job Reference Number</th><th>First Name</th><th>Last Name</th><th>Date Of Birth</th><th>Gender</th><th>Street Address</th><th>Suburb/Town</th><th>State</th><th>Postcode</th><th>Email</th><th>Phone Number</th><th>Skills</th><th>Other Skills</th><th>Status</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['eoi_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['job_reference_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_of_birth']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['street_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['suburb']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['postcode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['other_skills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Error executing query: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<p>Error preparing statement: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    }

    // Function to delete EOIs by job reference number
    function deleteEOIs($conn, $job_reference_number)
    {
        // Get the address_id associated with the EOI
        $query = "SELECT address_id FROM EOI WHERE job_reference_number = ?";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param($stmt, 's', $job_reference_number);
        if (!mysqli_stmt_execute($stmt)) {
            die("Execute failed: " . htmlspecialchars(mysqli_stmt_error($stmt)));
        }
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $address_id = $row['address_id'];

        // Delete the EOI
        $query = "DELETE FROM EOI WHERE job_reference_number = ?";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param($stmt, 's', $job_reference_number);
        if (!mysqli_stmt_execute($stmt)) {
            die("Execute failed: " . htmlspecialchars(mysqli_stmt_error($stmt)));
        }

        // Delete the address
        $query = "DELETE FROM `Address` WHERE address_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param($stmt, 'i', $address_id);
        if (!mysqli_stmt_execute($stmt)) {
            die("Execute failed: " . htmlspecialchars(mysqli_stmt_error($stmt)));
        }
    }

    // Function to change EOI status
    function changeEOIStatus($conn, $eoiId, $newStatus)
    {
        $query = "UPDATE EOI SET status = ? WHERE eoi_id = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $newStatus, $eoiId);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p>Status of EOI ID " . htmlspecialchars($eoiId) . " has been updated to " . htmlspecialchars($newStatus) . " successfully.</p>";
            } else {
                echo "<p>Error updating EOI status: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p>Error preparing statement: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Manage EOIs</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Manage EOIs</h1>
        <br>
        <hr>
        <br>
        <?php
        // Check if a form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['list_all_eois'])) {
                listAllEOIs($conn);
            } elseif (isset($_POST['list_eois_by_position'])) {
                $jobReferenceNumber = $_POST['job_reference_number'];
                if (!empty($jobReferenceNumber)) {
                    listEOIsByPosition($conn, $jobReferenceNumber);
                } else {
                    echo "<p>Please enter a job reference number.</p>";
                }
            } elseif (isset($_POST['list_eois_by_applicant'])) {
                $firstName = $_POST['first_name'];
                $lastName = $_POST['last_name'];
                if (!empty($firstName) && !empty($lastName)) {
                    listEOIsByApplicant($conn, $firstName, $lastName);
                } else {
                    echo "<p>Please enter both first name and last name.</p>";
                }
            } elseif (isset($_POST['delete_eois'])) {
                $jobReferenceNumber = $_POST['job_reference_number'];
                if (!empty($jobReferenceNumber)) {
                    deleteEOIs($conn, $jobReferenceNumber);
                    echo "<p>EOIs with job reference number " . htmlspecialchars($jobReferenceNumber) . " have been deleted.</p>";
                } else {
                    echo "<p>Please enter a job reference number.</p>";
                }
            } elseif (isset($_POST['change_eoi_status'])) {
                $eoiId = $_POST['eoi_id'];
                $newStatus = $_POST['new_status'];
                if (!empty($eoiId) && !empty($newStatus)) {
                    changeEOIStatus($conn, $eoiId, $newStatus);
                } else {
                    echo "<p>Please enter both EOI ID and new status.</p>";
                }
            }
        }
        ?>
        <br>
        <h2>List all EOIs</h2><br>
        <form method="post">
            <input type="submit" name="list_all_eois" value="List All EOIs">
        </form>
        <br>
        <h2>List EOIs by Position</h2><br>
        <form method="post">
            <label for="job_reference_number">Job Reference Number:</label>
            <input type="text" name="job_reference_number" id="job_reference_number">
            <br>
            <input type="submit" name="list_eois_by_position" value="List EOIs">
        </form>
        <br>
        <h2>List EOIs by Applicant</h2><br>
        <form method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name">
            <br>
            <input type="submit" name="list_eois_by_applicant" value="List EOIs">
        </form>
        <br>
        <h2>Delete EOIs</h2><br>
        <form method="post">
            <label for="job_reference_number">Job Reference Number:</label>
            <input type="text" name="job_reference_number" id="job_reference_number">
            <br>
            <input type="submit" name="delete_eois" value="Delete EOIs">
        </form>
        <br>
        <h2>Change EOI Status</h2><br>
        <form method="post">
            <label for="eoi_id">EOI ID:</label>
            <input type="text" name="eoi_id" id="eoi_id">
            <label for="new_status">New Status:</label>
            <input type="text" name="new_status" id="new_status">
            <br>
            <input type="submit" name="change_eoi_status" value="Change Status">
        </form>
        <br>
        <?php
        // Function to log out the manager
        function logout()
        {
            // Start the session if it's not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Unset all of the session variables
            $_SESSION = array();

            // If it's desired to kill the session, also delete the session cookie.
            // Note: This will destroy the session, and not just the session data!
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            // Finally, destroy the session.
            session_destroy();
            // Redirect to the index page
            header("Location: manager_login.php");
            exit();
        }

        // Check if the logout form has been submitted
        if (isset($_POST['logout'])) {
            logout();
        }
        ?>
        <form method="post">
            <input type="submit" name="logout" value="Logout">
        </form>
        <br><br><br>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>