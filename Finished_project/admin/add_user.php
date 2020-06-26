<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";

include "./include/add_ons.php";

?>

<?php


//AUTHENTICATION

//instead of using isset($_POST['submit']) we should check if the server has a post request
 if($_SERVER['REQUEST_METHOD'] == "POST") {

    //checking if its working
    //echo "It's working";

    //extracting form values
    //trim fxn is to take the white spaces out
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $user_fname = mysqli_real_escape_string($conn, trim($_POST['user_fname']));
    $user_lname = mysqli_real_escape_string($conn, trim($_POST['user_lname']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $password2 = mysqli_real_escape_string($conn, trim($_POST['password2']));
    $addedby = $_SESSION['username'];

    //creating an associative arrays
    //an error array containing some errors
    $error = [
        'username' => '',
        'email'    => '',
        'password' => '',
        'password2' => '',
        'user_fname' => '',
        'user_lname' => '',
        'profile_image' => ''
    ];


    /*** VALIDATION ***/

    if($password == $password2) {

        $error['password'] = 'Password are not identical';
        $error['password2'] = 'Password are not identical';

    }

    //strlen() is to check the length of a string
    if(strlen($username) <4) {

        $error['username'] = 'Username needs to be longer';

    }

    if($username == '') {

        $error['username'] = 'Username cannot be empty';

    }

    if(username_exists($username)) {

        $error['username'] = 'Username already exists, pink another one please';

    }

    if($user_fname == '') {

        $error['user_fname'] = 'Firstname cannot be empty';

    }

    if($user_lname == '') {

        $error['user_lname'] = 'Lastname cannot be empty';

    }

    if($email == '') {

        $error['email'] = 'Email cannot be empty';

    }

    //checking if the email provided is already in the user table in database
    if(user_email_exists($email)) {

        $error['email'] = 'Email already exists, <a href="index.php">Please Login</a>';

    }

    if($password == '') {

        $error['password'] = 'Password cannot be empty';

    }


    //looping through the error arrays to figure out if there's an error
    //using for each
    foreach ($error as $key => $value) {

        if(empty($value)) {

            //cleaning up our empty fields
            //unsetting the key of that variable
            unset($error[$key]);

        }
        
    } //end foreach 



    if(empty($error)){

        register_user($username, $email, $password);

        $data['message'] = $username;

        $pusher->trigger('notifications', 'new_user', $data);

        login_user($username, $password);


    }

    $profile_image = '';
    //for images we need the super glbal of $_FILES
    //the ['image'] there is the name of the input n we are have the name of the image here ['name']
    $profile_image = escape($_FILES['image']['name']);
    $profile_image_temp = escape($_FILES['image']['tmp_name']);
    move_uploaded_file($profile_image_temp, "./uploads/images/$profile_image");




     $user_role = "admin";

 //encrypting password
    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12 ));
     
    //query to add post
    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_pass, user_image, addedby, token) ";
    //not using a single quote '' in {$post_category_id} since it will be a number not a string as the others
    //for date we are not sending a value but we are sending a function
    $query .= "VALUES('{$user_fname}','{$user_lname}','{$user_role}','{$username}','{$email}','{$password}', '{$profile_image}', '{$addedby}', '') ";
    
    //sending the query to the database
    $create_user_query = mysqli_query($conn, $query);
    
    //adding a condition when $create_post_query ie query doesn't work or checking the query id working
    confirmQuery($create_user_query);

    echo "User Created: " . " " . "<a href='users.php'>View Users</a>";


 } 




?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<div>
				<h2>Add User</h2>
			</div>

			<form method="POST" action="add_user.php" enctype="multipart/form-data">
	           <div class="form-group">
	              <label for="username">Username</label>
	              <input type="text" name="username" id="username"  class="form-control" placeholder="Enter Username">
	              <!-- to display error message -->
                    <p><?php echo isset($error['username']) ? $error['username'] : '' ?></p>
	            </div>
	            <div class="form-group">
	              <label for="user_fname">Firstname</label>
	              <input type="text" name="user_fname" id="user_fname"  class="form-control" placeholder="Enter First Name">
	              <!-- to display error message -->
                   <p><?php echo isset($error['user_fname']) ? $error['user_fname'] : '' ?></p>
	            </div>
	            <div class="form-group">
	              <label for="user_lname">Lastname</label>
	              <input type="text" name="user_lname" id="user_lname"  class="form-control" placeholder="Enter Lastname">
	              <!-- to display error message -->
                   <p><?php echo isset($error['user_lname']) ? $error['user_lname'] : '' ?></p>
	            </div>
	            <div class="form-group">
			        <label for="profile_image">Profile Picture</label>
			        <input type="file" class="form-control" id="image" name="image">
			    </div>
			    <!-- to display error message -->
                   <p><?php echo isset($error['profile_image']) ? $error['profile_image'] : '' ?></p>
	            <div class="form-group">
	              <label for="email">Email</label>
	              <input type="email" name="email" id="email" class="form-control" placeholder="Your Email">
	            </div>
	            <!-- to display error message -->
                   <p><?php echo isset($error['email']) ? $error['email'] : '' ?></p>
	            <div class="form-group">
	              <label for="password">Password</label>
	              <input type="password" name="password" id="key" class="form-control" placeholder="Password">
	            </div>
	            <!-- to display error message -->
                   <p><?php echo isset($error['password']) ? $error['password'] : '' ?></p>
	            <div class="form-group">
	              <label for="password2">Confirm Password</label>
	              <input type="password" name="password2" class="form-control" placeholder=" Re-enter Password">
	            </div>
	            <!-- to display error message -->
                   <p><?php echo isset($error['password2']) ? $error['password2'] : '' ?></p>

	             <div class="modal-footer">
			          <input type="submit" name="register" id="btn-login" class="btn btn-success" value="Add User">
			      </div>
	           
	         </form>


		</div>
	</div>
</div>


<!-- FOOTER -->
   <?php include "./include/admin_footer.php"; ?>

