<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";

include "./include/add_ons.php";

//to know or get the current user
function currentUser() {

    if(isset($_SESSION['username'])) {

        //if its set return username session
        return $_SESSION['username'];

    }

    //if it is not set return false
    return false;

}

?>

<?php

?>
<!-- HEADER -->
  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1>
            <i class="fas fa-cog"></i> Dashboard</h1>
        </div>
      </div>
    </div>
  </header>


  <!-- POSTS -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <div class="card">
            <div class="card-header col-md-9 mt-4">
              <h4>Latest Posts</h4>
            </div>
            <!-- <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Date</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Post One</td>
                  <td>Web Development</td>
                  <td>May 10 2018</td>
                  <td>
                    <a href="details.php" class="btn btn-secondary">
                      <i class="fas fa-angle-double-right"></i> Details
                    </a>
                  </td>
                </tr>
              </tbody>
            </table> -->

            <div class="table-responsive">
              <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <!-- column is inside the tr tag -->
            <tr>
              <!-- these th tags are for the headings -->
              <th>#</th>
              <th>Title</th>
              <th>Category</th>
              <th>Date&Time</th>
              <th>Author</th>
              <th>Status</th>
              <!-- inside banner we will show image -->
              <th>Banner</th>
              <!-- live is to see the live preview of the post -->
              <th>Live</th>
              <th>Views</th>
            </tr>
          </thead>
          
          <tbody>
               <!-- Query to display posts from db -->
               <?php

               //getting the name of the current logged in user
               //$user = $_SESSION['username'];
               //using the function currentUser()
               $user = currentUser();
                                
                //creating the query variable where we will store our query. Selecting all the data (since we are using the *) from the desired table in this case is posts
               //this ORDER BY post_id DESC is to make sure the new posts are seen above ie from lastest or new posts to old or older posts
               
               $query = "SELECT posts.post_id, posts.post_author, posts.post_title, posts.post_cat_id, posts.post_status, posts.post_image, posts.post_video, posts.post_audio, ";
               $query .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";
               $query .= " FROM posts ";
               ////to show all post from any post user
               //$query .= " LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC";
               //to show post of the user logged in user
               $query .= " LEFT JOIN categories ON posts.post_cat_id = categories.cat_id WHERE posts.post_author ='$user' ORDER BY posts.post_id DESC";

                 //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query
                 $select_posts= mysqli_query($conn, $query);

                 confirmQuery($select_posts);

                 $no = 0;

                 //we need to display it to bring back all the values using a while loop
                 while($row = mysqli_fetch_assoc($select_posts)) {
                        //cat_id and cat_title are the name of the fields in the database in categories table
                        $post_id            = $row['post_id'];
                        $post_author        = $row['post_author'];
                        
                        $post_title         = $row['post_title'];
                        $post_category_id   = $row['post_cat_id'];
                        $post_status        = $row['post_status'];

                        $post_image         = $row['post_image'];
                        $post_video         = $row['post_video'];
                        $post_audio         = $row['post_audio'];

                        $post_tags          = $row['post_tags'];
                        $post_comment_count = $row['post_comment_count'];
                        $post_date          = $row['post_date'];
                        $post_views_count   = $row['post_views_count'];

                        //since the tables are join now (due to the join query) we can just pull the category title from here
                        $category_title     = $row['cat_title'];
                        $category_id        = $row['cat_id'];

                        $no ++;

                        echo "<tr>";
                    ?>

                    <td><?php echo $no; ?></td>


                    <?php
                        
                        //display title

                        echo "<td>{$post_title}</td>";
                                     
                     //to display the category
                        
                        echo "<td>{$category_title}</td>";

                        //display post date
                        echo "<td>{$post_date}</td>";

                        //display post author
                        echo "<td>{$post_author}</td>";
                                     
                        echo "<td>{$post_status}</td>";



                      //checking which media was uploaded and displaying it

                      if($post_image != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/images/{$post_image}' alt='image'></td>";
                        }
                        if($post_video != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/videos/vid_pic.jpg' alt='image'></td>";
                        }
                        if($post_audio != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/audios/aud_pic.jpg' alt='image'></td>";
                        }


              
                        ?>

                        


                        <?php
                        echo "<td><a class='btn btn-primary' href='../blog-post.php?p_id={$post_id}' target='_blank'>View</a></td>";

                        //using php and javascript to open up a confirmatory box 
                        //if click on yes post gets deleted if click on cancle nothing happen



                        echo "<td><a onClick=\" javascript: return confirm('Are you sure you want to reset post view count to 0!'); \" href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                        echo "</tr>";

                                    }
                                
                                ?>

            </tbody>
          
        </table>
            </div>
        <br>
        

          </div>
        </div>

        <!-- include side meenu -->
        <?php include "./include/admin_side_menu.php"; ?>
        
      </div>
    </div>
  </section>



  <!-- FOOTER -->
   <?php include "./include/admin_footer.php"; ?>





  




 