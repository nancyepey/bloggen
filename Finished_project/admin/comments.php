<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";
$username = $_SESSION['username'];
?>

<!-- HEADER -->
  <header id="main-header" class="py-2 bg-info text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1>
            <i class="fas fa-comments"></i> Manage Comments <?php echo $username; ?></h1>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Area Start -->
  <section class="container py-2 mb-4">
    <div class="row" style="min-height: 30px;">
      <div class="col-lg-12 mt-4" style="min-height: 400px;">

        <?php

         


        ?>
        
        <!-- For Un Approved Comments -->
        <h2>Un-Approved Comments</h2>
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>No.</th>
              <th>Date&Time</th>
              <th>Name</th>
              <th>Comment</th>
              <th>Approve</th>
              <th>Post Title</th>
              <th>Action</th>
              <th>Details</th>
            </tr>
          </thead>
       

           <tbody class="mb-4">
             <!-- fetching comments from our table in DB -->

        <!-- Query to display comments from db -->
        <?php
                                
       //creating the query variable where we will store our query. Selecting all the data (since we are using the *) from the desired table in this case is comments
          $query = "SELECT * FROM comments WHERE comment_status = 'unapproved'";
          //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query, n we to pass in the connection and the query
          $select_comments= mysqli_query($conn, $query);

          //we need to display it to bring back all the values using a while loop
         while($row = mysqli_fetch_array($select_comments)) {
                 //cat_id and cat_title are the name of the fields in the database in categories table
                $comment_id      = $row['comment_id'];
                $comment_post_id = $row['comment_post_id'];
                $comment_author  = $row['comment_author'];
                $comment_content = $row['comment_content'];
                 $comment_email   = $row['comment_email'];
                  $comment_status  = $row['comment_status'];
                 $comment_date    = $row['comment_date'];

        
        ?>
            <tr>

              <?php

              echo "<td>{$comment_id}</td>";
              echo "<td>{$comment_author}</td>";
                echo "<td>{$comment_content}</td>";
                echo "<td>{$comment_email}</td>";
                  echo "<td>{$comment_status}</td>";
                                     
                  //query to get the post title of the post that has a particular comment
                $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id} ";
                //sending the query to db
                $select_post_id_query = mysqli_query($conn, $query);


                //using while loop to grab or fetch the data of post where the comment
                while($row= mysqli_fetch_assoc($select_post_id_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                                            
                     echo "<td><a href='../post.php?p_id={$post_id}'>{$post_title}</a></td>";
                }
                                    
                                        
                echo "<td><a href='comments.php?approve=$comment_id'>Approve</a></td>";
             
              ?>
             
            <form method="POST">
                       <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                      <td><input type="submit" class="btn btn-danger" name="delete" value="Delete"></td>
                    </form>
                
                </tr>
        <?php } ?>
           </tbody>
           
        </table>

        <!-- For Approved Comments -->
        <h2 class="mt-4">Approved Comments</h2>
        <table class="table table-striped table-hover ">
          <thead class="thead-dark">
            <tr>
              <th>No.</th>
              <th>Date&Time</th>
              <th>Name</th>
              <th>Comment</th>
              <th>Disapprove</th>
              <th>Post Title</th>
              <th>Action</th>
              <th>Details</th>
            </tr>
          </thead>
        <!-- fetching comments from our table in DB -->
        

           <tbody>
            <!-- Query to display comments from db -->
        <?php
                                
       //creating the query variable where we will store our query. Selecting all the data (since we are using the *) from the desired table in this case is comments
          $query = "SELECT * FROM comments WHERE comment_status = 'approved'";
          //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query, n we to pass in the connection and the query
          $select_comments= mysqli_query($conn, $query);

          //we need to display it to bring back all the values using a while loop
         while($row = mysqli_fetch_array($select_comments)) {
                 //cat_id and cat_title are the name of the fields in the database in categories table
                $comment_id      = $row['comment_id'];
                $comment_post_id = $row['comment_post_id'];
                $comment_author  = $row['comment_author'];
                $comment_content = $row['comment_content'];
                 $comment_email   = $row['comment_email'];
                  $comment_status  = $row['comment_status'];
                 $comment_date    = $row['comment_date'];

        
        ?>
            <tr>

              <?php

              echo "<td>{$comment_id}</td>";
              echo "<td>{$comment_author}</td>";
                echo "<td>{$comment_content}</td>";
                echo "<td>{$comment_email}</td>";
                echo "<td>{$comment_status}</td>";

                //query to get the post title of the post that has a particular comment
                $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id} ";
                //sending the query to db
                $select_post_id_query = mysqli_query($conn, $query);


                //using while loop to grab or fetch the data of post where the comment
                while($row= mysqli_fetch_assoc($select_post_id_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                                            
                     echo "<td><a href='../post.php?p_id={$post_id}'>{$post_title}</a></td>";
                }
                                    
                                        
              
                
                echo "<td><a href='comments.php?unapprove=$comment_id'>Unapprove</a></td>";
                                     

              ?>
             
            

             <form method="POST">
                       <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                      <td><input type="submit" class="btn btn-danger" name="delete" value="Delete"></td>
                    </form>
                
                </tr>

        <?php } ?>
        
           </tbody>
        
        </table>

      </div>
    </div>
  </section>




<?php
$Admin = $_SESSION["username"];

//to approved comment
if(isset($_GET['approve'])) {
    
    $the_comment_id = escape($_GET['approve']);
    
    //delete query
    $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id={$the_comment_id}";
    
    //sending the delete query
    $approve_comment_query = mysqli_query($conn, $query);
    
    //refreshing the page so that we won't have to press the delete link twice for it to delete the post
    header("Location: comments.php"); //to refresh the page ie it will send thebasically refleshing the page

    
}


//to unapprove comments
if(isset($_GET['unapprove'])) {
    
    $the_comment_id = escape($_GET['unapprove']);
    
    //delete query
    $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id={$the_comment_id}";
    
    //sending the delete query
    $unapprove_comment_query = mysqli_query($conn, $query);
    
    //refreshing the page so that we won't have to press the delete link twice for it to delete the post
    header("Location: comments.php"); //to refresh the page ie it will send thebasically refleshing the page

    
}



 if(isset($_POST['delete'])) {
                                        
        //saving the value of the key $_GET['delete'] into a cat id variable
        $the_cmt_id = $_POST['comment_id'];
                                        
        //query to delete comments associated with the id gotten from url ie the cat_id now store as $the_cat_id
        $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id} ";
        //making the query work by using the mysqli_query() fxn passing $connection n $query
        $delete_query = mysqli_query($conn, $query);
        confirmQuery($delete_query);
        
        header("Location: comments.php"); //to refresh the page ie it will send thebasically refleshing the page
                                        
    }


  ?>



  <!-- Main Area End -->

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