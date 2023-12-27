


<?php
require_once "database.php";

$errors = array(
    'fullname' => '',
    'email' => '',
    'password' => '',
    'repeat_password' => '',
);

if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    if (empty($fullName)) {
        $errors['fullname'] = "Full Name is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is not valid";
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors['password'] = "Password must meet the 8 character,/[A-Z]/,[a-z]/,/[0-9]/,/[^a-zA-Z0-9]/";
    }

    if ($password !== $passwordRepeat) {
        $errors['repeat_password'] = "Passwords do not match";
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors['email'] = "Email already exists!";
        }
    }

    if (empty($errors['fullname']) && empty($errors['email']) && empty($errors['password']) && empty($errors['repeat_password'])) {
        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordhash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'><p>You are registered successfully. <a href='login.php'>Login</a></p></div>";
        } else {
            die("Something went wrong");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h1>Registration Form</h1>

        
        <form action="index.php" method="post">
    <div class="form-group">
        <input type="text" class="form-control" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($fullName ?? ''); ?>">
    </div>
    <?php if (!empty($errors['fullname'])) {
        echo "<div class='alert alert-danger'>" . $errors['fullname'] . "</div>";
    } ?>

    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
    </div>
    <?php if (!empty($errors['email'])) {
        echo "<div class='alert alert-danger'>" . $errors['email'] . "</div>";
    } ?>

<div class="form-group">
        <div class="input-group-append">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            <i class="fa-solid fa-eye-slash eye" id="togglepassword"></i>
        </div>
    </div>
    <?php if (!empty($errors['password'])) {
        echo "<div class='alert alert-danger'>" . $errors['password'] . "</div>";
    } ?>

    <div class="form-group">
        <div class="input-group-append">
            <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            <i class="fa-solid fa-eye-slash eyeslash" id="togglerepeatpassword"></i>
        </div>
    </div>

    <?php if (!empty($errors['repeat_password'])) {
        echo "<div class='alert alert-danger'>" . $errors['repeat_password'] . "</div>";
    } ?>

    <div class="form-btn">
        <input type="submit" class="btn btn-primary" value="Register" name="submit">
    </div>
</form>

<script>
    const togglePassword = document.getElementById('togglepassword');
    const toggleRepeatPassword = document.getElementById('togglerepeatpassword');
    const password = document.getElementById('password');
    const repeatPassword = document.getElementsByName('repeat_password')[0];

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
    });

    toggleRepeatPassword.addEventListener('click', function () {
        const type = repeatPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        repeatPassword.setAttribute('type', type);
    });
</script>

<div>
    <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
</div>


        </body>
</html>
