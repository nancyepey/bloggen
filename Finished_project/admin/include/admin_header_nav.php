
<?php ob_start(); ?> 

<?php include "../include/db.php"; ?>
<?php include "functions.php"; ?>

<!--turning SESSION ON, since we need to start session before using it-->
<!--it telling our server to prepare the session for us-->
<?php session_start(); ?>

<?php

//checking if the user role of the user that just login is admin or subscriber
//so if the user role is admin we send the user to the admin and if not we send the user to the home page ie index.html


//if session is not set redirect user to home page
if(!isset($_SESSION['user_role'])) {
    
    
    header("Location: ../index.php");
   
 
    
} 


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>Bloggen</title>
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
    <div class="container">
      <a href="index.php" class="navbar-brand">Bloggen</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
          <li class="nav-item px-2">
            <a href="index.php" class="nav-link active">Dashboard</a>
          </li>
          <li class="nav-item px-2">
            <a href="posts.php" class="nav-link">Posts</a>
          </li>
          <li class="nav-item px-2">
            <a href="categories.php" class="nav-link">Categories</a>
          </li>
          <li class="nav-item px-2">
            <a href="users.php" class="nav-link">Users</a>
          </li>
          <li class="nav-item px-2">
            <a href="comments.php" class="nav-link">Comments</a>
          </li>
          <li class="nav-item px-2">
            <a href="../index.php" class="nav-link">BLOG</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown mr-3">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <i class="fas fa-user"></i> Welcome 
              <?php

                      //checking if $_SESSION['username'] is set
                     if(isset($_SESSION['username'])) {

                        echo $_SESSION['username'];

                     } else {
                        echo "Unknown";
                     }

                      
              ?> 
            </a>
            <div class="dropdown-menu">
              <a href="profile.php" class="dropdown-item">
                <i class="fas fa-user-circle"></i> Profile
              </a>
              <a href="settings.php" class="dropdown-item">
                <i class="fas fa-cog"></i> Settings
              </a>
              <a href="../include/endsession.php" class="dropdown-item">
                <i class="fas fa-cog"></i> Change Accounts
              </a>
            </div>
          </li>
          <li class="nav-item">
            <a href="../include/logout.php" class="nav-link">
              <i class="fas fa-user-times"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  