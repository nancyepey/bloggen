<?php

if(isset($_GET['p_id'])) {
    
    $the_post_id = escape($_GET['p_id']);
    
}

//creating the query variable where we will store our query. Selecting all the data (since we are using the *) from the desired table in this case is posts
$query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
//$mysqli_queryquery, n we tquery
$select_posts_by_id= mysqli_query($conn, $query);

//we need to values using a while loop
while($row = mysqli_fetch_assoc($select_posts_by_id)) {
       //cat_categories table
       $post_id            = $row['post_id'];
       $post_author        = $row['post_author'];
       
       $post_title         = $row['post_title'];
       $post_category_id   = $row['post_cat_id'];
       $post_status        = $row['post_status'];
       $post_image         = $row['post_image'];
       $post_audio         = $row['post_audio'];
       $post_video         = $row['post_video'];
       $post_content       = $row['post_content'];
       $post_tags          = $row['post_tags'];
       $post_comment_count = $row['post_comment_count'];
       $post_date          = $row['post_date'];
}

//checking if the update_post btn is set
if(isset($_POST['update_post'])) {

  //setting defaults
    $post_image = '';
    $post_audio = '';
    $post_video = '';
    
    //grabing the fields in form
    //$post_author      = $_POST['post_author'];
    //NEW adding user
    $post_user        = escape($_POST['post_user']);
    $post_title       = escape($_POST['post_title']);
    $post_category_id = escape($_POST['post_category']);
    $post_status      = escape($_POST['post_status']);

    

    $post_content     = escape($_POST['post_content']);
    $post_tags        = escape($_POST['post_tags']);

   

    //getting the image
    // File upload path
    $targetDir_img = "./uploads/images/";
    // $file_image = basename($_FILES["file"]["name"]);
    // $file_image = basename($_FILES["image"]["name"]);
    $post_image = basename(escape($_FILES['upload_image']['name']));
    $targetFilePath_img = $targetDir_img . $post_image;
    $fileType_img = pathinfo($targetFilePath_img,PATHINFO_EXTENSION);

    //getting the audios
    // File upload path
    $targetDir_aud = "./uploads/audios/";
    $post_audio = basename(escape($_FILES['upload_audio']['name']));
    $targetFilePath_aud = $targetDir_aud . $post_audio;
    $fileType_aud = pathinfo($targetFilePath_aud,PATHINFO_EXTENSION);

    //getting the videos
    // File upload path
    $targetDir_vid = "./uploads/videos/";
    $post_video = basename(escape($_FILES['upload_video']['name']));
    $targetFilePath_vid = $targetDir_vid . $post_video;
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

              //upload query
              $query = "UPDATE posts SET ";
              $query .="post_title  = '{$post_title}', ";
              $query .="post_cat_id = '{$post_category_id}', ";
              //in date we are using the fxn now(), wc gives the date of today 
              $query .="post_date   =  now(), ";
              // manually putting author
              $query .="post_author = '{$post_author}', ";
              
              // $query .= "post_status = '{$post_status}', ";
              $query .="post_status = '{$post_status}', ";
              $query .="post_tags   = '{$post_tags}', ";
              $query .="post_content= '{$post_content}', ";

              $query .="post_image  = '{$post_image}', ";
              $query .="post_audio  = '{$post_audio}', ";
              $query .="post_video  = '{$post_video}' ";

              //not a string so no ''
              $query .="WHERE post_id = {$the_post_id} ";

              

              //sending query
              $update_post = mysqli_query($conn, $query);

              //using the fxn to make sure the query works 
              confirmQuery($update_post);
   


                if($update_post){
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


  
   

    echo "<p class='bg-success'>Post Updated. <a href='../blog-post.php?p_id={$the_post_id}' target='_blank'>View Post</a> or <a href='posts.php' target='_blank'> Edit More Posts</a></p>";

    
}

?>

  <form action="" method="post" enctype="multipart/form-data">
   
    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title">
    </div>
    
    <div class="form-group">
<!--    display category from db in drop down menu-->
   <label for="post_category">Post Category</label>
   <select class=form-control name="post_category" id="">
      
      <?php
        
        //display category from db in drop down menu
       //query to get the categories, so that we up view it
       $query = "SELECT * FROM categories";
       //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query, n we to pass in the connection and the query
    $select_categories = mysqli_query($conn, $query);
       
       confirmQuery($select_categories);

    //we need to display it to bring back all the values using a while loop
    while($row = mysqli_fetch_assoc($select_categories)) {
       //cat_id and cat_title are the name of the fields in the database in categories table
         $cat_id =  $row['cat_id'];
          $cat_title = $row['cat_title'];
        
        //displaying the ccategories
        //echo "<option value='{$cat_id}'>{$cat_title}</option>";

        if($cat_id == $post_category_id) {

          //displaying the current category first in option
          echo "<option selected value='{$cat_id}'>{$cat_title}</option>";

        } else {

          //displaying the ccategories
          echo "<option value='{$cat_id}'>{$cat_title}</option>";

        }

    }

       
        
    ?>
      
     
       
   </select>
    
    
    </div>
    
    <!-- <div class="form-group">
        <label for="author">Post Author</label>
        <input value="<?php //echo $post_author; ?>" type="text" class="form-control" name="post_author">
    </div> -->

    <div class="form-group">
    <!--    display category from db in drop down menu-->
       <label for="user">Users</label>
       <select class=form-control name="post_user" id="">

        <!-- <?php //echo "<option value='{$post_author}'>{$post_author}</option>"; ?> -->

          <?php

            //display category from db in drop down menu
           //query to get the users, so that we up view it
           $users_query = "SELECT * FROM users";
           //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query, n we to pass in the connection and the query
        $select_users = mysqli_query($conn, $users_query);

           confirmQuery($select_users);

        //we need to display it to bring back all the values using a while loop
        while($row = mysqli_fetch_assoc($select_users)) {
     //user_id and username are the name of the fields in the database in users table
       $user_id    =  $row['user_id'];
        $username  =  $row['username'];

            //sending id will only display id
            //echo "<option value='{$user_id}'>{$username}</option>";
            //sending username to display username
            // echo "<option value='{$user_id}'>{$username}</option>";
        if($username == $post_author) {

          //displaying the current category first in option
          echo "<option selected value='{$user_id}'>{$username}</option>";

        } else {

          //displaying the ccategories
          echo "<option value='{$user_id}'>{$username}</option>";

        }

        }



        ?>

       </select>

    
    </div>


     <div class="form-group">
        <label for="post_status">Post Status</label>

        <select class="form-control" name="post_status" id="">

          <option name="post_status"  value='<?php echo $post_status; ?>'><?php echo $post_status; ?></option>

          <?php

          if($post_status == 'published') {

            //display draft
            echo "<option value='draft'>Draft</option>";

          } else {

            //display draft
            echo "<option value='published'>Publish</option>";

          }

          ?>

          
        </select>
        
    </div>


    
   <!--  <div class="form-group">
        <label for="post_status">Post Status</label>
        <input value="<?php //echo $post_status; ?>" type="text" class="form-control" name="post_status">
    </div> -->
    
    <div class="form-group">
       <label for="post_image">Post Image</label>
       <?php

       if($post_image != '') { 
                      
          echo "<td><img class='img-responsive' width=100 src='./uploads/images/{$post_image}' alt='image'></td>";
         }

       ?>
        <!-- <img width="100" src="./uploads/images/<?php// echo $post_image; ?>" alt=""> -->
        <input type="file" class="form-control"  name="upload_image">
    </div>
    <div class="form-group">
       <label for="post_image">Post Video</label>
       <?php

       if($post_video != '') { 
                      
          echo "<td><img class='img-responsive' width=100 src='./uploads/videos/vid_pic.jpg' alt='image'></td>";
         }

       ?>
        <!-- <img width="100" src="./uploads/video/<?php //echo $post_video; ?>" alt=""> -->
        <p><?php echo $post_video; ?></p>
        <div class="custom-file">
          <input type="file" name="upload_video" class="custom-file-input" id="video">
          <label for="video" class="custom-file-label">Choose File</label>
        </div>
    </div>
    <div class="form-group">
       <label for="post_image">Post Audio</label>
       <?php

       if($post_audio != '') { 
                      
          echo "<td><img class='img-responsive' width=100 src='./uploads/audios/aud_pic.jpg' alt='image'></td>";
         }

       ?>
        <!-- <img width="100" src="./uploads/audio/<?php //echo $post_audio; ?>" alt=""> -->
        <p><?php echo $post_audio; ?></p>
        <div class="custom-file">
          <input type="file" name="upload_audio" class="custom-file-input" id="audio">
          <label for="audio" class="custom-file-label">Choose File</label>
        </div>
    </div>
    
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>
    
    <div class="form-group">
        <label for="post_content">Post Content</label>
        <!-- adding id of body in textarea for ckeditor to use -->
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"><?php echo $post_content; ?></textarea>
    </div>
    
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div> 
    
</form>