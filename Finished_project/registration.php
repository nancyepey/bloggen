<?php  include "include/db.php"; ?>
<?php  include "include/header.php"; ?>
<?php include "admin/functions.php"; ?>

 <?php
//requiring this file to use all the classes
//we wan to use pusher
 require 'vendor/autoload.php';


if(isset($_GET['lang']) && !empty($_GET['lang'])) {

    $_SESSION['lang'] = $_GET['lang'];

    if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']) {
        //echo "<script type='text/javascript'> Location.reload(); </script>";
    } 

}

if(isset($_SESSION['lang'])) {
    include "include/languages/".$_SESSION['lang'].".php";
}else {
    include "include/languages/en.php";
}


//PUSHER
 //going to the file
// $dotenv = \Dotenv\Dotenv(__DIR__);
// $dotenv->load();
 
//  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();


 //cluster
 $options = array(
    'cluster' => 'eu',
    'useTLS'  => true
  );

 //creating a new instance of pusher
 //first parameter is the key, second is the secret code, the third thing is the app id and the forth thing is the options
 //new Pusher\Pusher('key', 'secret', 'app_id', 'options');
 //we have to assign it to a variable
 //OLD WAY with no protection
 //putting this value directly without protecting credentials
//  $pusher = new Pusher\Pusher('9af76de09290caaa14ca', '093a7526137457c8f26a', '993663', $options);
//NEW WAY with protection ie protecting credential using vlucas
$pusher = new Pusher\Pusher(getenv('APP_KEY'), getenv('APP_SECRET'), getenv('APP_ID'), $options);




//AUTHENTICATION

//instead of using isset($_POST['submit']) we should check if the server has a post request
 if($_SERVER['REQUEST_METHOD'] == "POST") {

    //checking if its working
    //echo "It's working";

    //extracting form values
    //trim fxn is to take the white spaces out
    $username = mysqli_real_escape_string($connection, trim($_POST['username']));
    $email    = mysqli_real_escape_string($connection, trim($_POST['email']));
    $password = mysqli_real_escape_string($connection, trim($_POST['password']));

    //creating an associative arrays
    //an error array containing some errors
    $error = [
        'username' => '',
        'email'    => '',
        'password' => ''
    ];


    /*** VALIDATION ***/
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


 } 


 ?>


    <!-- Navigation -->
    
    <?php  include "include/nav.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">

        <div class="row">
             <div class="col-md-4 col-md-offset-4"></div>
            <div class="col-md-4 col-md-offset-4"></div>
            <div class="col-md-4">
                <!-- for the language changer or switcher -->
        <!-- creating a form with selection opetion for the different lang choices  -->
        <form method="get" class="navbar-form navbar-right" action="" id="language_form">
            <div class="form-group">
                <select name="lang" class="form-control" onchange="changeLanguage()" >
                 
                    <option value='en' <?php if(isset($_GET['lang']) && ($_GET['lang'] =='en')) { echo "selected"; } ?>>English</option>
                    <option value='es' <?php if(isset($_GET['lang']) && ($_GET['lang']=='es')) { echo "selected"; } ?>>Spanish</option>
                </select>
            </div>
        </form>
    
            </div>
        </div>
        <div class="row">

        </div>
        <section id="login">
    <div class="container">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 col-lg-offset-3">
                <div class="form-wrap">
                <!-- <h1>Register</h1> -->
                <h1 class="text-center"><?php echo _REGISTER; ?></h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">

                        <!-- to display a message -->
                        <!-- <h6 class="text-center"><?php //echo $message; ?></h6> -->

                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _USERNAME; ?>"

                            autocomplete="on" value="<?php echo isset($username) ? $username : '' ?>">

                            <!-- to display error message -->
                            <p><?php echo isset($error['username']) ? $error['username'] : '' ?></p>

                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _EMAIL; ?>"

                            autocomplete="on" value="<?php echo isset($email) ? $email : '' ?>">
                            <!-- this echo isset($email) ? $email : '' is a shorter way to write if statements, this  $email happen when the condition ie isset($email) is true and this  '' happens when the condition is false   -->

                            <p><?php echo isset($error['email']) ? $error['email'] : '' ?></p>

                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD; ?>">

                            <!-- to display error message -->
                            <p><?php echo isset($error['password']) ? $error['password'] : '' ?></p>

                        </div>
                
                        <input type="submit" name="register" id="btn-login" class="btn btn-primary btn-lg btn-block" value="<?php echo _REGISTER; ?>">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

    </div>  



<script>

    function changeLanguage() {

        //console.log("IT's Working");

        document.getElementById('language_form').submit();

    }




</script>


 <!-- jQuery first, then Bootstrap JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/myjs.js"></script>
