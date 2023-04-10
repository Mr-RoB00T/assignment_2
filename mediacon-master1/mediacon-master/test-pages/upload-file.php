<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    // reference the uploaded file
    $userFile = $_FILES['userFile'];

    // file name
    $name = $userFile['name'];
    echo 'Name: ' . $name . '<br />';

    // size in bytes (1 kb = 1024 bytes)
    $size = $userFile['size'];
    echo 'Size: ' . $size . '<br />';

    // temp location in server cache
    $tmp_name = $userFile['tmp_name'];
    echo 'Temp Name: ' . $tmp_name . '<br />';

    // file type
    //$type = $userFile['type']; // dangerous - do not use (can be fooled)
    $type = mime_content_type($tmp_name);
    echo 'Type: ' . $type . '<br />';

    // use the session object to create a unique name. eg. photo1.png => as98df723-photo1.png
    session_start();
    $name = session_id() . '-' . $name;

    // move file to uploads folder
    move_uploaded_file($tmp_name, 'uploads/'. $name);

    // show the caption entered by the user in the form
    $caption = $_POST['caption'];
    echo 'Caption: ' . $caption . '<br />';

    ?>
</body>
</html>