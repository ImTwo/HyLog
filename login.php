<?php

$notok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();

    $DATABASE_HOST = '';
    $DATABASE_USER = '';
    $DATABASE_PASS = '';
    $DATABASE_NAME = '';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
        die ('Failed to connect to the database!' . mysqli_connect_error());
    }

    if (!isset($_POST['email'], $_POST['password']) ) {
        $notok = true;
        $message = "Please fill both the email and password field!";
    }

    if ($notok == false) {
        if ($stmt = $con->prepare('SELECT id, password, username FROM accounts WHERE email = ?')) {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password, $username);
                $stmt->fetch();

                if (password_verify($_POST['password'], $password)) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $username;
                    $_SESSION['id'] = $id;
                    header('Location: index.php');
                    exit();
                } else {
                    $notok = true;
                    $message = "Incorrect email or password!";
                }
            } else {
                $notok = true;
                $message = "Incorrect email or password!";
            }
            $stmt->close();
        }
    }
}

?>

<html>
	<head>
		<meta charset="utf-8">
		<link href="./css/style.css" rel="stylesheet">
        <title>Login</title>
	</head>
    
    <body>
        <div class="container">
            <div class="header">
                <img src="./img/logo.gif" class="logo">
                <span class="ip">server.com</span>
            </div>
            <div class="login">
                <span class="title">Sign in</span><?php
                if ($notok == true) {
                echo '<div class="warning">' . $message . '</div>';}?>
                <form id="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <input class="cred" name="email" type="text" placeholder="Email address">
                    <input class="cred" name="password" type="password" placeholder="Password"><br><br>
                    <input type="submit">
                </form>
            </div>
            <div class="footer">
                <span class="link"><a href="./register.php">Create an account</a></span>
                <span class="link"><a href="#">Can't sign in?</a></span>
            </div>
        </div>
    </body>
</html>