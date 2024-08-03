<?php

session_start();

$error = "";

if (array_key_exists("logout", $_GET)) {
    unset($_SESSION);
    setcookie("id", "", time() - 60 * 60);
    $_COOKIE["id"] = "";
} else if ((array_key_exists("id", $_SESSION) and $_SESSION['id']) or (array_key_exists("id", $_COOKIE) and $_COOKIE['ID'])) {
    header("Location: loggedinpage.php");
}

if (array_key_exists("submit", $_POST)) {

    include("./include/connection.php");

    if (!$_POST['email']) {
        $error .= "An email address is requird<br>";
    }
    if (!$_POST['password']) {
        $error .= "A password is required<br>";
    }
    if ($error != "") {
        $error = "<p>There were error(s) in your form:</p>" . $error;
    } else {

        if ($_POST['signUp'] == 1) {

            $query = "SELECT `id` from diary WHERE email ='" . mysqli_real_escape_string($link, $_POST['email']) . "' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $error = "That email address is already taken";
            } else {
                $query = "INSERT INTO `diary` (`email`, `password`) VALUES ('" . mysqli_real_escape_string($link, $_POST['email']) . "','" . mysqli_real_escape_string($link, $_POST['password']) . "')";
                if (!mysqli_query($link, $query)) {
                    $error = "<p>Could not sign you up -please try again later. </p> ";
                } else {
                    $query = "UPDATE `diary` SET `password` = '" . md5(md5(mysqli_insert_id($link)) . $_POST['password']) . "' WHERE id = " . mysqli_insert_id($link) . " LIMIT 1";
                    mysqli_query($link, $query);
                    $_SESSION['id'] = mysqli_insert_id($link);
                    if ($_POST['stayLoggedIn'] == '1') {
                        setcookie("id", mysqli_insert_id($link), time() + 60 * 60 * 24 * 365);
                    }
                    // echo "Sign up Successful!";
                    header("Location: loggedinpage.php");
                }
            }
        } else {
            // echo "logging in...";
            $query = "SELECT *FROM `diary` WHERE email ='" . mysqli_real_escape_string($link, $_POST['email']) . "'";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_array($result);
            if (isset($row)) {
                $hashedPassword = md5(md5($row['id']) . $_POST['password']);
                if ($hashedPassword == $row['password']) {
                    $_SESSION['id'] = $row['id'];
                    if ($_POST['stayLoggedIn'] == '1') {
                        setcookie("id", $row['id'], time() + 60 * 60 * 24 * 365);
                    }
                    header("Location: loggedinpage.php");
                } else {
                    $error = "email/password combination could not be found";
                }
            } else {
                $error = "email/password combination could not be found";
            }
        }
    }
}



?>

<?php include("./include/header.php"); ?>
<div class="container">
    <h1>NoteBook</h1>
    <p><strong>Manage Notes Securely</strong></p>
    <div id="error"><?php echo '<div class="alert alert-danger" role="alert">'.$error.'</div>' ?></div>
        <form method="post" id="signUpForm">
            <div class="mb-3">

                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </div>
            <div clas="mb-3">

                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
                <label class="form-check-label">Stay logged in</label>
            </div>
            <input type="hidden" name="signUp" value="1">
            <input class="btn btn-primary" type="submit" name="submit" value="Sign Up!">

            <p><a class="toggleForm">Log In</a></p>
        </form>

        <!-- <form method="post">
            <input type="email" name="email" placeholder="Your Email">
            <input type="password" name="password" placeholder="Password">
            <input type="checkbox" name="stayLoggedIn" value=1>
            <input type="hidden" name="signUp" value="0">
            <input type="submit" name="submit" value="Log In!">

        </form> -->

        <form method="post" id="logInForm">
            <div class="mb-3">

                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </div>
            <div clas="mb-3">

                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
                <label class="form-check-label">Stay logged in</label>
            </div>
            <input type="hidden" name="signUp" value="0">
            <input class="btn btn-primary" type="submit" name="submit" value="Log In!">

            <p><a class="toggleForm">Sign Up</a></p>
        </form>

    </div>
  <?php include("./include/footer.php"); ?>