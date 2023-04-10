<?php
require('shared/auth.php');

$title = 'Edit Your Recipe';
require('shared/header.php');
?>
    <main>
        <?php 
        try {
            // get the recipeId from the url parameter using $_GET
            $recipeId = $_GET['recipeId'];
            if (empty($recipeId)) {
                header('location:404.php');
                exit();
            }

            // connect to the database
            require('shared/db.php');

            // set up & run SQL query to fetch the selected recipe record.  fetch for 1 record only
            $sql = "SELECT * FROM recipes WHERE recipeId = :recipeId";
            $cmd = $db->prepare($sql);
            $cmd->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $cmd->execute();
            $recipe = $cmd->fetch();

            // check query returned a valid recipe record
            if (empty($recipe)) {
                header('location:404.php');
                exit();
            }

            // access control check: is logged user the owner of this recipe?
            if ($recipe['user'] != $_SESSION['user']) {
                header('location:403.php');  // 403 = HTTP Forbidden Error
                exit();
            }
        }
        catch (Exception $error) {
            header('location:error.php');
            exit();
        }
        ?>
        <h1>Edit Recipe</h1>
        <form action="update-recipe.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo $recipe['title']; ?>" required maxlength="50" />
            </fieldset>
            <fieldset>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required maxlength="1000"><?php echo $recipe['description']; ?></textarea>
            </fieldset>
            <fieldset>
                <label for="ingredients">Ingredients:</label>
                <textarea name="ingredients" id="ingredients" required maxlength="4000"><?php echo $recipe['ingredients']; ?></textarea>
            </fieldset>
            <fieldset>
                <label for="instructions">Instructions:</label>
                <textarea name="instructions" id="instructions" required maxlength="4000"><?php echo $recipe['instructions']; ?></textarea>
            </fieldset>
            <fieldset>
                <label for="photo">Photo:</label>
                <input type="file" name="photo" accept=".png,.jpg" />
            </fieldset>
            <?php
            if (!empty($recipe['photo'])) {
                echo '<img src="img/' . $recipe['photo'] . '" alt="Recipe Photo" />';
            }
            ?>
            <button class="btnOffset">Update</button>
            <input name="recipeId" id="recipeId" value="<?php echo $recipeId; ?>" type="hidden" />
            <input name="currentPhoto" value="<?php echo $recipe['photo']; ?>" type="hidden" />
        </form>
    </main>
<?php require('shared/footer.php'); ?>
