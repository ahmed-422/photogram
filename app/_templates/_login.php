<?php
$username = $_POST['email_address'];
$password = $_POST['password'];
//print($username);
$result = User::login($username, $password);

if ($result) {
    ?>
<main class="container">
	<div class="bg-light p-5 rounded mt-3">
		<h1>Login Success</h1>
		<p class="lead">basic login with html forms</p>
	</div>
</main>
<?php
} else {
    ?>

<!-- CHECK ACTION IN FORM BEFORE TESTING LOGIN -->

<main class="form-signin w-100 m-auto">
	<form method="post" action="logintest.php">
		<img class="mb-4" src="https://cdn-icons-png.flaticon.com/256/7144/7144851.png" alt="" width="100" height="100"
			style="position:relative; left:100px;">
		<h1 class="h3 mb-3 fw-normal">Please sign in</h1>

		<div class="form-floating">
			<input name="email_address" type="text" class="form-control" id="floatingInput" placeholder="email">
			<label for="floatingInput">Email address or Username</label>
		</div>
		<div class="form-floating">
			<input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
			<label for="floatingPassword">Password</label>
		</div>

		<input name="fingerprint" type="hidden" class="form-control" id="fingerprint">


		<div class="checkbox mb-3">
			<label>
				<input type="checkbox" value="remember-me"> Remember me
			</label>
		</div>
		<button class="glow-on-hover" type="submit">Sign in</button>

	</form>
</main>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
	crossorigin="anonymous"></script>
<script>
	// Initialize the agent at application startup.
	const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
		.then(FingerprintJS => FingerprintJS.load())

	// Get the visitor identifier when you need it.
	fpPromise
		.then(fp => fp.get())
		.then(result => {
			// This is the visitor identifier:
			const visitorId = result.visitorId
			console.log(visitorId)
			$("#fingerprint").val(visitorId);
		})
</script>

<?php
}

// button class    w-100 btn btn-lg btn-primary
