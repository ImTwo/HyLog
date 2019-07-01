<?php

$notok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $notok = true;
        $message = "Email is not valid";
    }

    if (!ctype_alnum($_POST['username'])) {
        $notok = true;
        $message = "Username may only contain letters and numbers";
    }

    if (strlen($_POST['username']) > 15 || strlen($_POST['username']) < 3) {
        $notok = true;
        $message = "Password must be between 3 and 15 characters long, and may only include letters and numbers";
    }

    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        $notok = true;
        $message = "Password must be between 5 and 20 characters long!";
    }

    if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
        $notok = true;
        $message = "Please complete the registration form";
    }

    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        $notok = true;
        $message = "Please complete the registration form";
    }

    if ($notok == false) {

        $DATABASE_HOST = '';
        $DATABASE_USER = '';
        $DATABASE_PASS = '';
        $DATABASE_NAME = '';

        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (mysqli_connect_errno()) {
            die ('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $notok = true;
                $message = "Username exists, please choose another";
            }
            $stmt->close();
        } else {
            $notok = true;
            $message = "Something went wrong!";
        }

        if ($notok == false) {
            if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
                $stmt->bind_param('s', $_POST['email']);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $notok = true;
                    $message = "An account with the email address already exists";
                } else {
                    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
                        $stmt->execute();
                        die( 'You have successfully registered, you can now login!');
                    } else {
                        $notok = true;
                        $message = "Something went wrong!";
                    }
                }
                $stmt->close();
            } else {
                $notok = true;
                $message = "Something went wrong!";
            }
        }
        $con->close();
    }
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<link href="./css/style.css" rel="stylesheet">
        <title>Register</title>
	</head>
    
    <body>
        <div class="container">
            <div class="header">
                <img src="./img/logo.gif" class="logo">
                <span class="ip">server.com</span>
            </div>
            <div class="login">
                <span class="title">Register</span><?php
                if ($notok == true) {
                echo '<div class="warning">' . $message . '</div>';}?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <input class="cred" name="username" type="text" placeholder="Username">
                    <input class="cred" name="email" type="text" placeholder="Email">
                    <input class="cred" name="password" type="password" placeholder="Password">
                    <input id="tac" type="checkbox" name="tac" value="tac" required> <label class="me" for="tac"><b>I agree to Terms & Conditions</b></label><br><br>
                    <input type="submit" value="Submit">
                </form>
            </div>
            <div class="footer">
                <span class="link"><a href="./login.php">Already have an account? Sign in</a></span>
                <span class="link"><a href="#">Can't sign in?</a></span>
            </div>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</html>