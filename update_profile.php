<?php
    include('config.php');
    session_start();
    $user_id = $_SESSION['user_id'];
    $change_pass = false;
    if(isset($_POST['update_profile'])){
        $new_name = mysqli_real_escape_string($connection, $_POST['update_username']);
        $new_email = mysqli_real_escape_string($connection, $_POST['update_email']);

        mysqli_query($connection, "UPDATE `user_form` SET name='$new_name', email='$new_email' WHERE id=$user_id") or die("query failed");

        $old_password = $_POST['old_password'];
        $previous_password = mysqli_real_escape_string($connection, md5($_POST['previous_password']));
        $new_password = mysqli_real_escape_string($connection, md5($_POST['new_password']));
        $confirm_password = mysqli_real_escape_string($connection, md5($_POST['confirm_password']));

        if(!empty($previous_password) || !empty($new_password) || !empty($confirm_password)){
            if($old_password != $previous_password){
                $message[] = "Previous password is incorrect!";
            }elseif($new_password != $confirm_password){
                $message[] = "confirm password is incorrect!";
            }else{
                mysqli_query($connection, "UPDATE `user_form` SET password='$new_password' WHERE id=$user_id") or die("query failed");
                $message[] = "password has changed successfully";
                $change_pass = true;
            }
        }
        $image = $_FILES['update_image'];
        $image_location = $image['tmp_name'];
        $image_name = $image['name'];
        $image_size = $image['size'];
        $image_up = "images/".$image_name;
        if(!empty($image_name)){
            if($image_size > 2000000){
                $message[] = "Avatar size if too large";
            }else{
                $image_update_query = mysqli_query($connection, "UPDATE `user_form` SET image='$image_up' WHERE id=$user_id") or die("query failed");
                if($image_update_query){
                    move_uploaded_file($image_location, $image_up);
                    $message[] = "Avatar changed successfully";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update user</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    .passChangedMessage{
        margin:15px 0;
        width: 100%;
        border-radius: 5px;
        padding: 10px;
        text-align: center;
        background-color: #2980b9;
        color: var(--white);
        font-size: 20px;
    }
    </style>
</head>
<body>
    <div class="update_profile">
        <?php
            $select = mysqli_query($connection, "SELECT * FROM `user_form` WHERE id=$user_id") or die("query failed");
            if(mysqli_num_rows($select) > 0){
                $fetch = mysqli_fetch_assoc($select);
            }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <?php
                if($fetch['image'] == 'images/'){
                    echo "<img src='images/default-avatar-icon.jpg'>";
                }else{
                    echo "<img src='" . $fetch['image'] . "' alt='Uploaded Image'>";
                }
                if(isset($message)){
                    foreach($message as $message){
                        if($change_pass === false){
                            echo "<div class='message'>$message</div>";
                        }else{
                            echo "<div class='passChangedMessage'>$message</div>";
                        }
                    } 
                }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>username: </span>
                    <input type="text" name="update_username" class="box" value="<?php echo $fetch['name']?>">
                    <span>Email address: </span>
                    <input type="email" name="update_email" class="box" value="<?php echo $fetch['email']?>">
                    <span>change Avatar: </span>
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box" style="cursor: pointer;">
                </div>
                <div class="inputBox">
                    <input type="hidden" name="old_password" value="<?php echo $fetch['password']?>">
                    <span>previous password: </span>
                    <input type="password" name="previous_password" class="box" placeholder="Enter previous password">
                    <span>new password: </span>
                    <input type="password" name="new_password" class="box" placeholder="Enter new password">
                    <span>confirm password: </span>
                    <input type="password" name="confirm_password" class="box" placeholder="repeat new password">
                </div>
            </div>
            <input type="submit" name="update_profile" value="update profile" class="btn">
            <a href="home.php" class="delete-btn">Back and cancel</a>
        </form>
    </div>
</body>
</html>