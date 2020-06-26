       <?php session_start(); ?>

    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <div class="container">
        <a href="index.php" class="navbar-brand">Bloggen</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a href="index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item dropdown dropdown-sm active">
              <a href="#0" class="nav-link dropdown-toggle" id="blogDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog<span class="sr-only">(current)</span></a>
              <div class="dropdown-menu" aria-labelledby="blogDropdown">
                <a href="index.php" class="dropdown-item active">List</a>
                <a href="blog-overview-grid.php" class="dropdown-item">Grid</a>
              </div>
            </li>
            <li class="nav-item dropdown dropdown-sm">
              <a href="#" class="nav-link dropdown-toggle" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
              <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
                <!-- getting the categories -->

                <?php

                  $get_category_query = "SELECT * FROM categories";
                  $result = mysqli_query($conn, $get_category_query);

                  if(!$result) {
                
                      die("QUERY FAILED ".mysqli_error($conn));
                      
                  } 

                  while($row = mysqli_fetch_assoc($result)) {

                    $thy_cat_id = $row['cat_id'];
                    $thy_cat_title = $row['cat_title'];

                    //showing which link is actve by using a active class to change the style of the active link
                        //creating an empty variable where we will store our active class depending on which link is active for dynamic links
                        $category_class = '';

                        //creating another class to store active class in a non dynamic link ie for static links
                        $registration_class = '';
                        $login_class        = '';
                        $contact_class      = '';

                        //to find out the name of the current page that we are on by using the function basename () and putting $_SERVER['PHP_SELF']
                        //this PHP_SELF will be the name of the page we r on or the page we are on
                        //assigning fxn to variable
                        $pageName = basename($_SERVER['PHP_SELF']);

                        //for static links writing their pages name directly
                        $registration = 'registration.php';
                        $login        = 'login.php';
                        $contact      = 'contact.php';

                        //checking first to see if we have a GET request called category
                        if(isset($_GET['category']) && $_GET['category'] == $cat_id ) {

                            //setting the category class variable to active
                            $category_class = 'active';

                        } elseif ($pageName == $registration) {

                            //for making registration link active if we are in the registration page
                            $registration_class = 'active';

                        } elseif ($pageName == $login) {

                            //for making login link active if we are in the login page
                            $login_class = 'active';

                        } elseif ($pageName == $contact) {

                            //for making contact link active if we are in the contact page
                            $contact_class = 'active';

                        }


                    //echoing each title
                    echo "<a href='{$thy_cat_id}' class='dropdown-item'>{$thy_cat_title}</a>";

                  }



                  ?>
                  <!-- <a href='{$thy_cat_id}' class='dropdown-item'>{$thy_cat_title}</a> -->
                
                <!-- <a href="#0" class="dropdown-item">January</a> -->
              </div>
            </li>
            <li class="nav-item">
              <a href="/Finished_project/about.php" class="nav-link">About</a>
            </li>

            <?php if(isset($_SESSION['user_role'])): ?>

                <!-- if user is logged in show-->
                <li class="nav-item">
                  <a href="./admin/index.php" class="nav-link">Admin</a>
                </li>

                <li class="nav-item">
                  <a href="/Finished_project/include/logout.php" class="nav-link">Logout</a>
                </li>

                <?php else: ?>

                  <li class=' nav-item <?php echo $login_class; ?>'>
                      <a href="/Finished_project/login.php" class="nav-link">Login</a>
                   </li>

                <?php endif; ?>

            <!-- <li class="nav-item">
              <a href="./admin/index.php" class="nav-link">Admin</a>
            </li>
            <li class="nav-item">
              <a href="./login.php" class="nav-link">Login</a>
            </li> -->
            <li class="nav-item">
              <a href="./registration.php" class="nav-link">Register</a>
            </li>
          </ul>
          <form class="form-inline mt-2 mt-md-0">
            <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search" aria-label="Search">
            <button type="submit" class="btn btn-primary my-2 my-sm-0"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search</span></button>
          </form>
        </div>
      </div>
    </nav>
    