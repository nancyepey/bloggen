<?php 

include "../include/db.php";

include "./include/admin_header_nav.php";

include "./include/add_ons.php";

// include "./functions.php";

?>

<?php

if(isset($_POST['add_post'])) {

	//output msgs
	$statusMsg = '';

	//getting the post title
	$post_title = escape($_POST['post_title']);

	//getting the post category
	$post_cat = escape($_POST['post_cat']);

	//getting the post author
	$post_author = escape($_POST['post_author']);

	//getting the post status
	$post_status = escape($_POST['post_status']);

	//getting the post tags
	$post_tags = escape($_POST['post_tags']);

	//getting the post content
	$post_content = escape($_POST['editor1']);

	//assigning the date() fxn to the post date variable and passing the format or assigning the format d-m-y
    $post_date = date('d-m-y');

	//hard coding the count but it shall be dynamic later
    $post_comment_count = 0;

    //for views
    $post_views_count = 0;

    $user_id = $_SESSION['user_id'];

    $post_author = $_SESSION['username'];

    //for datetime
	date_default_timezone_set("Africa/Douala"); //to specify time with respect to my zone
	$CurrentTime =time(); //current time in seconds
	//strftime is string format time
	//$DateTime = strftime("%Y-%m-%d %H:%M:%S",$CurrentTime); //mostly use when we have to apply sql format
	$DateTime = strftime("%B-%d-%Y %H:%M:%S",$CurrentTime); 


	//getting up some validations
	if(empty($post_title)) {
		$statusMsg = "Title Can not be empty";
	}elseif (empty($post_cat)) {
		$statusMsg = "Category Can not be empty";
	}elseif (empty($post_author)) {
		$statusMsg = "Author Can not be empty";
	}elseif (empty($post_status)) {

		$statusMsg = "Status Can not be empty";

	}elseif (empty($post_tags)) {

		$statusMsg = "Tags Can not be empty";

	}elseif (empty($post_content)) {

		$statusMsg = "Content Can not be empty";

	}else {


		//getting media

		//setting defaults
		$file_image = '';
		$file_audio = '';
		$file_video = '';


		//getting the image
		// File upload path
		$targetDir_img = "./uploads/images/";
		// $file_image = basename($_FILES["file"]["name"]);
		// $file_image = basename($_FILES["image"]["name"]);
		$file_image = basename(escape($_FILES['upload_image']['name']));
		$targetFilePath_img = $targetDir_img . $file_image;
		$fileType_img = pathinfo($targetFilePath_img,PATHINFO_EXTENSION);

		//getting the audios
		// File upload path
		$targetDir_aud = "./uploads/audios/";
		$file_audio = basename(escape($_FILES['upload_audio']['name']));
		$targetFilePath_aud = $targetDir_aud . $file_audio;
		$fileType_aud = pathinfo($targetFilePath_aud,PATHINFO_EXTENSION);

		//getting the videos
		// File upload path
		$targetDir_vid = "./uploads/videos/";
		$file_video = basename(escape($_FILES['upload_video']['name']));
		$targetFilePath_vid = $targetDir_vid . $file_video;
		$fileType_vid = pathinfo($targetFilePath_vid,PATHINFO_EXTENSION);

		if(!empty($_FILES["upload_image"]["name"]) || !empty($_FILES["upload_audio"]["name"]) || !empty($_FILES["upload_video"]["name"])){
		    // Allow certain file formats IMAGES
		    $allowTypes_img = array('jpg','png','jpeg','gif','pdf');
		    // Valid file extensions VIDEOS
      		 $extensions_arr = array("mp4","avi","3gp","mov","mpeg");
      		 //file AUDIO
      		 $allowTypes_aud = array('mp3','aac','wav','flac');
      		 //
		    if(in_array($fileType_img, $allowTypes_img) || in_array($fileType_aud, $allowTypes_aud) || in_array($fileType_vid, $extensions_arr)){
		        // Upload file to server
		        if(move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath_img) || move_uploaded_file($_FILES["upload_audio"]["tmp_name"], $targetFilePath_aud) || move_uploaded_file($_FILES["upload_video"]["tmp_name"], $targetFilePath_vid)){

		        	//query to add post
				    $query = "INSERT INTO posts(post_cat_id, user_id, post_title, post_content, post_date, post_tags, post_author, post_image, post_video, post_audio, post_status, post_comment_count, post_views_count, likes)";
				    
				    //for date we are not sending a value but we are sending a function
				    $query .= "VALUES({$post_cat} , 0,'{$post_title}', '{$post_content}', '{$DateTime}', '{$post_tags}', '{$post_author}','{$file_image}', '{$file_video}', '{$file_audio}', '{$post_status}', 0, 0, 0)";
				    
				    //sending the query to the database
				    $create_post_query = mysqli_query($conn, $query);

				    confirmQuery($create_post_query);


		            if($create_post_query){
		                // $statusMsg = "The file ".$file_image. " has been uploaded successfully.";
		                $statusMsg = "<p class='bg-primary' style='text-align:center;'> <span style='text-transform:capitalize; color:orange;'>{$post_title}</span> Post was Created Sucessfully. <a href='#' target='_blank' style='text-transform:capitalize; color:orange;'>View Post</a> or <a href='#' target='_blank' style='text-transform:capitalize; color:orange;'> Edit More Posts</a></p>";
		                
		            }else{
		                $statusMsg = "File upload failed, please try again.";
		            } 
		        }else{
		            $statusMsg = "Sorry, there was an error uploading your file.";
		        }
		    }else{
		        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
		    }
		}

	}
	

	// Display status message
	echo $statusMsg;
  
 
}

?>



  <!-- ADD POST  -->

<div class="container">
	<div class="row">
		<div class="col-12">
			<div>
				<h2>Add Post</h2>
			</div>

			<form method="POST" action="add_post.php" enctype="multipart/form-data">
	            <div class="form-group">
	              <label for="title">Post Title</label>
	              <input type="text"  name="post_title" class="form-control" required>
	            </div>
	            <div class="form-group">
	              <label for="category">Category</label>
	              <select name="post_cat" id="category" class="form-control" required>
	              	<?php

	              	$get_category_query = "SELECT * FROM categories";
	              	$result = mysqli_query($conn, $get_category_query);

	              	if(!$result) {
				        
				        die("QUERY FAILED ".mysqli_error($conn));
				        
				    } 

				    while($row = mysqli_fetch_assoc($result)) {

				    	$thy_cat_id = $row['cat_id'];
				    	$thy_cat_title = $row['cat_title'];

				    	//echoing each title
				    	echo "<option value='{$thy_cat_id}'>{$thy_cat_title}</option>";

				    }



	              	?>
	                <!-- <option value="">Web Development</option>
	                <option value="">Tech Gadgets</option>
	                <option value="">Business</option>
	                <option value="">Health & Wellness</option> -->
	              </select>
	            </div>
	            <div class="form-group">
	              <label for="author">Author</label>
	              <input type="text" name="post_author" id="author" class="form-control" required>
	            </div>
	            <div class="form-group">
                  <label for="post_status">Post Status</label>
                  <select class="form-control" name="post_status">
                    <option value="draft">Draft</option>
                    <option value="published">Publish</option>
                  </select>
                </div>
	            <div class="form-group">
	              <label for="image">Upload Image</label>
	              <div class="custom-file">
	                <input type="file" name="upload_image" class="custom-file-input" id="image">
	                <label for="image" class="custom-file-label">Choose File</label>
	              </div>
	              <small class="form-text text-muted">Max Size 3mb</small>
	            </div>
	            <div class="form-group">
	              <label for="video">Upload Video</label>
	              <div class="custom-file">
	                <input type="file" name="upload_video" class="custom-file-input" id="video">
	                <label for="video" class="custom-file-label">Choose File</label>
	              </div>
	            </div>
	            <div class="form-group">
	              <label for="audio">Upload Audio</label>
	              <div class="custom-file">
	                <input type="file" name="upload_audio" class="custom-file-input" id="audio">
	                <label for="audio" class="custom-file-label">Choose File</label>
	              </div>
	            </div>
	            <div class="form-group">
	              <label for="tags">Tags</label>
	              <input type="text" name="post_tags" id="tags" class="form-control" required>
	            </div>
	            <div class="form-group">
	              <label for="body">Post Content</label>
	              <textarea name="editor1" id="body" class="form-control" required></textarea>
	            </div>

          		<button class="btn btn-primary" type="submit" name="add_post" >Published Post</button>
	           
	          </form>


		</div>
	</div>
</div>



 <!-- FOOTER -->
   <?php include "./include/admin_footer.php"; ?>