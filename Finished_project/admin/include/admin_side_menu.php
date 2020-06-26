<?php

//PERSONALIZATION ADMIN TO USER USING DATABASE HELPER FXN FROM FUNCTION
$post_count = count_records(get_all_user_posts());


$category_count = count_records(get_all_user_categories());








?>

<div class="col-md-3 mt-4">
          <div class="card text-center bg-primary text-white mb-3">
            <div class="card-body">
              <h3>Posts</h3>
              <h4 class="display-4">
                <i class="fas fa-pencil-alt"></i> <?php echo $post_count; ?>
              </h4>
              <a href="posts.php" class="btn btn-outline-light btn-sm">View</a>
            </div>
          </div>

          <div class="card text-center bg-success text-white mb-3">
            <div class="card-body">
              <h3>Categories</h3>
              <h4 class="display-4">
                <i class="fas fa-folder"></i> <?php echo $category_count; ?>
              </h4>
              <a href="categories.php" class="btn btn-outline-light btn-sm">View</a>
            </div>
          </div>

         

