<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Apply</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Job Application Form</h1>
        <p>Please fill out the form below to submit your job application.</p>
        <br>
        <hr>
        <br>
        <div class="form-container">
            <form action="processEOI.php" method="POST" novalidate="novalidate" >
                <label for="job_reference_number"><strong>Job Reference Number:</strong></label>
                <input type="text" id="job_reference_number" name="job_reference_number" size="5" maxlength="5" pattern="[A-Za-z0-9]{5}" placeholder="" required>
                <span></span>
                <br><br>

                <label for="first_name"><strong>First Name:</strong></label>
                <input type="text" id="first_name" name="first_name" size="20" maxlength="20" pattern="[A-Za-z]+{20}" placeholder="" required>
                <span></span>
                <br><br>

                <label for="last_name"><strong>Last Name:</strong></label>
                <input type="text" id="last_name" name="last_name" size="20" maxlength="20" pattern="[A-Za-z]+{20}" placeholder="" required>
                <span></span>
                <br><br>

                <label for="dob"><strong>Date of Birth:</strong></label>
                <input type="text" id="date_of_birth" name="date_of_birth" size="10" maxlength="10" pattern="\d{2}/\d{2}/\d{4}" placeholder="dd/mm/yyyy" required>
                <span></span>
                <br><br>
                <div class="tick-container">
                    <fieldset>
                        <legend>Gender:</legend>
                        <label for="male"><input type="radio" id="male" name="gender" value="male"> Male</label>
                        <label for="female"><input type="radio" id="female" name="gender" value="female"> Female</label>
                        <label for="other"><input type="radio" id="other" name="gender" value="other"> Other</label>
                    </fieldset>
                </div>
                <br>

                <label for="street_address"><strong>Street Address:</strong></label>
                <input type="text" id="street_address" name="street_address" size="40" maxlength="40" placeholder="" required>
                <span></span>
                <br><br>

                <label for="suburb"><strong>Suburb/Town:</strong></label>
                <input type="text" id="suburb" name="suburb" size="40" maxlength="40" placeholder="" required>
                <span></span>
                <br><br>

                <label for="state"><strong>State:</strong></label>
                <select id="state" name="state">
                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
                <br><br>

                <label for="postcode"><strong>Postcode:</strong></label>
                <input type="text" id="postcode" name="postcode" size="4" maxlength="4" pattern="\d{4}" placeholder="" required>
                <span></span>
                <br><br>

                <label for="email"><strong>Email Address:</strong></label>
                <input type="email" id="email" name="email" placeholder="" required>
                <span></span>
                <br><br>

                <label for="phone_number"><strong>Phone Number:</strong></label>
                <input type="tel" id="phone_number" name="phone_number" size="12" maxlength="12" pattern="[0-9]{8,12}" placeholder="" required>
                <span></span>
                <br><br>
                <div class="tick-container">
                    <label for="skills"><strong>Skills:</strong></label><br>
                    <input type="checkbox" id="qiskit" name="skills[]" value="qiskit">
                    <label for="qiskit">Qiskit</label>
                    <input type="checkbox" id="cirq" name="skills[]" value="cirq">
                    <label for="cirq">Cirq</label>
                    <input type="checkbox" id="quipper" name="skills[]" value="quipper">
                    <label for="quipper">Quipper</label>
                    <br>
                    <input type="checkbox" id="python" name="skills[]" value="python">
                    <label for="python">Python</label>
                    <input type="checkbox" id="tensorflow" name="skills[]" value="tensorflow">
                    <label for="tensorflow">TensorFlow</label>
                    <input type="checkbox" id="pytorch" name="skills[]" value="pytorch">
                    <label for="pytorch">PyTorch</label>
                    <br>
                    <input type="checkbox" id="other_skills" name="skills[]" value="other_skills">
                    <label for="other_skills">Other skills...</label>
                </div>
                <br><br>

                <label for="other_skills"><strong>Other Skills (if any):</strong></label>
                <br>
                <textarea id="other_skills" name="other_skills" rows="4" cols="50"></textarea>
                <br><br>

                <input type="submit" value="Submit">
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>