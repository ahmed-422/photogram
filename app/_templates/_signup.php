<?php

$signup = false;
if (isset($_POST['username']) and !empty($_POST['username']) and isset($_POST['password']) and !empty($_POST['password']) and isset($_POST['email_address']) and !empty($_POST['email_address']) and isset($_POST['phone']) and !empty($_POST['phone'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email_address'];
    $phone = $_POST['phone'];
    $error = User::signup($username, $password, $email, $phone);
    $signup =true;
}

if ($signup) {
    if (!$error) {
        ?>
<main class="container">
	<div class="bg-light p-5 rounded mt-3">
		<h1>Signup Success</h1>
		<p class="lead">Now you can login from <a href="login.php">here</a></p>
	</div>
</main>
<?php
    } else {
        ?>
<main class="container">
	<div class="bg-light p-5 rounded mt-3">
		<h1>Signup Failed</h1>
		<p class="lead">Something went wrong :( ,<?=$error?>
		</p>
	</div>
</main>
<?php
    }
} else {
    ?>

<main class="form-signup w-100 m-auto">
	<form method="post" action="signup.php">
		<img class="mb-4" src="https://cdn-icons-png.flaticon.com/256/7144/7144851.png" alt="" width="100" height="100"
			style="position:relative; left:100px;">
		<h1 class="h3 mb-3 fw-normal">Signup here</h1>

		<div class="form-floating">
			<input name="username" type="text" class="form-control" id="floatingInputUsername" placeholder="username">
			<label for="floatingInputUsername">Username</label>
		</div>
		<div class="form-floating">
			<input name="phone" type="text" class="form-control" id="floatingInput" placeholder="phone">
			<label for="floatingInput">Phone</label>
		</div>

		<div class="form-floating">
			<input name="email_address" type="email" class="form-control" id="floatingInput"
				placeholder="name@example.com">
			<label for="floatingInput">Email address</label>
		</div>
		<div class="form-floating">
			<input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
			<label for="floatingPassword">Password</label>
		</div>

		<button class="glow-on-hover" type="submit">Sign Up</button>

	</form>
</main>
<?php
}
