<?php
require('shared/header.php');

// check if the user is already logged in, redirect to home page
if (isset($_SESSION['user'])) {
    header('location:index.php');
    exit();
}

// set page title
$title = 'Register';

// set variables for form inputs
$username = $email = $password = $confirmPassword = '';

// set variables for error messages
$usernameError = $emailError = $passwordError = $confirmPasswordError = '';

// if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // validate form data
    $isValid = true;

    if (empty($username)) {
        $usernameError = 'Username is required';
        $isValid = false;
    }

    if (empty($email)) {
        $emailError = 'Email is required';
        $isValid = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Invalid email format';
        $isValid = false;
    }

    if (empty($password)) {
        $passwordError = 'Password is required';
        $isValid = false;
    } else if (strlen($password) < 8) {
        $passwordError = 'Password must be at least 8 characters';
        $isValid = false;
    }

    if (empty($confirmPassword)) {
        $confirmPasswordError = 'Please confirm password';
        $isValid = false;
    } else if ($password != $confirmPassword) {
        $confirmPasswordError = 'Passwords do not match';
        $isValid = false;
    }

    // if form data is valid
    if ($isValid) {
        // connect to database
        require('shared/db.php');

        // check if username or email already exist
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $cmd = $db->prepare($sql);
        $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
        $cmd->bindParam(':email', $email, PDO::PARAM_STR, 100);
        $cmd->execute();
        $user = $cmd->fetch();

        // if username or email already exist
        if (!empty($user)) {
            if ($user['username'] == $username) {
                $usernameError = 'Username already taken';
            } else {
                $emailError = 'Email already registered';
            }
        } else {
            // insert user data into database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $cmd = $db->prepare($sql);
            $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
            $cmd->bindParam(':email', $email, PDO::PARAM_STR, 100);
            $cmd->bindParam(':password', $hashedPassword, PDO::PARAM_STR, 255);
            $cmd->execute();

            // set session variable to indicate user is logged in
            $_SESSION['user'] = $username;

            // redirect to home page
            header('location:index.php');
            exit();
        }
    }
}
?>
<main>
    <h1>Register</h1>
    <form method="post">
        <fieldset>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required />
</fieldset>
<fieldset>
<label for="password">Password:</label>
<input type="password" name="password" id="password" required />
</fieldset>
<fieldset>
<label for="confirmPassword">Confirm Password:</label>
<input type="password" name="confirmPassword" id="confirmPassword" required />
</fieldset>
<fieldset>
<label for="email">Email:</label>
<input type="email" name="email" id="email" required />
</fieldset>
<fieldset>
<label for="firstName">First Name:</label>
<input type="text" name="firstName" id="firstName" required />
</fieldset>
<fieldset>
<label for="lastName">Last Name:</label>
<input type="text" name="lastName" id="lastName" required />
</fieldset>
<button>Register</button>
</form>

</main>
<?php require('shared/footer.php'); ?>
