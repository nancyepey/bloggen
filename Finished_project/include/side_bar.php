<aside>
  <div class="card mb-3">
    <div class="card-body bg-light">
      <!-- checking if a user role was set since it gets set when user login -->
                    <!-- using a short way or another way of writing if statements -->
                    <?php if(isset($_SESSION['user_role'])): ?>

                        <h4>Logged in as <?php echo $_SESSION['username']; ?></h4>

                        <!-- creating a logout btn -->
                        <a href="/Finished_project/include/logout.php" class="btn btn-primary"> Logout </a>

                    <!-- adding an else statement to if -->
                    <?php else: ?>
      <h5 class="card-title">Login</h5>
      <form action="/Finished_project/login.php" method="post">
        <div class="form-group">
            <input name="username" type="text" class="form-control" placeholder="Enter Username">
        </div>
                            
        <div class="input-group">
            <input name="password" type="password" class="form-control" placeholder="Enter Password">
            <span class="input-group-btn">
                                    
                <button class="btn btn-primary" name="login" type="submit">
                    Submit
                </button>
                                    
            </span>
        </div>
        <div class="form-group">
            <a href="/cms/forgot.php?forgot=<?php echo uniqid(true); ?>">Forgot Password</a>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>
            <h2 class="mb-3">Popular posts</h2>
            
            <div class="list-group mb-3">

              <a href="#0" class="list-group-item list-group-item-action">
                <div class="badge badge-primary float-right">Category</div>
                <h5>Blog post title</h5>
                <p class="small mb-2">Author Name (<time datetime="2017-03-12T10:24">March 12, 2017 at 10:24 AM</time>)</p>
                <div class="small mb-2"><span class="badge badge-primary"><i class="fa fa-thumbs-up" aria-hidden="true"></i> 13 <span class="sr-only">likes</span></span> <span class="badge badge-primary"><i class="fa fa-comments" aria-hidden="true"></i> 3 <span class="sr-only">comments</span></span></div>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </a>
              
            </div>
            <h2 class="mb-3">Recent posts</h2>
            <div class="list-group mb-3">
              <a href="#0" class="list-group-item list-group-item-action">
                <div class="badge badge-primary float-right">Category</div>
                <h5>Blog post title</h5>
                <p class="small mb-2">Author Name (<time datetime="2017-03-12T10:24">March 12, 2017 at 10:24 AM</time>)</p>
                <div class="small mb-2"><span class="badge badge-primary"><i class="fa fa-thumbs-up" aria-hidden="true"></i> 13 <span class="sr-only">likes</span></span> <span class="badge badge-primary"><i class="fa fa-comments" aria-hidden="true"></i> 3 <span class="sr-only">comments</span></span></div>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </a>
            
              
            </div>
            <h2 class="mb-3">Categories</h2>
            <div class="list-group mb-3" id="main-div">

              <?php

              $get_cat_title_query = "SELECT * FROM categories LIMIT 5";
              //sending query in
              $result = mysqli_query($conn, $get_cat_title_query);

              confirmQuery($result);

              while($row = mysqli_fetch_assoc($result)) {
                $post_cat_id = $row['cat_id'];
                $post_user_id = $row['user_id'];
                $post_cat_title = $row['cat_title'];
                //display
              
                  echo "<a href='{$post_cat_id}' class='list-group-item list-group-item-action  text-white sidebar_categories sidebar_cat'>{$post_cat_title}</a>";
                
               
              }




              ?>

              <!-- <a href="#" class="list-group-item list-group-item-action  text-white sidebar_categories">Category</a> -->
             <!--  <a href="#" class="list-group-item list-group-item-action  text-white sidebar_categories">Category</a>
              <a href="#" class="list-group-item list-group-item-action  text-white sidebar_categories">Category</a>
              <a href="#" class="list-group-item list-group-item-action  text-white sidebar_categories">Category</a>
              <a href="#" class="list-group-item list-group-item-action  text-white sidebar_categories">Category</a>
 -->
              <!-- <a href="#" class="list-group-item list-group-item-action  text-white" id="sidebar_categories">Category</a> -->


              <!-- <a href="#" class="list-group-item list-group-item-action bg-primary text-white" id="sidebar_categories">Category</a>
              <a href="#" class="list-group-item list-group-item-action bg-success text-white">Category</a>
              <a href="#" class="list-group-item list-group-item-action bg-danger text-white">Category</a>
              <a href="#" class="list-group-item list-group-item-action bg-warning text-white">Category</a>
              <a href="#" class="list-group-item list-group-item-action bg-info text-white">Category</a> -->
            </div>
        
            <div class="card">
              <div class="card-body bg-light">
                <h5 class="card-title">Signup for our newsletter</h5>
                <form method="POST" action="./include/newsletter.php">
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="addon-left">@</span>
                    </div>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email" aria-describedby="addon-left" required>
                  </div>
                  <button type="submit" name="newsletter_signup" class="btn btn-primary">Signup</button>
                </form>
              </div>
            </div>
          </aside>