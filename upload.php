<?php

$allowedType = ['jpg', 'png', 'gif'];
$errorsArray['error'] = [];
$files = new FilesystemIterator('uploads/');

$uploadDir = 'uploads/';

//Upload part
if (!empty($_FILES)) {
    $errorsArray = [];
    //foreach files uploaded
    foreach ($_FILES['avatar']['name'] as $key => $value){
        //get file name without extention, and get extention
        $fileName = explode('.', $value);
        $fileType = pathinfo($value, PATHINFO_EXTENSION);
        //error tests
        if ($_FILES['avatar']['error'][$key] == 4)
            $errorsArray['error'][$key] = 'No such file seleted' . "<br>";
        elseif ( !in_array($fileType, $allowedType))
            $errorsArray['error'][$key] = 'Wrong type fo file for ' . "$value<br>";
        elseif ($_FILES['avatar']['error'][$key] == 2)
            $errorsArray['error'][$key] = 'File size must be under 1Mo on ' . "$value<br>";
        elseif ($_FILES['avatar']['error'][$key] != 0)
            $errorsArray['error'][$key] = 'UPLOAD_ERR value: ' . $_FILES['avatar']['error'][$key] . "<br>";
        //if no error
        if (empty($errorsArray['error'][$key])){
            $uploadFile = $uploadDir . uniqid() . $fileName[0] . '.' . $fileType;
            move_uploaded_file($_FILES['avatar']['tmp_name'][$key], $uploadFile);
            //redirection
            header('Location : upload.php');
        }
    }
}

//Delete part
if (!empty($_POST['delete'])){
    //if file not exist
    if (!file_exists($_POST['delete'])){
        $errorsArray['delete'] = 'Unknown file to delete';
    }
    //if no error
    if (empty($errorsArray['delete'])){
        unlink($_POST['delete']);
        //redirection
        header('Location : upload.php');
    }
}
?>
<!--upload form-->
<form action="" method="post" enctype="multipart/form-data">
    <label for="uploads">Upload Files</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    <input type="file" id="uploads" name="avatar[]" multiple="multiple"/>
    <button type="submit">Upload</button>
</form>
<!--upload error message-->
<?php if (isset($errorsArray['error'])) : ?>
    <?php foreach ($errorsArray['error'] as $key => $value): ?>
        <p style="color: red"><?= isset($errorsArray['error'][$key]) ? $errorsArray['error'][$key] : '' ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<hr>
<!--diplay upload with path for each files in folder-->
<?php foreach ($files as $file) : ?>
    <figure>
        <img src="<?= $file ?>" style="height: 150px">
        <figcaption><?= $file ?></figcaption>
        <form action="" method="post">
            <input type="hidden" id="delete" name="delete" value="<?= $file ?>">
            <button type="submit">Delete</button>
        </form>
        <!--delete error message-->
        <p style="color: red"><?= isset($errorsArray['delete']) ? $errorsArray['delete'] : '' ?></p>
    </figure>
<?php endforeach; ?>
