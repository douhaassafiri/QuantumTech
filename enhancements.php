<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="QuantumTech">
    <meta name="keywords" content="IT Jobs">
    <meta name="author" content="Douha Assafiri, Narun Pich, Sok Mekno Dy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumTech | Enhancements</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="images/quantumtech.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <br>
        <h1>Enhancements</h1>
        <p>Our team has implemented a few enhancements using CSS to make your experience better.</p>
        <br>
        <hr>
        <br>
        <div class="grid-container">
            <div class="grid-item">
                <p>
                    We applied different animation styles to the buttons.
                    <br>
                    We used the :hover pseudo-class to make this work.
                </p>
                <br>
                <p>This button was used for the nav: </p>
                <a class="btn hover-underline-animation" href="index.php">Hover Me!</a>
                <br>
                <p>Source: <a class="source" href="https://www.30secondsofcode.org/css/s/hover-underline-animation/">30 Seconds Of
                        Code</a>
                    <br><br>
                <p>This button was used for the index and jobs page: </p>
                <a class="btn btn-job btn-cont" href="jobs.php">Hover Me!</a>
                <br>
            </div>
            <div class="grid-item">
                <p>
                    Our forms page allows live updates on whether the user input is valid (fits the pattern) or not.
                    <br>
                    This is achieved through using span in HTML paired with CSS to change the position.
                </p>
                <br>
                <label for="try_me"><strong>This form only takes letters.<br>Try different inputs:</strong></label>
                <input type="text" id="try_me" name="Try_me" size="25" pattern="[A-Za-z]+" placeholder="Try Me!"
                    required>
                <span></span>
                <br>
                <p>Source: <a class="source" href="https://codepen.io/hane-smitter/pen/wvQpvZL">CodePen</a></p>
            </div>
            <div class="grid-item">
                <p>We used the @media query with (max-width: 1024px) to adapt the website to tablet size.</p><br>
                <img src="images/ipad.png" class="screenshot-tiny" alt="ipad screen">
            </div>
            <div class="grid-item">
                <p>We used the @media query with (max-width: 768px) to adapt the website to phone size.</p><br>
                <img src="images/iphone.png" class="screenshot-tiny" alt="iphone screen">
            </div>
            <div class="grid-item">
                <p>This is what our website looks like if you have light mode enabled (default).
                    <br>The background color's hex code is #eaeaea
                </p><br>
                <img src="images/lightmode.png" class="screenshot" alt="light mode">
            </div>
            <div class="grid-item">
                <p>We used the @media query with (prefers-color-scheme: dark) to make the webpage change if you have
                    dark mode enabled.
                    <br>The background color's hex code is #222222
                </p><br>
                <img src="images/darkmode.png" class="screenshot" alt="dark mode">
            </div>
            <?php include 'phpenhancements.php'; ?>
        </div>
        <br><br><br><br>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>
