<?php 
    include('config.php');
    if(isset($_POST['submit'])){
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($connection, md5($_POST['confirm_password']));
        $image = $_FILES['image'];
        $image_location = $image['tmp_name'];
        $image_name = $image['name'];
        $image_size = $image['size'];
        $image_up = "images/".$image_name;

        $select = mysqli_query($connection, "SELECT * FROM `user_form` WHERE email='$email' AND password='$password'") or die("query failed");

        if(mysqli_num_rows($select) > 0){
            $message[] = "user already exists"; 
        }else{
            if($password != $confirm_password){
                $message[] = "confirm password not matched!"; 
            }elseif($image_size > 2000000){
                $message[] = "image size is too large!"; 
            }else{
                $insert = mysqli_query($connection, "INSERT INTO `user_form`(`name`, `email`, `password`, `image`) VALUES ('$name','$email','$password','$image_up')") or die("query failed");

                if($insert){
                    move_uploaded_file($image_location, $image_up);
                    $message[] = "Registered successfully"; 
                    header('location:login.php');
                }else{
                    $message[] = "Registered failed, something wrong happened";
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
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <style>.form-container{align-items: center;}</style>
</head>
<body>
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Register now </h3>
            <?php 
                if(isset($message)){
                    foreach($message as $message){
                        echo "<div class='message'>$message</div>";
                    } 
                }
            ?>
            <input type="text" class="box" name="name" placeholder="username" required>
            <input type="email" class="box" name="email" placeholder="Email address" required>
            <input type="password" class="box" name="password" placeholder="password" required>
            <input type="password" class="box" name="confirm_password" placeholder="confirm password" required>
            <input type="file" class="box" accept="image/jpg, image/jpeg, image/png" name="image" style="cursor: pointer;">
            <input type="submit" name="submit" value="Register now" class="btn">
            <p>Already have an account?<a href="login.php"> Login now!</a></p>
        </form>
    </div>    
</body>
</html>