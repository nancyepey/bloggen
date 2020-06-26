<?php

//including the modal popup for delete confirmation
include("delete_modal.php");

//checking is the array is set
if(isset($_POST['checkBoxArray'])) {

    //using a foreach to loop through an array
    foreach ($_POST['checkBoxArray'] as $postValueId) {

        // echo $postValueId; checking

        //capturing the bulk_options from option tag and storing it in a variable
        $bulk_options = escape($_POST['bulk_options']);
        //checking if bulk_options is set in select tag ie if we are recieving the values
        //echo $bulk_options;

        //creating a switch statement for handling the different bulk options values
        switch ($bulk_options) {
            case 'published':

                //query to update the paticular post post status to published
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                //send the query
                $update_to_published_status = mysqli_query($conn, $query);

                //checking query
                confirmQuery($update_to_published_status);
                
                break;

            case 'draft':

                //query to update the paticular post post status to draft
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                //send the query
                $update_to_draft_status = mysqli_query($conn, $query);

                //checking query
                confirmQuery($update_to_draft_status);
                
                break;

            case 'delete':

                //query to delete the paticular post 
                //delete query
                $query = "DELETE FROM posts WHERE post_id={$postValueId}";
                //send the query
                $update_to_delete_status = mysqli_query($conn, $query);

                //checking query
                confirmQuery($update_to_delete_status);
                
                break;

            case 'clone':

                //query to clone post ie duplicate post 

                //query all from post with a particular id depending on which check box is click to mark
                $query = "SELECT * FROM posts WHERE post_id = {$postValueId} ";
                //sending the query in
                $select_post_query = mysqli_query($conn, $query);

                $post_image         = "";
                $post_video         = "";
                $post_audio         = "";

                //using a while loop to loop through the values ie fetching the data as an array
                while($row = mysqli_fetch_array($select_post_query)) {
                    //getting the values and assigning them into variables
                    $post_title         = $row['post_title'];
                    $post_category_id   = $row['post_cat_id'];
                    $post_date          = $row['post_date'];
                    //old way , no more author we use user now
                    //$post_author      = $row['post_author'];
                    $post_author        = $_SESSION['username'];
                    
                    //$post_status        = $row['post_status'];
                    $post_status        = 'draft';

                    $post_image         = $row['post_image'];
                    $post_video         = $row['post_video'];
                    $post_audio         = $row['post_audio'];


                    $post_tags          = $row['post_tags'];
                    $post_content       = $row['post_content'];
                    $post_views_count   = 0;
                    $post_comment_count = 0;


                    //note a way to out default values in table if values don't exist yet
                    /*
                    if(empty($post_tags)) {
                        $post_tags = "No Tags";
                    }
                    */

                }
                
                //query to insert data in posts
                //query to add post
                $query = "INSERT INTO posts(post_cat_id, post_title, post_author, post_date, post_image, post_video, post_audio, post_content, post_tags, post_comment_count, post_status, post_views_count)";
                //not using a single quote '' in {$post_category_id} since it will be a number not a string as the others
                //for date we are not sending a value but we are sending a function
                //values to insert
                $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}', now(),'{$post_image}', '{$post_video}', '{$post_audio}','{$post_content}','{$post_tags}', '{$post_comment_count}', '{$post_status}', '{$post_views_count}')";

                //sending the query into the database
                $copy_query = mysqli_query($conn, $query);

                //checking for errors in query
                if(!$copy_query) {
                    die("QUERY FAILED") . mysqli_error($conn);
                }

                
                break;
            
            default:
                # code...
                break;
        }

    }

}

?>

<form action="" method='post'>


    <table class="table table-bordered table-hover">

        <div id="bulkOptionsContainer" class="col-md-4">

            <select class="form-control" name="bulk_options" id="">

                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <!-- option to clone posts -->
                <option value="clone">Clone</option>
                
            </select>
            
        </div>

        <div class="col-md-4 mb-4">

            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>            
        </div>


            <thead>
                <tr>
                    <th><input id="selectAllBoxes" type="checkbox" name=""></th>
                    <th>Id</th>
                    <th>User</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Media</th>
                    <th>Tags</th>
                    <th>Comments</th>
                    <th>Date</th>
                    <th>View Post</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Views</th>
                </tr>
            </thead>
                            
            <tbody>
               <!-- Query to display posts from db -->
               <?php

               //getting the name of the current logged in user
               //$user = $_SESSION['username'];
               //using the function currentUser()
               $user = currentUser();
                                
                //creating the query variable where we will store our query. Selecting all the data (since we are using the *) from the desired table in this case is posts
               //this ORDER BY post_id DESC is to make sure the new posts are seen above ie from lastest or new posts to old or older posts
               
               $query = "SELECT posts.post_id, posts.post_author, posts.post_title, posts.post_cat_id, posts.post_status, posts.post_image, posts.post_video, posts.post_audio, ";
               $query .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";
               $query .= " FROM posts ";
               ////to show all post from any post user
               //$query .= " LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC";
               //to show post of the user logged in user
               $query .= " LEFT JOIN categories ON posts.post_cat_id = categories.cat_id WHERE posts.post_author ='$user' ORDER BY posts.post_id DESC";

                 //$mysqli_query($connection) here $mysqli_query() is the fxn we use to send the query
                 $select_posts= mysqli_query($conn, $query);

                 confirmQuery($select_posts);

                 //we need to display it to bring back all the values using a while loop
                 while($row = mysqli_fetch_assoc($select_posts)) {
                        //cat_id and cat_title are the name of the fields in the database in categories table
                        $post_id            = $row['post_id'];
                        $post_author        = $row['post_author'];
                        
                        $post_title         = $row['post_title'];
                        $post_category_id   = $row['post_cat_id'];
                        $post_status        = $row['post_status'];

                        $post_image         = $row['post_image'];
                        $post_video         = $row['post_video'];
                        $post_audio         = $row['post_audio'];

                        $post_tags          = $row['post_tags'];
                        $post_comment_count = $row['post_comment_count'];
                        $post_date          = $row['post_date'];
                        $post_views_count   = $row['post_views_count'];

                        //since the tables are join now (due to the join query) we can just pull the category title from here
                        $category_title     = $row['cat_title'];
                        $category_id        = $row['cat_id'];

                        echo "<tr>";
                    ?>

                    <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id ?>'></td>


                    <?php
                        // echo "<td><input class='checkBoxes' type='checkbox' name=''></td>";
                        echo "<td>{$post_id}</td>";

                        //better functionality
                        //display something if there's something or display something else if there's something else
                        //display author if there's author from comment section or if there's a user display user

                        //display post author
                        echo "<td>{$post_author}</td>";

                        
                        

                        echo "<td>{$post_title}</td>";
                                     
                     //to display the category
                        
                        echo "<td>{$category_title}</td>";
                                     
                        echo "<td>{$post_status}</td>";



                      //checking which media was uploaded and displaying it

                      if($post_image != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/images/{$post_image}' alt='image'></td>";
                        }
                        if($post_video != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/videos/vid_pic.jpg' alt='image'></td>";
                        }
                        if($post_audio != '') { 
                      
                            echo "<td><img class='img-responsive' width=100 src='./uploads/audios/aud_pic.jpg' alt='image'></td>";
                        }


                        echo "<td>{$post_tags}</td>";

                        //NEW WAY TO GET COMMENT COUNT NUMBER PER POST
                        //query to get everything from comment where comment post id equals post id
                        $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                        //sending query into db
                        $send_comment_query = mysqli_query($conn, $query);

                        //loop through the table and pull out all the comments related to that post
                        $row = mysqli_fetch_array($send_comment_query);

                        //pulling out the comment id of that specific post and assign it into a variable comment_id
                        //$comment_id = $row['comment_id'];

                        //putting this if statement to make sure we don't get an error if the post have no comment meaning comment id for a particular post is null
                        
                        //counting the comments
                        $count_comments = mysqli_num_rows($send_comment_query);

                        // echo "<td>{$post_comment_count}</td>"; //for old way of getting comment count
                        //sending the post id so that all the comment of that particular post can be seen
                        echo "<td><a href='post_comments.php?id=$post_id'>{$count_comments}</a></td>";


                        echo "<td>{$post_date}</td>";
                        echo "<td><a class='btn btn-primary' href='../blog-post.php?p_id={$post_id}' target='_blank'>View Post</a></td>";
                        echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";

                        ?>

                        <!-- We want to create our delete functionality and use POST using a form not just sending the id -->
                        <form method="post">

                            <!-- the first input will be a hidden input and will be used to send the post id -->
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

                            <!-- putting this inside a td since we are inside a table -->
                            <?php

                                echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';

                            ?>
                            
                        </form>


                        <?php

                        //using php and javascript to open up a confirmatory box 
                        //if click on yes post gets deleted if click on cancle nothing happen



                        echo "<td><a onClick=\" javascript: return confirm('Are you sure you want to reset post view count to 0!'); \" href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                        echo "</tr>";

                                    }
                                
                                ?>

            </tbody>
        </table>
    
</form>
                        
                        
<?php

//TO DELETE A POST


if(isset($_POST['delete'])) {
    
   
    //new method we checking for the hidden field value
    //no escape really needed since it coming from inside our application but we can just leave it there in case we have someone in application that still want to carryout a sql injection
    $the_post_id = escape($_POST['post_id']);
    
    //delete query
    $query = "DELETE FROM posts WHERE post_id={$the_post_id}";
    
    //sending the delete query
    $delete_query = mysqli_query($conn, $query);
    
    //refreshing the page so that we won't have to press the delete link twice for it to delete the post
    header("Location: posts.php"); //to refresh the page ie it will send thebasically refleshing the page

    
}

//to reset the post view count nummber
if(isset($_GET['reset'])) {
    
    $the_post_id = escape($_GET['reset']);
    
    //reset query
    // $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = {$the_post_id} ";
    //escaping the values, best practices
    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id =" . mysqli_real_escape_string($conn, $the_post_id) . "";
    
    //sending the reset query
    $reset_query = mysqli_query($conn, $query);
    
    //refreshing the page so that we won't have to press the reset link twice for it to reset the post view count of particular post with the id
    header("Location: posts.php"); //to refresh the page ie it will send thebasically refleshing the page

    
}

//to know or get the current user
function currentUser() {

    if(isset($_SESSION['username'])) {

        //if its set return username session
        return $_SESSION['username'];

    }

    //if it is not set return false
    return false;

}


?>


<!-- script to delete post from modal-->

<script>

    $(document).ready(function() {


        //creating an even, using jquery fxn on()
        $(".delete_link").on('click', function() {

            //alert("IT WORKS");

            //put the post id in rel attribute
            var id = $(this).attr("rel");
            //alert(id);

            //concatenating url with get request delete to post id
            //thus that's the delete link
            var delete_url = "posts.php?delete=" + id + "";

            //alert(delete_url);

            //targeting modal_delete_link so that when the delete link in modal is clicked
            //where this class is found, we are targeting thr href and putting delete_url inside it
            $(".modal_delete_link").attr("href", delete_url);

            //targeting the modal to open it
            $("#myModal").modal('show');

        });

    });
    
</script>            
                     
<!-- script to select all using checkboxes-->

<script>

    $(document).ready(function() {


        $('#selectAllBoxes').click(function(event) {

            //if checked
            if(this.checked) {

                //grabbing the class of check boxes and iterate thro the object
                $('.checkBoxes').each(function() {

                    //make all the check boxes true ie checked
                    this.checked = true;

                });

            } else {

                //grabbing the class of check boxes and iterate thro the object
                //make all the check boxes false ie unchecked
              $('.checkBoxes').each(function() {

                    //make all the check boxes false ie checked
                    this.checked = false;

                });

            }

        });

    });
    
</script>            
                            
                   
                  
                 
                
               
              
             
            
           
          
         