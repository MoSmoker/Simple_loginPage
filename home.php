<?php
    include('config.php');
    session_start();
    $user_id = $_SESSION['user_id'];
    if(!isset($user_id)){
        header('location:login.php');
    }else{}
    if(isset($_GET['logout'])){
        unset($user_id);
        session_destroy();
        header('location:login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="profile">
            <?php
                $select = mysqli_query($connection, "SELECT * FROM `user_form` WHERE id=$user_id") or die("query failed");
                if(mysqli_num_rows($select) > 0){
                    $fetch = mysqli_fetch_assoc($select);
                }
                if($fetch['image'] == 'images/'){
                    echo "<img src='images/default-avatar-icon.jpg'";
                }else{
                    echo "<img src='" . $fetch['image'] . "' alt='Uploaded Image'>";
                }
            ?>
            <h3><?php echo $fetch['name']; ?></h3>
            <a href="update_profile.php" class="btn" >Update profile</a>
            <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">Logout</a>
            <p>new <a href="login.php">login</a> or <a href="register.php">Register now!</a></p>
        </div>
    </div>
</body>
</html>