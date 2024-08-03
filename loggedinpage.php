<?php

    session_start();
    $notesContent = "";
    if(array_key_exists("id", $_COOKIE)){
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(array_key_exists("id",$_SESSION)){
        echo "<p>Logged In! <a href='index.php?logout=1'>Log Out</a></p>";

        include("./include/connection.php");
        $query = "SELECT notes FROM `diary` WHERE id =".mysqli_real_escape_string($link,$_SESSION['id'])." LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link,$query));
        $notesContent = $row['notes'];


    } else {
        header("Location: index.php");
    }

include("./include/header.php");

?>

<div class="container-fluid">
    <textarea class="form-control" id="diary" ><?php echo $notesContent; ?></textarea>
</div>


<?php
include("./include/footer.php");
 
?>

