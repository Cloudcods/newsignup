
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="nap.css">

</head>
<body>
    
<div class="wrapper">
<?php
session_start();
      if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        require_once "database.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = "yes";
                header("Location: newdashboard.php");
                die();
            } else {
                $_SESSION['error'] = "Password does not match";
            }
        } else {
            $_SESSION['error'] = "Email does not match";
        }
    }
    
    
    if (isset($_SESSION['error'])) {
        $errorMessage = $_SESSION['error'];
        unset($_SESSION['error']);
    } else {
        $errorMessage = '';
    }
    ?>
    <div class="error-message">
    <?php echo $errorMessage; ?>
</div>
    <form action="login.php" method="post">
      <h1>Login</h1>
      <div class="input-box">
        <input type="email" name="email" required>
        <i class="fa-solid fa-envelope-circle-check"></i>
      </div>
      <div class="input-box">
      <div class="input-group-append">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class="fa-regular fa-eye-slash eyelog" onclick="togglePasswordVisibility()"></i>
      </div>
    </div>
      
      <div class="remember-forgot">
        <label><input type="checkbox">Remember Me</label>
        <a href="index.php">Forgot Password</a>
      </div>
      <div class="form-btn">
        <input type="submit" class="btn btn-primary" value="login" name="login">
    </div>
  
      <div class="register-link">
        <p>Dont have an account? <a href="index.php">Register</a></p>
      </div>
    </form>
    <script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("password");
      var eyeIcon = document.querySelector(".eyelog");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      }
    }
  </script>
    
</body>
</html>