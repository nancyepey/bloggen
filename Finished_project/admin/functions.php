<?php


/***** HELPER FUNCTIONS *****/


//before putting our cms online we need to protect ourself from sql injection
//creating a function to eescape all our values and protect ourself from sql injection
//we will pass in a string
function escape($string) {

    //getting our connection to get access to db
    global $conn;

    //using a build in fxn from php ie mysqli_real_escape_string()
    //we can trim the string by using the function trim() and/or strip tags fxn to remove any hmtl tags too
    //we return it
    //mysqli_real_escape_string($connection, trim(strip_tags($string)));

    return mysqli_real_escape_string($conn, trim($string));

}

//adding a condition when $create_post_query ie query doesn't work or checking the query id working
function confirmQuery($result) {
    //making our connection variable global
    global $conn;
    
    if(!$result) {
        
        die("QUERY FAILED ".mysqli_error($conn));
        
    }
}

function query($query) {
    //getting the connection to db
    global $conn;
    //sending the query into the database
    $result = mysqli_query($conn, $query);
    confirmQuery($result);
    return $result;
}

//to get post likes
function getPostLikes($post_id) {
    $result = query("SELECT * FROM likes WHERE post_id={$post_id}");
    confirmQuery($result);
    echo mysqli_num_rows($result);
}

//function to get all post user comments
function get_all_post_comments() {
    //joining tables
    return query("SELECT * FROM posts
        INNER JOIN comments ON posts.post_id = comments.comment_post_id");

}

//function to get all post user comments
function get_all_post_likes() {
    //joining tables
    return query("SELECT * FROM posts
        INNER JOIN likes ON posts.post_id = likes.post_id");

}

//function to get specific post comments
function get_post_comments($post_id) {
    //joining tables
    return query("SELECT * FROM posts
        INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE posts.post_id={$post_id}");

}

//function to get specific post likes
function get_post_likes($post_id) {
    //joining tables
    return query("SELECT * FROM posts
        INNER JOIN likes ON posts.post_id = likes.post_id WHERE posts.post_id={$post_id}");

}


//function to count the number of rows
function count_records($result) {
    return mysqli_num_rows($result);
}


function checkIfUserIsLoggedInAndRedirect($redirectLocation=null) {

    //using the isLoggedIn fxn to check if user is logged in
    if(isLoggedIn()) {
        //redirecting logged in user
        redirect($redirectLocation);
    }

}

//this function is to redirect user to any location set in
function redirect($location) {

    // return header("Location:" . $location);
    //not using return
    header("Location:" . $location);
    //we will just exist this
    exist;

}

//function to check if username already exist to makesure two users shouldn't have the same username
function username_exists($username) {

    global $conn;

    //query to get username from users
    //selecting the user
    $query = "SELECT username FROM users WHERE username = '$username'";
    //sending query in
    $result = mysqli_query($conn, $query);
    //checking if query result is good
    confirmQuery($result);

    //get how many rows we get from query using fxn mysqli_num_rows()
    //checking if the rows in db is bigger than zero its means we have found something
    if(mysqli_num_rows($result) > 0) {

        return true;

    }else {
        return false;
    }

}

//function to check if email already exist to makesure two users shouldn't have the same email
function user_email_exists($email) {

    global $conn;

    //query to get email from users
    //selecting the user email
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    //sending query in
    $result = mysqli_query($conn, $query);
    //checking if query result is good
    confirmQuery($result);

    //get how many rows we get from query using fxn mysqli_num_rows()
    //checking if the rows in db is bigger than zero its means we have found something
    if(mysqli_num_rows($result) > 0) {

        return true;

    }else {
        return false;
    }

}

/***** END OF HELPER FUNCTIONS *****/



// ====== USER SPECIFIC HELPER FUNCTION ====== //
 
//function to get all user posts
function get_all_user_posts() {
    return query("SELECT * FROM posts WHERE user_id= " .loggedInUserId()."");
}


//getting the user category
function get_all_user_categories(){
     return query("SELECT * FROM categories WHERE user_id= " .loggedInUserId()."");
}

//this fucntion is to check for 4 method
//setting default argument as null by 
function ifItIsMethod($method) {

    //checking if we get $_SERVER['REQUEST_METHOD'] ie a request method
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }

    return false;

}

//checking if a user is logged in
function isLoggedIn() {

    //checking if the user role session is set (it can be any session, we just want to check if the session is set)
    if(isset($_SESSION['user_role'])) {
        return true;
    }
    return false;

}

//this function is to get the logged in user id
function loggedInUserId() {
    //checking if user is loggedin
    //using the isLoggedIn fxn
    if(isLoggedIn()) {
        //passing the query into the query fxn to send to db and saving it in the variable result
        // $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] . "'");

       $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] ."'");

       confirmQuery($result);
        //getting the result
        $user = mysqli_fetch_array($result);

        //checking if we got the data

        //old
        // if(mysqli_num_rows($result) >= 1) {
        //     return $user['user_id'];
        // }

        //New with if false part
        // means if mysqli_num_rows($result) is greater than one return $user['user_id'] if not return false
        return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
    }

    return false;
}


//function to display online users with the help of ajax thus won't need refresh in browser
function users_online() {

    //detecting or checking the get request from ajax in scripts.js file from load user online function
    //checking if the get onlineusers is set
    if(isset($_GET['onlineusers'])) {

        //getting the connection important
        global $connection;

        if(!$connection) {

            //starting sessions
            session_start();
            //including db connection
            include("../includes/db.php");

            $session = session_id();
            $time    = time();
            //amount of time u want the user to be marked offline, 60 there stands for 60sec
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;

            //making a query
            $query = "SELECT * FROM users_online WHERE session = '$session' ";
            //sending query
            $send_query = mysqli_query($connection, $query);
            //counting the rows in the table where session equals $session, thus counting how many users are online
            $count = mysqli_num_rows($send_query);

            if($count == NULL) {

                //if the count is nothing ie nobody is online we insert values into the rows
                mysqli_query($connection, "INSERT INTO users_online(session, timer) VALUES('$session', '$time')");

            } else {

                //if we have a count ie values
                //if the user is not new ie the user been there before
                //we will just update
                mysqli_query($connection, "UPDATE users_online SET timer= '$time' WHERE session = '$session'");

            }

            //we will diplay the user online using the count value base on the time out variable ie base on the time ie time_out_in_second the user have been on our site

            //query to find out the users online
            //this timer > $time_out means if the time the user time out of page if greater that the time in time out variable it means the user is not active
            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE timer > $time_out ");
            //counting
            $count_user = mysqli_num_rows($users_online_query);

            //not return since there's no php to catch it since we are using ajax
            echo $count_user;


        }

    } //checking if get request isset()

    

}
//Calling the function
users_online();


//to login a user
function login_user($username, $password) {

    //getting the global connecting
    global $conn;

    //triming the data from user, meaning removing white spaces
    $username = trim($username);
    $password = trim($password);
    
    //this function mysqli_real_escape_string() cleans up the data submitted to prevert hackers from harming our system
    //mysqli_real_escape_string() takes in 2 parameter the connection to database and the variable it wants to clean
    $username = mysqli_real_escape_string($conn, $username);
    // this $username above is now a clean version of the one assigned to the username field above
    
    $password = mysqli_real_escape_string($conn, $password);
    //now the info coming from our form is clean, since it doesn't have sql injection on  it, then we can put in our db
    
    //checking our column in our table 
    //query is to pull our some info is the username is found in the username column
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    
    //sending the query, the result of the query is store in $select_user_query variable
    $select_user_query = mysqli_query($conn, $query);
    
    //testing query
    if(!$select_user_query) {
        die("Query Failed!" .mysqli_error($conn));
    }
    
    while($row = mysqli_fetch_array($select_user_query)) {
        
        //looping through the result of the query
        $db_user_id        = $row['user_id'];
        $db_username       = $row['username'];
        $db_user_password  = $row['user_pass'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname  = $row['user_lastname'];
        $db_user_role      = $row['user_role'];


        //validation
        //method 1, this === makes it super strict meaning it must be identical

        //verifying the password using the password verify function

        //OLD
        /*

        f($username === $db_username && (password_verify($password, $db_user_password) || $password == $db_user_password))

        */

        if($username === $db_username && (password_verify($password, $db_user_password) || $password == $db_user_password)) {
            
            //setting some value for the session when the user successfully login
            //setting session
            //start session
            session_start(); 

            //setting a session called username
            //assigning our username ie $db_username from database to a session called username
            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            

            //if the username and password put in by user is the same to that of a user in database
            //redirect to admin dashboard 
            //OLD METHOD
            //header("Location: ../admin");

            //NEW METHOD USING CUSTOM FUNCTION TO REDIRECT
            redirect("/Finished_project/admin");
            
            
            
        }  else {
            
            //if any other thing happen, we need to just take the user back to index.php page ie home page
            
            return false;
            
        }


    }


    
   
    return true; 

}

?>

