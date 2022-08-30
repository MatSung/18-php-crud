<?php
$parduotuveDatabase = new parduotuveDatabase('settings');
// include("upload.php");
$item = $parduotuveDatabase->selectOneItem('settings');

if($parduotuveDatabase->updateItem('settings')){
    header('Location: index.php?page=settings&subpage=index');
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <div class="container">
        <h1>you are editing id: <?php echo $_GET['id']; ?></h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <input class="form-control" name="value" placeholder="Value" value="<?php echo $item['value'] ?>">
            <input class="form-control" name="name" placeholder="Name" value="<?php echo $item['name'] ?>">
            <button class="btn btn-primary" type="submit" name="update">Update</button>
        </form>
    </div>
</body>

</html>