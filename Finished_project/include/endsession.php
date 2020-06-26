<?php ob_start(); ?> 
<!--turning SESSION ON, since we need to start session before using it-->
<!--it telling our server to prepare the session for us-->
<?php session_start(); ?>


<?php

//cancelling the user session by assiging a boolean value of NULL to the user sessions

$_SESSION['username'] = null;
$_SESSION['firstname'] = null;
$_SESSION['lastname'] = null;
$_SESSION['user_role'] = null;

//after cancling the user session we redirect the user to index.php, ie home page
header("Location: ../admin/login.php");

?>
