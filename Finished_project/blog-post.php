<?php include "./include/db.php" ?>

<!-- fxns -->
<?php include "./admin/functions.php";  ?>

<!-- head -->
<?php include "./include/details/header.php" ?>

    <?php

    //checking for the like
    if(isset($_POST['liked'])) {

        // echo "<h1>ITS WORKS </h1>";

        //we have our post_id in the get n post request
        //getting the post_d from the post request
        //we are getting it from the ajax request down below
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        //WHAT WE WANT TO DO
        //STEPS

        //1 = SELECT or FETCHING THE RIGHT POST
        //query to get the paticular post with the id
        $searchPostQuery = "SELECT * FROM posts WHERE post_id= {$post_id}";
        //connection to bd n sending the query
        $postResult  = mysqli_query($conn, $searchPostQuery);
        //fetching the search post query
        $post = mysqli_fetch_array($postResult);
        //getting the likes
        $likes = $post['likes'];

        //checking if its working
        // if(mysqli_num_rows($postResult) >= 1) {
        //     echo $post['post_id'];
        // }

        //2 = UPDATE POST WITH INCREMENTING LIKES
        //passing the query directly n sending in to the db
        mysqli_query($conn, "UPDATE posts SET likes=$likes+1 WHERE post_id= {$post_id}");

        //3 = CREATE LIKES FOR POST
        mysqli_query($conn, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();
    }


    //checking for the unlike
    if(isset($_POST['unliked'])) {

        // echo "<h1>ITS WORKS UNLIKE</h1>";

        //we have our post_id in the get n post request
        //getting the post_d from the post request
        //we are getting it from the ajax request down below
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        //WHAT WE WANT TO DO
        //STEPS

        //1 = SELECT or FETCHING THE RIGHT POST
        //query to get the paticular post with the id
        $searchPostQuery = "SELECT * FROM posts WHERE post_id= {$post_id}";
        //connection to bd n sending the query
        $postResult  = mysqli_query($conn, $searchPostQuery);
        //fetching the search post query
        $post = mysqli_fetch_array($postResult);
        //getting the likes
        $likes = $post['likes'];

        //checking if its working
        // if(mysqli_num_rows($postResult) >= 1) {
        //     echo $post['post_id'];
        // }

        //2 = DELETING LIKES
        mysqli_query($conn, "DELETE FROM likes WHERE post_id= {$post_id} AND user_id= {$user_id}");

        //3 = UPDATE POST WITH DECREMENTING LIKES
        //passing the query directly n sending in to the db
        mysqli_query($conn, "UPDATE posts SET likes=$likes-1 WHERE post_id= {$post_id}");
        exit();
    }

    ?>
 


  <body>
   
    
    <!-- nav -->
    <?php include "./include/nav.php" ?>
   
    <div class="container my-3 my-sm-5">
      <div class="row">
        <div class="col-12 col-lg-8">
          <article class="mb-3">

            <?php

            //catching the parameter from the index.php in the url
                //checking if the key or parameter is in the url
                if(isset($_GET['p_id'])) {
                    
                    $the_post_id = mysqli_real_escape_string($conn, trim($_GET['p_id']));


                    //getting the post view counts
                    $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
                    //sending the query in
                    $send_query = mysqli_query($conn, $view_query);


                    //checking if we have a user role session ie if it is set
                    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {

                        //admin showed see everything both draft and published posts
                        $query = "SELECT * FROM posts WHERE post_id={$the_post_id} ";

                    } else {

                        //a user should only see published posts
                        $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";

                    }
               
                
                    //creating query to get the post which id is the same as that in the url
                    //$query = "SELECT * FROM posts WHERE post_id={$the_post_id} ";
                    //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query, n we to pass in the connection and the query
                    $select_all_post_query = mysqli_query($conn, $query);

                    //checking if there's any data that corresponds
                    if(mysqli_num_rows($select_all_post_query) < 1) {

                        //displaying a text to say no post 
                        echo "<h1 class='text-center'>No post available</h1>";

                    } else {


                    //we need to display it to bring back all the values using a while loop
                    while($row = mysqli_fetch_array($select_all_post_query)) {
                        //$row['post_title'] this is the row from the database and we are assigning it to a variable $post_title
                        $post_title   = $row['post_title'];
                        $post_author  = $row['post_author'];
                        $post_date    = $row['post_date'];
                        $post_image   = $row['post_image'];
                        $post_content = $row['post_content'];
                        $post_tags    = $row['post_tags'];
                        $post_category_id    = $row['post_cat_id'];

            ?>


            <header class="mb-2">
              <?php

                       $get_cat_title_query = "SELECT * FROM categories WHERE cat_id = $post_category_id";
                    //sending query in
                    $result = mysqli_query($conn, $get_cat_title_query);

                    confirmQuery($result);

                    while($row = mysqli_fetch_assoc($result)) {
                      $post_cat_id = $row['cat_id'];
                      $post_user_id = $row['user_id'];
                      $post_cat_title = $row['cat_title'];
                      //display
                      if($post_cat_id  == $post_category_id) {
                        echo "<a href='{$post_cat_id}' class='badge badge badge-primary cat_color'>{$post_cat_title}</a>";
                      
                      }else {
                        echo "No Category";
                      }
                    }

                      ?>
              <!-- <a href="#" class="badge badge-primary">Category</a> -->
              <h1><?php echo $post_title; ?></h1>
              <div><a href="#0"><?php echo $post_author; ?></a></div>
              <div class="small">Posted on: <time><?php echo $post_date; ?></time></div>
              <!-- likes and comment counts -->
              <div class="small"><span class="badge badge-primary"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <?php //likes counts ?> <?php $likes_count = count_records(get_post_likes($the_post_id)); echo $likes_count; ?>  <span class="sr-only">likes</span></span> <span class="badge badge-primary"><i class="fa fa-comments" aria-hidden="true"></i> <?php //comment counts ?> <?php $comment_count = count_records(get_post_comments($the_post_id)); echo $comment_count; ?> <span class="sr-only">comments</span></span></div>
              <div class="small">
                <a href="#0" class="badge badge-secondary"><?php echo $post_tags; ?></a>
              </div>
            </header>
            <section id="main" class="mb-3">
              <!-- <figure class="figure">
                <img src="http://placehold.it/800x600" class="figure-img img-fluid" alt="Figure image">
                <figcaption class="figure-caption small">
                  <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In laoreet pellentesque lorem sed elementum.</p>
                  <p class="mb-0"><i class="fa fa-camera" aria-hidden="true"></i><span class="sr-only">Photo by:</span> Artist Name</p>
                </figcaption>
              </figure> -->
             <p><?php echo $post_content; ?></p>
            </section>
           
            <section id="comments" class="small">

              <?php 
                     
                    //function is for user like post 
function userLikedThisPost1($post_id = '') {
    //getting the user that like post
   $result =  query("SELECT * FROM likes WHERE user_id=" . loggedInUserId() ."  AND post_id={$post_id}");
   confirmQuery($result);
    //using the tertiary operator instead of if
    // means if mysqli_num_rows($result) is greater than one return true if not return false
    return mysqli_num_rows($result) >= 1 ? true : false;
}

                    ?>


                     <?php

                    //making sure user don't see the link btn if u are not logged in

                    if(isLoggedIn()) {

                        ?>

                        <!-- <div class="row">
                        <p class="pull-right">
                            <a class=" -->
                        <div class="row">
                        <p class="pull-right">
                            <a class="

                            <?php
                            
                            // if(userLikedThisPost1($the_post_id) ) {
                            //     echo "unlike";
                            // }else {
                            //     echo "like";
                            // }

                            echo userLikedThisPost1($the_post_id) ? 'unlike' : 'like';

                                                  

                            ?>

                            " href=""
                            data-toggle="tooltip"
                            data-placement="top"
                            title="<?php echo userLikedThisPost1($the_post_id) ? ' I Liked this before' : ' Want to like it'; ?>"
                            ><span class="glyphicon glyphicon-thumbs-up"></span>
                            <?php

                            // if(userLikedThisPost1($the_post_id) ) {
                            //     echo "unlike";
                            // }else {
                            //     echo "like";
                            // }

                            echo userLikedThisPost1($the_post_id) ? 'unlike' : 'like';

                            ?>

                            <!-- Adding tooltip when you hover -->



                            </a>
                        </p>
                    </div>

                            <?php } else { ?>

                    <div class="row">
                        <p class="pull-right login-to-post">You need to <a href="/Finished_project/login.php">Login</a> to like</p>
                        <!-- <button class="btn btn-lg btn-primary mb-3"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="sr-only login-to-post">You need to <a href="/Finished_project/login.php">Login</a> to like</span></button> -->
                    </div>
                    
              


                    <?php
                   }


                    ?>

                    <div class="row">
                        <p class="pull-right likes">Like: 
                            <?php

                            //METHOD 1
                            // $likeCount_query = "SELECT * FROM likes WHERE post_id={$the_post_id} ";
                            // //sending the query in
                            // $likeCount = mysqli_query($connection, $likeCount_query);
                            // confirmQuery($likeCount);

                            // echo mysqli_num_rows($likeCount);

                            //METHOD 2
                            echo getPostLikes($the_post_id);



                            ?>
                        </p>
                    </div>

                    <div class="clearfix"></div>

                    <?php 
                }}}


                     ?>

              <!-- <button class="btn btn-lg btn-primary mb-3"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="sr-only">Like</span></button>
              <h2>Comments</h2> -->

              
              

              <!-- Posted Comments -->
                  <!-- <h2>Comments</h2><br> -->
                
                    <?php
                    
                    $query  = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                    $query .= "AND comment_status = 'approved' ";
                    $query .= "ORDER BY comment_id DESC ";
    //                $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} AND comment_status = 'approved' ";
    //                $query .= "AND comment_status = 'approved' ";
    //                $query .= "ORDER BY comment_id DESC ";
                    
                     $select_comment_query = mysqli_query($conn, $query);
                    
                    if(!$select_comment_query) {
                        die('Query Failed' . mysqli_error($conn));
                    }
                    
                      while ($row = mysqli_fetch_array($select_comment_query)) {
                        $comment_date   = $row['comment_date']; 
                        $comment_content= $row['comment_content'];
                        $comment_author = $row['comment_author'];

                    
                    ?>
                    
                    <!-- Comment -->
                    

                    <div class="media">
                <img src="https://placehold.it/64x64" alt="Media object image" class="mr-3">
                <div class="media-body">
                  <p><a href="mailto:example@domain.com"><?php echo $comment_author;  ?></a> (Posted on: <time datetime=""><?php echo $comment_date;  ?></time>)</p>
                  <p><?php echo $comment_content;  ?></p>
                </div>
              </div>
              <hr>
                
                <?php } ?>

                
                <!-- See how to add nested comment meaning a person can reply another comment in a post too -->
                <!-- Comment End -->

              <!-- <div class="media">
                <img src="https://placehold.it/64x64" alt="Media object image" class="mr-3">
                <div class="media-body">
                  <p><a href="mailto:example@domain.com">Author Name</a> (Posted on: <time datetime="2017-03-12T10:24">March 12, 2018 at 10:24 AM</time>)</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed arcu bibendum massa euismod scelerisque. Morbi porttitor tellus tempor metus posuere dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                  <div class="media">
                  <img src="https://placehold.it/64x64" alt="Media object image" class="mr-3">
                  <div class="media-body">
                    <p><a href="mailto:example@domain.com">Author Name</a> (Posted on: <time datetime="2017-03-12T10:24">March 12, 2017 at 10:24 AM</time>)</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed arcu bibendum massa euismod scelerisque. Morbi porttitor tellus tempor metus posuere dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                  </div>
                </div>
                <div class="media">
                  <img src="https://placehold.it/64x64" alt="Media object image" class="mr-3">
                  <div class="media-body">
                    <p><a href="mailto:example@domain.com">Author Name</a> (Posted on: <time datetime="2017-03-12T10:24">March 12, 2017 at 10:24 AM</time>)</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed arcu bibendum massa euismod scelerisque. Morbi porttitor tellus tempor metus posuere dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                  </div>
                </div>
                </div>
              </div> -->
              <!-- <hr> -->

              <!-- Blog Post Comments -->
                    
                    <?php
                    //if create_comment btn is set
                    if(isset($_POST['create_comment'])) {
                        
                        //echo $_POST['comment_author']; for testing
                        
                        //getting the post id from the url
                        $the_post_id = mysqli_real_escape_string($conn, trim($_GET['p_id']));
                        
                        //getting the post data from the fields in the form
                        $comment_author  = mysqli_real_escape_string($conn, trim($_POST['comment_author']));
                        $comment_email   = mysqli_real_escape_string($conn, trim($_POST['comment_email']));
                        $comment_content = mysqli_real_escape_string($conn, trim($_POST['comment_content']));


                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                            //making the insert into query
                            $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                            $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now() )";
                            
                            //sending the query in and storing the result in create_comment_query variable
                            // $create_comment_query = mysqli_query($connection, $query);
                            $create_comment_query = mysqli_query($conn, $query);
                            
                            //test if the query works
                            if(!$create_comment_query) {
                                die('QUERY FAILED'. mysqli_error($conn));
                            }
                            
                            //query to update the post comment count everytime a post is comment is created for a particular post
                            //OLD WAY
                            // $query  = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                            // $query .= "WHERE post_id = $the_post_id ";
                            
                            //send the query
                            //$update_comment_count = mysqli_query($connection, $query);

                        } else {

                            //display warning message if the fields are empty
                            echo "<script>alert('Fields cannot be empty')</script>";

                        }
                        
                        
                    } 
                 //    else {

                 //    //redirecting users that don't have post id back to index.php page ie the main page
                 //    header("Location: index.php");

                 // }
                    
                    
                    ?>


              <form action="" method="post" role="form">
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="name">Name:</label>
                      <input type="text" class="form-control" placeholder="Name" id="name" name="comment_author" required>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="email">Email:</label>
                      <input type="email" class="form-control" placeholder="example@email.com" id="email" name="comment_email" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="comment">Your comment:</label>
                  <textarea class="form-control" id="comment" name="comment_content" rows="5" placeholder="Write your comment here. Maximum 500 characters." required></textarea>
                </div>
                <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
              </form>
            </section>
          </article>
        </div>
        <div class="col-12 col-lg-4">
          <aside>
            <h2 class="mb-3">About the author</h2>
            <div class="card mb-3">
              <div class="card-body">
                <div class="text-center">
                  <img src="http://placehold.it/200x200" class="img-fluid rounded-circle" alt="Figure image">
                  <ul class="list-inline">
                    <li class="list-inline-item small"><a href="#0">Twitter</a></li>
                    <li class="list-inline-item small"><a href="#0">LinkedIn</a></li>
                  </ul>
                </div>
                <h5><a href="#0">Author Name</a></h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed arcu bibendum massa euismod scelerisque. Morbi porttitor tellus tempor metus posuere dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
              </div>
            </div>

            <!-- side bar -->
          <!-- aside -->
          <?php include "./include/side_bar.php" ?>
            
          </aside>
        </div>
      </div>
    </div>
 

        </div>
      </div>
    </div>
    
    <!-- footer -->
    <!-- ending code -->
    <?php include "./include/footer_rest.php" ?>

    <script>

    $(document).ready(function() {

        // alert("HELLO");

        //tool tip to show text on hover using bootstrap
        $("[data-toggle='tooltip']").tooltip();

        //getting the post id
        var post_id = <?php echo $the_post_id; ?>;

        //logged it user id
        var user_id = <?php echo loggedInUserId(); ?>;

        //FOR LIKE
        //LIKING

         $('.like').click(function() {

             // console.log("Its working")
             // alert("HELLO");

             //creating ajax to send request to our server
             
             //this will carry some of that data in our post request data to the same page
             $.ajax({
                url: "/Finished_project/blog-post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                //this object
                data: {
                    liked: 1,
                    'post_id': post_id,
                    //logged in user id
                    'user_id': user_id
                }
             });

         });

         //FOR UNLIKE
        //UNLIKING

         $('.unlike').click(function() {

             // console.log("Its working")
             // alert("HELLO");

             //creating ajax to send request to our server
             
             //this will carry some of that data in our post request data to the same page
             $.ajax({
                url: "/Finished_project/blog-post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                //this object
                data: {
                    unliked: 1,
                    'post_id': post_id,
                    //logged in user id
                    'user_id': user_id
                }
             });

         });


    });
    
</script>