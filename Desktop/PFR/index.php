
<!-- Index.php^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->

<?php
    include 'header.php';
    // include("classes/User.php");
// include("classes/Post.php");
// include("classes/Message.php");


    if(isset($_POST['post'])){
        $uploadOk = 1;
        $imageName = $_FILES['fileToUpload']['name'];
        $errorMessage = "";
        
        if($imageName != ""){
            $targetDir = "assets/images/posts/";
            $imageName = $targetDir . uniqid() . basename($imageName);
            $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);
            
            if($uploadOk){
                if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
                    //image Upload Okey
                    $errorMessage = "uploaded";
                }
                else{
                    $uploadOk = 0;
                    $errorMessage = "fail to upload";
                }
            }
        }
        
        if($uploadOk){
            $userLoggedIn = $_SESSION['username'];
            $user = new User($con, $userLoggedIn);  
            $con = mysqli_connect("localhost", "root", "", "ocean");


            $post = new Post($con, $userLoggedIn);
            $post->submitPost($_POST['post_text'], $imageName);
        }
        else{
            echo "<div style='text-align: center;' class='alert alert-danger'> $errorMessage </div>";
        }
    }

    $user_detail_query = mysqli_query($con,"select * from users where username='$userLoggedIn'");
    $user_array = mysqli_fetch_array($user_detail_query);
    $num_friends = (substr_count($user_array['friend_array'],","))-1;

?>

<div class="index-wrapper">
    <div class="info-box">
        <div class="info-inner">
            <div class="info-in-head">
                <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['cover_pic']; ?>"></a>
            </div>
            <div class="info-in-body">
                <div class="in-b-box">
                    <div class="in-b-img">
                        <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
                    </div>
                </div>
                <div class="info-body-name">
                    <div class="in-b-name">
                        <!-- <div><a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name'] . " " . $user['last_name']; ?></a> -->
                        </div>
                        <span><small><a href="<?php echo $userLoggedIn; ?>"><?php echo "@" . $user['username'] ?></a></small></span>
                    </div>
                </div>
            </div>
            <div class="info-in-footer">
                <div class="number-wrapper">
                    <div class="num-box">
                        <div class="num-head">
                            POSTS
                        </div>
                        <div class="num-body">
                            <?php echo $user['num_posts']; ?>
                        </div>
                    </div>
                    <div class="num-box">
                        <div class="num-head">
                            LIKES
                        </div>
                        <div class="num-body">
                            <span class="count-likes">
                                <?php echo $user['num_likes']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="num-box">
                        <div class="num-head">
                            Friends
                        </div>
                        <div class="num-body">
                            <?php echo $num_friends ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="post-wrap">
    <style>
    .post-wrap {
        background-color: #f2f2f2;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    
    .post-inner {
        display: flex;
        align-items: flex-start;
    }
   
 .post-h-left {
        margin-right: 10px;
    } 
     
    .post-h-img img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    
    /* .post-body {
        flex: 1;
    } */
    
    .post_form .status {
        width: 200%;
        height: 100px;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
        resize: none;
    } 
     
    .hash-box {
        margin-top: 10px;
    }
    
    .post-footer {
        margin-top: 10px;
    }
    
    .p-fo-left ul {
    position: absolute;
    display: flex;
    align-items: end;
    justify-content: flex-end;
}

    
    .p-fo-left ul input[type="file"] {
        display: none;
    }
    
    .p-fo-left ul label {
        cursor: pointer;
        margin-right: 10px;
    }
    
    .p-fo-left ul img {
        height: 30px;
    }
    
    .tweet-error {
        color: red;
    }
    
    #sub-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

 
</style>
        <div class="post-inner">
            <div class="post-h-left">
                <div class="post-h-img">
                    <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic'] ?>"></a>
                 </div>
            </div>
            
            <div class="post-body">
                <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
                    <textarea class="status" name="post_text" id="post_text" placeholder="Type Something here!" rows="4" cols="50"></textarea>
                    <div class="hash-box">
                        <ul>
                        </ul>
                    </div>
            </div>
                <div class="post-footer">
                    <div class="p-fo-left">
                        <ul>
                            <input type="file" name="fileToUpload" id="fileToUpload"/>
                            <label for="fileToUpload"> <img src="assets/images/camera.png" alt="" height="30px"></i> </label>
                            <span class="tweet-error"></span>
                            <input id="sub-btn" type="submit" name="post" value="SHARE">
                        </ul>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="show_post"> -->
        <?php 
            $post = new Post($con, $userLoggedIn) ;
            $post->indexPosts();
        ?>
    </div>
</div>
</body>
</html>