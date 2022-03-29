<?php
    
session_start();

require '../core/conn.php';
require '../core/queries.php';

$username = $email = $password1 = $password2 = "";
$errors = array(); 
$success_msgs = array();

if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {

    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password1 = $conn->real_escape_string($_POST['password1']);
    $password2 = $conn->real_escape_string($_POST['password2']);

    if (empty($username)) {
        array_push($errors, "Username is a required field");
    } 

    if (empty($email)) {
        array_push($errors, "Email is a required field");
    }  

    if ($password1 != $password2) {
        array_push($errors, "Your passwords do not match");
    }

    $exist = checkEmailExists($email);
    $user = mysqli_fetch_assoc($exist);
    if ($user) {
        if ($user['email'] === $email) {
            array_push($errors, "This email address is already registered");
        }
    }

    if (count($errors) == 0) {
        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

        if ($conn->query("INSERT INTO users (username, password, email) VALUES('$username', '$hashed_password', '$email')")) {
            array_push($success_msgs, "Your account has been created successfully!");
        } else { 
            echo $conn->error; 
        }
    }

  
    if (count($errors) > 0) {
        echo "<div class='display-error-div'>";
        foreach ($errors as $error) {
            echo "<p>" . $error . "</p>";
        }
        echo "</div>";
    }

    if (count($success_msgs) > 0) {
        echo "<div class='display-success-div'>";
        foreach ($success_msgs as $msg) {
            echo "<p>" . $msg . "</p>";
        }
        echo "</div>";
        header("refresh:3;url=../home");
    }
}

?>

<html>
<head>
    <link rel='stylesheet' href='../css/styles.css'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body>
    <div id="register-container">
        <h3><strong>Create a new account</strong></h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off">
            <input type="text" name="username" placeholder="Username"><br />
            <input type="email" name="email" placeholder="Email Address"><br />

            <input type="password" name="password1" placeholder="Password"><br />
            <input type="password" name="password2" placeholder="Re-Type Password"><br />

            <button type="submit">Register</button>
        </form>
        <div class="registration">
            <h3>Already have an account? click <a href="login.php">here</a> to log in</h3>
        </div>
    </div>
</body>
</html>