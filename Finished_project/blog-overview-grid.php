<?php include "./include/db.php" ?>

<!-- fxns -->
<?php include "./admin/functions.php";  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">

    <title>Blog - Overview: Grid</title>

  </head>
  <body>
     <!-- nav -->
    <?php include "./include/nav.php" ?>
    <div class="container my-3 my-sm-5">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-8">
          <h2 class="mb-3">Latest posts</h2>
          <div class="row">
            <?php

             //checking if we have a user role session ie if it is set
                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {

                    //admin showed see everything both draft and published posts
                   $post_query_count = "SELECT * FROM posts";

                } else {

                    //a user should only see published posts
                    $post_query_count = "SELECT * FROM posts WHERE post_status = 'published'";

                }


                //query to get all posts
                $find_count = mysqli_query($conn, $post_query_count);
                //calculating the number of row in the posts table thus getting the total number of posts using the function mysqli_num_row() to calculate the number of rows
                $count = mysqli_num_rows($find_count);

                //if count is less than 1
                //if count us less than one means ther's no record with post_status equals to published ie no published post
                if($count < 1) {

                    //displaying a text to say no post
                    echo "<h1 class='text-center'>NO POST AVAILABLE</h1>";

                } else {
 
                $count = ceil($count / 5);


                //checking if we have a user role session ie if it is set
                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {

                    //admin showed see everything both draft and published posts
                    //creating query to get elt from post
                    //this LIMIT the number of page shown per page to 5 or $per page
                   // $query = "SELECT * FROM posts LIMIT $page_1, $per_page";
                   $query = "SELECT * FROM posts";

                } else {

                    //a user should only see published posts
                    //creating query to get elt from post
                    //this LIMIT the number of page shown per page to 5 or $per page
                    $query = "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC";

                }

                //creating query to get elt from post
                $select_all_post_query = mysqli_query($conn, $query);
                //we need to display it to bring back all the values using a while loop
                while($row = mysqli_fetch_array($select_all_post_query)) {
                    //$row['post_title'] this is the row from the database and we are assigning it to a variable $post_title
                    $post_id      = $row['post_id'];
                    $post_category_id      = $row['post_cat_id'];
                    $post_title   = $row['post_title'];
                    //this substr() fxn is to limit the characters display to 100
                    $post_content = substr($row['post_content'], 0,100);
                    $post_date    = $row['post_date'];
                    $post_tags    = $row['post_tags'];
                    //before adding post user
                    //$post_author = $row['post_author'];
                    //aftter adding post user, just changing the row to avoid over changing things
                    $post_author  = $row['post_author'];
                    
                    $post_image   = $row['post_image'];
                    $post_video   = $row['post_video'];
                    $post_audio   = $row['post_audio'];
                    
                    $post_status  = $row['post_status'];

                    $post_comment_count  = $row['post_comment_count'];
                    $post_views_count  = $row['post_views_count'];
                    $post_likes  = $row['likes'];

            ?>
            <div class="col-12 col-lg-6">
              <div class="card mb-4">
                <div class="card-body">
                  <article>
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
                      <h1 class="h4"><a href="blog-post-text.php"><?php echo $post_title; ?></a></h1>
                      <div><a href="#0"><?php echo $post_author; ?></a></div>
                      <div class="small">Posted on: <time datetime="<?php echo $post_date; ?>"><?php echo $post_date; ?></time></div>
                      <div class="small"><span class="badge badge-primary"><i class="fa fa-thumbs-up" aria-hidden="true"></i><?php //likes counts ?> <?php $likes_count = count_records(get_post_likes($post_id)); echo $likes_count; ?>  <span class="sr-only">likes</span></span> <span class="badge badge-primary"><i class="fa fa-comments" aria-hidden="true"></i><?php //comment counts ?> <?php $comment_count = count_records(get_post_comments($post_id)); echo $comment_count; ?> <span class="sr-only">comments</span></span></div>
                      <div class="small">
                        <a href="#0" class="badge badge-secondary"><?php echo $post_tags; ?></a></a>
                      </div>
                    </header>
                    <p><?php echo $post_content; ?></p>
                    <a href="blog-post.php?p_id=<?php echo $post_id; ?>" class="btn btn-primary">Read more</a>
                  </article>
                </div>
              </div>
            </div>
            <hr>

        <?php }  } ?>
          </div>
          <!-- <nav aria-label="Blog pages">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <span class="page-link" aria-label="Previous">
                  <span aria-hidden="true">«</span>
                  <span class="sr-only">Previous</span>
                </span>
              </li>
              <li class="page-item active">
                <a href="#0" class="page-link">
                  1 <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="page-item"><a href="#0" class="page-link">2</a></li>
              <li class="page-item"><a href="#0" class="page-link">3</a></li>
              <li class="page-item"><a href="#0" class="page-link">4</a></li>
              <li class="page-item"><a href="#0" class="page-link">5</a></li>
              <li class="page-item">
                <a href="#0" class="page-link" aria-label="Next">
                  <span aria-hidden="true">»</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav> -->
        </div>
        <div class="col-12 col-md-6 col-lg-4">
          <!-- aside side bar -->
          <?php include "./include/side_bar.php" ?>
        </div>
      </div>
    </div>
    
    <!-- footer -->
    <?php include "./include/footer_rest.php" ?>
    