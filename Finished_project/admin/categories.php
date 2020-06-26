<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";

?>

  <!-- HEADER -->
  <header id="main-header" class="py-2 bg-success text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1>
            <i class="fas fa-folder"></i> Categories</h1>
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
            <input type="text" class="form-control" placeholder="Search Categories...">
            <div class="input-group-append">
              <button class="btn btn-success">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CATEGORIES -->
  <section id="categories">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Latest Categories</h4>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>


                 <?php

              $get_cat_title_query = "SELECT * FROM categories WHERE user_id= " .loggedInUserId()."";
              //sending query in
              $result = mysqli_query($conn, $get_cat_title_query);

              confirmQuery($result);

              $no = 0;

              while($row = mysqli_fetch_assoc($result)) {
                $cat_id = $row['cat_id'];
                $user_id = $row['user_id'];
                $cat_title = $row['cat_title'];

                $no ++;
                //display
                echo "<tr>";
                echo "<td>$no</td>";
                echo "<td>$cat_title</td>";
                ?>

              
                    <form method="POST">
                       <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
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
                                        
        //saving the value of the key $_GET['delete'] into a cat id variable
        $the_cat_id = $_POST['cat_id'];
                                        
        //query to delete category associated with the id gotten from url ie the cat_id now store as $the_cat_id
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        //making the query work by using the mysqli_query() fxn passing $connection n $query
        $delete_query = mysqli_query($conn, $query);
        //refreshing the page so that we won't have to press the delete link twice for it to delete the category
        header("Location: categories.php"); //to refresh the page ie it will send thebasically refleshing the page
                                        
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