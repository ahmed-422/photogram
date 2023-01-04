<?php

include 'libs/load.php';
?>
<!doctype html>
<html lang="en">

<?load_template('_head');?>
<?php
if (isset($_GET['logout'])) {
    if (Session::isset("session_token")) {
        $session = new UserSession(Session::get("session_token"));
        if ($session->removeSession()) {
            echo "<h3> Pervious Session is removing from db </h3>";
        } else {
            echo "<h3>Pervious Session not removing from db </h3>";
        }
    }
    Session::destroy();
    die("Session destroyed, <a href='test.php'>Login Again</a>");
}

/*
1. Check if session_token in PHP session is available
2. If yes, construct UserSession and see if its successful.
3. Check if the session is valid one
4. If valid, print "Session validated"
5. Else, print "Invalid Session" and ask user to login.
*/

if (Session::isset("session_token")) {
    print($_SESSION['session_token']);
    print("in sess");

    if (UserSession::authorize(Session::get("session_token"))) {
        echo "<h1>Session Login, WELCOME $user </h1>";
    } else {
        $session = new UserSession(Session::get("session_token"));
        print($session->getFingerprint());
        $session->removeSession();
        Session::destroy();
        die("<h1>Invalid Session, <a href='test.php'>Login Again</a></h1>");
    }
} else {
    ?>


<body>

	<?load_template('_header');?>


	<main>

		<?load_template('_login');?>

	</main>

	<?load_template('_footer');?>



	<?php
     $pass = isset($_POST['password']) ? $_POST['password'] : '';
    $user = isset($_POST['email_address']) ? $_POST['email_address'] : '';
    if (!$pass || !$user) {
        die("<h1>Password or Username is Empty</h1>");
    }
    if (UserSession::authenticate($user, $pass)) {
        echo "<h1>New LOGIN Success,  WELCOME $user</h1>";
    } else {
        echo "<h1>New Login Failed! $user</h1>";
    }
}

echo <<<EOL
<br><br><a href="test.php?logout">Logout</a>
EOL;

?>




	<script src="/app/assets/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>