<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Process EOI</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Process EOI</h1>
        <br>
        <hr>
        <br>
        <?php
        require_once ("settings.php");
        function sanitize_input($data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = sanitize_input($value);
                }
            } else {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
            }
            return $data;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = sanitize_input($_POST);
            $required_fields = ['job_reference_number', 'first_name', 'last_name', 'email'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    die("<p>Error: $field is required.</p>");
                }
            }
            $job_reference_number = $_POST['job_reference_number'];
            $reference_number = isset($_POST['job_reference_number']) ? $_POST['job_reference_number'] : '';
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : '';
            $suburb = isset($_POST['suburb']) ? $_POST['suburb'] : '';
            $state = isset($_POST['state']) ? $_POST['state'] : '';
            $postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
            $email = $_POST['email'];
            $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
            $skills = isset($_POST['skills']) ? implode(', ', $_POST['skills']) : '';
            $other_skills = isset($_POST['other_skills']) ? $_POST['other_skills'] : '';
            $status = 'New';

            $errors = [];
            if (!preg_match("/^[a-zA-Z0-9]{5}$/", $job_reference_number)) {
                $errors[] = "Job reference number must be exactly 5 alphanumeric characters.";
            }
            if (!preg_match("/^[a-zA-Z ]{1,20}$/", $first_name)) {
                $errors[] = "First name must be max 20 alpha characters.";
            }
            if (!preg_match("/^[a-zA-Z ]{1,20}$/", $last_name)) {
                $errors[] = "Last name must be max 20 alpha characters.";
            }
            $dob_parts = explode("/", $date_of_birth);
            if (count($dob_parts) == 3 && checkdate($dob_parts[1], $dob_parts[0], $dob_parts[2])) {
                $dob = DateTime::createFromFormat('d/m/Y', $date_of_birth);
                $now = new DateTime();
                $age = $dob->diff($now)->y;
                if ($age < 15 || $age > 80) {
                    $errors[] = "Date of birth must be between 15 and 80 years.";
                }
            } else {
                $errors[] = "Date of birth must be in dd/mm/yyyy format.";
            }
            if (!in_array($gender, ['male', 'female', 'other'])) {
                $errors[] = "Gender must be selected.";
            }
            if (strlen($street_address) > 40) {
                $errors[] = "Street address must be max 40 characters.";
            }
            if (strlen($suburb) > 40) {
                $errors[] = "Suburb/town must be max 40 characters.";
            }
            if (!in_array($state, ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'])) {
                $errors[] = "State must be one of VIC, NSW, QLD, NT, WA, SA, TAS, ACT.";
            }
            if (!preg_match("/^[0-9]{4}$/", $postcode)) {
                $errors[] = "Postcode must be exactly 4 digits.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address format.";
            }
            if (!preg_match("/^[0-9 ]{8,12}$/", $phone_number)) {
                $errors[] = "Phone number must be 8 to 12 digits or spaces.";
            }
            if (empty($other_skills) && isset($_POST['other_skills_check'])) {
                $errors[] = "Other skills must not be empty if selected.";
            }

            if (!empty($errors)) {
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                exit();
            }

            $conn = mysqli_connect($host, $user, $pwd, $sql_db);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $eoi_table = "EOI";
            $address_table = "Address";

            // Insert into Address table
            $query = "INSERT INTO $address_table (street_address, suburb, `state`, postcode) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ssss', $street_address, $suburb, $state, $postcode);
            if (!mysqli_stmt_execute($stmt)) {
                die("Execute failed: " . mysqli_stmt_error($stmt));
            }
            $address_id = mysqli_insert_id($conn);  // Get the last inserted ID
        
            // Insert into EOI table
            $query = "INSERT INTO $eoi_table (job_reference_number, first_name, last_name, date_of_birth, gender, email, phone_number, skills, other_skills, `status`, address_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ssssssssssi', $job_reference_number, $first_name, $last_name, $date_of_birth, $gender, $email, $phone_number, $skills, $other_skills, $status, $address_id);

            if (mysqli_stmt_execute($stmt)) {
                $last_id = mysqli_insert_id($conn);
                $query = "SELECT E.*, A.street_address, A.suburb, A.state, A.postcode 
                          FROM $eoi_table E 
                          JOIN $address_table A ON E.address_id = A.address_id 
                          WHERE E.eoi_id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'i', $last_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

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
                echo "<p>Failed to submit application. Please try again later.</p>";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            header("location: apply.php");
        }
        ?>
        <br>
        <hr>
        <br>
        <a class="btn btn-job btn-cont" href="apply.php">Apply for another job</a>
        <br>
        <br>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>