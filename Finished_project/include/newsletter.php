<?php include "./db.php" ?>

<!-- fxns -->
<?php include "../admin/functions.php";  ?>
<?php

// echo "You gooddddddddddddddd :)";

if($_POST['email'] != "") {

	$sub_email = $_POST['email'];

	//echo $sub_email;

	$newsletterSubs_query = "INSERT INTO newsletter_requests (nletter_emails, ndate) VALUES('{$sub_email}', now()) ";
	$query = mysqli_query($conn, $newsletterSubs_query);
	confirmQuery($query);

header('Location: ../index.php');

}

?>