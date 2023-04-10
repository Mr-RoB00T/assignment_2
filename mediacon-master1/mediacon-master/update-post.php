<?php
require('shared/auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updating your Post...</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/app.css" />
</head>
<body>
    <header>
        <h1>
            <a href="#">
                MediaCon
            </a>
        </h1>
        <nav>
            <ul>
                <li><a href="posts.php">Posts</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        //try {
            // capture the form body input using the $_POST array & store in a var
            $body = $_POST['body'];
            $user = $_SESSION['user']; //$_POST['user'];
            $postId = $_POST['postId']; // hidden input w/PK
            $photo = $_FILES['photo']; // uploaded file if any

            // calculate the date and time with php
            date_default_timezone_set("America/Toronto");
            $dateCreated = date("y-m-d h:i");
            //echo $dateCreated;
            //echo $body;

            // lesson 4 - add validation before saving. Check 1 at a time for descriptive errors.
            $ok = true;  // start with no validation errors

            if (empty($body)) {
                echo '<p class="error">Post body is required.</p>';
                $ok = false; // error happened - bad data
            }

            if (empty($user)) {
                echo '<p class="error">User is required.</p>';
                $ok = false; // error happened - bad data
            }

            // if a photo was uploaded, validate & save it 
            if (!empty($photo['name'])) {
                $tmp_name = $photo['tmp_name'];
                
                // ensure file is jpg or png
                $type = mime_content_type($tmp_name);
                if ($type != 'image/png' && $type != 'image/jpeg') {
                    echo 'Please upload a .png or .jpg';
                    $ok = false;
                }

                // create a unique name and save the photo
                $name = session_id() . '-' . $photo['name'];
                move_uploaded_file($tmp_name, 'img/' . $name);
            }
            else {
                // no new photo uploaded, keep current photo name
                $name = $_POST['currentPhoto'];
            }

            // only save to db if $ok has never been changed to false
            if ($ok == true) {
                // connect to the db using the PDO library
                require('shared/db.php');
                /*if ($db) {
                echo 'Connected';
            }
            else {
                echo 'Connection Failed';
            }*/

                // set up an SQL UPDATE.  We MUST HAVE A WHERE CLAUSE
                $sql = "UPDATE posts SET body = :body, user = :user, 
                dateCreated = :dateCreated, photo = :photo WHERE postId = :postId";

                // map each input to the corresponding db column
                $cmd = $db->prepare($sql);
                $cmd->bindParam(':body', $body, PDO::PARAM_STR, 4000);
                $cmd->bindParam(':user', $user, PDO::PARAM_STR, 100);
                $cmd->bindParam(':dateCreated', $dateCreated, PDO::PARAM_STR);
                $cmd->bindParam(':postId', $postId, PDO::PARAM_INT);
                $cmd->bindParam(':photo', $name, PDO::PARAM_STR, 100);

                // execute the insert
                $cmd->execute();

                // disconnect
                $db = null;

                // show the user a message
                echo '<h1>Post Updated</h1>
                    <p><a href="posts.php">See the updated feed</a></p>';
            }
       /* }
        catch (Exception $error) {
            header('location:error.php');
            exit();
        } */
        ?>
    </main>
</body>
</html>