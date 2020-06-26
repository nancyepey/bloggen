<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";

include "./include/add_ons.php";

?>
<?php

if(isset($_POST['add_cat'])) {
  
  // $cat_title = $_POST['title'];

  // $cat_title = mysqli_real_escape_string($conn, $cat_title);

  // $user_id = 0;

  if(!empty($_POST['title']) || !($cat_title == "")) {

  	//OLD METHOD Procedural style
  	//  $add_cat_query = "INSERT INTO categories(cat_title, user_id) VALUES ('{$cat_title}', 0)";
  	//  $result = mysqli_query($conn, $add_cat_query);

  	// if(!$result) {
        
   //      die("QUERY FAILED ".mysqli_error($conn));
        
   //  } else {
   //  	echo "<h5 style='color:green; text-align:center;'> <span style='text-transform:capitalize; color:orange;'>{$cat_title}</span>  Category was added Successfully</h5>";
   //  }

    //NEW METHOD Procedural style
    //insert the category into cat table in db if everything is fine
    //query to insert
    $stmt = mysqli_prepare($conn, "INSERT INTO categories(cat_title, user_id) VALUES(?, ?) ");

    mysqli_stmt_bind_param($stmt, 'si', $cat_title, $user_id);

     $cat_title = $_POST['title'];

  $cat_title = mysqli_real_escape_string($conn, $cat_title);

  $user_id = 0;

    //exceute
    mysqli_stmt_execute($stmt);


    //BEST METHOD  Object oriented style

    //in case the category insertion failed, killing or stopping everything using the die() fxn n displaying a mysqli_error($connection) error to findout what happened.
    if(!$stmt) {
        die('QUERY FAILED'. mysqli_error($conn));
    } else {
    	echo "<h5 style='color:green; text-align:center;'> <span style='text-transform:capitalize; color:orange;'>{$cat_title}</span>  Category was added Successfully</h5>";
    }


  	} else {
  		echo "<h5 style='color:red; text-align:center;'>This Field Should not be empty*</h5>";
  	}
 

}

?>


  <!-- ADD CATEGORY   -->


<div class="container" style="height:43vh;">
	<div class="row">
		<div class="col-12">
			<div>
				<h2>Add Category</h2>
			</div>

			<!-- ADD CATEGORY -->
		  	 <div class="modal-body">
		         <form method="POST" action="">
		           <div class="form-group">
		             <label for="title">Title</label>
		             <input type="text" name="title" id="title" placeholder="Enter a new Category Title here" class="form-control">
		           </div>
		           <button class="btn btn-success"  type="submit" name="add_cat">Add Catgory</button>
		         </form>
		      </div>
		      


		</div>
	</div>
</div>



 <!-- FOOTER -->
   <?php include "./include/admin_footer.php"; ?>