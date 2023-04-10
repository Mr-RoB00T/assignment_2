<?php
$title = 'Posts'; // set page title BEFORE linking header as header expects this var
require('shared/header.php');
?>
    <main>
        <h1>Posts</h1>
        <?php        
        if (!empty($_SESSION['user'])) {
            echo '<a href="post-details.php">Add a New Post</a>';
        }        
        //try {
            // connect to db
            require('shared/db.php');

            // set up the SQL SELECT command
            $sql = "SELECT * FROM posts ORDER BY dateCreated DESC";

            // if there is a user param in the url, use it as a filter
            if (!empty($_GET['user'])) {
                $sql = "SELECT * FROM posts WHERE user = :user 
                     ORDER BY dateCreated DESC";
            }

            // execute the select query
            $cmd = $db->prepare($sql);

            // bind the username param if viewing 1 user's posts only
            if (!empty($_GET['user'])) {
                $cmd->bindParam(':user', $_GET['user'], PDO::PARAM_STR, 50);
            }

            $cmd->execute();

            // store the query results in an array. use fetchAll for multiple records, fetch for 1.
            $posts = $cmd->fetchAll();

            /*echo '<table>
            <thead><th>Body</th><th>User</th><th>Date</th></thead>';*/

            // display post data in a loop. $posts = all data, $post = the current item in the loop
            foreach ($posts as $post) {
                echo '<article>
                <h2>
                    <a href="posts.php?user=' . $post['user'] . '">' . $post['user'] . '</a></h2>
                <p>' . $post['dateCreated'] . '</p>
                <p>' . $post['body'] . '</p>';

                if (!empty($post['photo'])) {
                    echo '<div><img src="img/' . $post['photo'] . '" alt="Post Photo" /></div>';
                }

                // access check. 1 - is user logged in?  2. does user own this post?
                if (!empty($_SESSION['user'])) {
                    if ($post['user'] == $_SESSION['user']) {
                        echo '<a href="edit-post.php?postId=' . $post['postId'] . '">Edit</a>
                        <a onclick="return confirmDelete();"
                        href="delete-post.php?postId=' . $post['postId'] .'
                        ">Delete</a>';
                    }
                }                 
                    
                echo '</article>';

                /*echo '<tr>
                <td>' . $post['body'] . '</td>
                <td>' . $post['user']. '</td>
                <td>' . $post['dateCreated']. '</td>
            </tr>';*/
            }

            // close table
            //echo '</table>';

            // disconnect
            $db = null;
        /*}
        catch (Exception $error) {
            header('location:error.php');
            exit();
        }*/
        ?>
    </main>
<?php require('shared/footer.php'); ?>