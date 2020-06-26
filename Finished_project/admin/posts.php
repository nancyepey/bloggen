<?php include "include/admin_header_nav.php" ?>


  <!-- HEADER -->
  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1>
            <i class="fas fa-pencil-alt"></i> Posts</h1>
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
            <input type="text" class="form-control" placeholder="Search Posts...">
            <div class="input-group-append">
              <button class="btn btn-primary">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- POSTS -->
  <!-- Main content area -->
        <!-- wapper start-->
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header mt-4 mb-4">
                            Welcome to Admin 
                            <small><?php echo $_SESSION['username']; ?></small>
                        </h1>
                        
                        
                        <!-- Table to display the post -->
                        <?php
                        
                        //creating a condition to display posts table
                        //checking if $_GET['source'] is available
                        //if we don't found the get request we will deplay our posts table thus making the posts tabel to be displayed all the time when there's no GET request
                        if(isset($_GET['source'])) {
                            
                            $source = escape($_GET['source']);
                            
                        } else {
                            //giving it an empty value is to make sure the post table is seen and avoid errors
                            $source = '';
                        }

                            
                            //using a switch case to determine what is shown here
                            //the default is the info in view_all_posts.php ie our post table
                            switch($source) {
                                    
                                case 'edit_post':
                                    //including add_post.php
                                    include "include/edit_post.php";
                                    break;
                                
                                    
                                default:
                                    //display our posts table found in view_all_posts.php
                                    //including view_all_posts.php
                                    include "include/view_all_posts.php";
                                    break;
                                    
                            }
                                                    
                        ?>
                        
                        <!-- Table to display the post END -->
                        
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
    crossorigin="anonymous"></script>
  <script src="https://cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>

  <script>
    // Get the current year for the copyright
    $('#year').text(new Date().getFullYear());

    CKEDITOR.replace('editor1');
  </script>