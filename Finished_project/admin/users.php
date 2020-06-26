<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";
$username = $_SESSION['username'];
?>

  <!-- HEADER -->
  <header id="main-header" class="py-2 bg-warning text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1>
            <i class="fas fa-users"></i> Users</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- SEARCH -->
  <section id="search" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-6 ml-auto">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search Users...">
            <div class="input-group-append">
              <button class="btn btn-warning">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- USERS -->
  <section id="users">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Users added by <?php echo $username; ?></h4>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
           


                <?php
                
                //display category from db in drop down menu
                 //query to get the users, so that we up view it
                 $users_query = "SELECT * FROM users WHERE addedby = '$username'";
                
                  $select_users = mysqli_query($conn, $users_query);

                     confirmQuery($select_users);

                     $num = 0;

                  //we need to display it to bring back all the values using a while loop
                  while($row = mysqli_fetch_assoc($select_users)) {
               //user_id and username are the name of the fields in the database in users table
                 $user_id    =  $row['user_id'];
                  $username  =  $row['username'];
                  $email  =  $row['user_email'];

                  $num ++;

                  echo "<tr>";

                  echo "<td>$num</td>";

                      //sending username to display username
                      echo "<td>{$username}</td>";
                   echo "<td>{$email}</td>";

                  
                  ?>

              
                    <form method="POST">
                       <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                      <td><input type="submit" class="btn btn-danger" name="delete" value="Delete"></td>
                    </form>
                  
                 

              </tr>

                <?php
              }

              ?>



              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php

 if(isset($_POST['delete'])) {
                                        
        //saving the value of the key $_GET['delete'] into a user  id variable
        $the_user_id = $_POST['user_id'];
                                        
        //query to delete user associated with the id gotten from url ie the user _id now store as $the_user_id
        $query = "DELETE FROM users WHERE user_id = {$the_user_id} ";
        //making the query work by using the mysqli_query() fxn passing $connection n $query
        $delete_query = mysqli_query($conn, $query);
        //refreshing the page so that we won't have to press the delete link twice for it to delete the user
        header("Location: users.php"); //to refresh the page ie it will send thebasically refleshing the page
                                        
    }


  ?>


  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
    crossorigin="anonymous"></script>


  <script>
    // Get the current year for the copyright
    $('#year').text(new Date().getFullYear());
  </script>
</body>

</html>