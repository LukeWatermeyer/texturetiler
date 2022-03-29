<?php

session_start();
include '../core/conn.php';
include '../core/queries.php';
$errors = array(); 

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    $email = $conn->real_escape_string($_POST['email']);
    $check_password = getHash($email);

    if ($check_password->num_rows > 0) {
        $user_details = mysqli_fetch_assoc($check_password);
        $hash = $user_details['password'];
        $username = $user_details['username'];
        $user_id = $user_details['id'];

       if (password_verify($_POST['password'], $hash)) { //For some reason this is failing -- It was the varchar length :facepalm:
            $_SESSION['valid'] = 1;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            
            header('location: ../home.php');
            exit();
        } else {
            array_push($errors, "The password you entered is incorrect");
        }

    } else {
        array_push($errors, "The email address you entered is not registered");
    }
    
    if (count($errors) > 0) {
      echo "<div class='display-error-div'>";
        foreach ($errors as $error) {
          echo "<p>" . $error . "</p>";
        }
      echo "</div>";
    }
}
?>
    
<html>
    <head>
        <link rel='stylesheet' href='../css/styles.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body>
        <div id="login-container">
            <h3><strong>Log in</strong></h3>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" autocomplete="off">
                    <input type="text" name="email" placeholder="Email Address" required autofocus><br />
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <br />
                    <button type="submit">Login</button>
                </form>
            <div>
                <h3>Or click <a href="register">here</a> to sign up</h3>
            </div>
        </div>
    </body>
</html>