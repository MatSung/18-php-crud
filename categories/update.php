<?php
$parduotuveDatabase = new parduotuveDatabase();
// include("upload.php");
$item = $parduotuveDatabase->selectOneItem('categories');

if($parduotuveDatabase->updateItem('categories')){
    header('Location: index.php?page=categories&subpage=index');
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <div class="container">
        <h1>you are editing id: <?php echo $_GET['id']; ?></h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <input class="form-control" name="title" placeholder="Title" value="<?php echo $item['title'] ?>">
            <input class="form-control" name="description" placeholder="Description" value="<?php echo $item['description'] ?>">
            <button class="btn btn-primary" type="submit" name="update">Update</button>
        </form>
    </div>
</body>

</html>