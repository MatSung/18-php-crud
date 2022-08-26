<?php
$parduotuveDatabase = new parduotuveDatabase();
// include("upload.php");
$item = $parduotuveDatabase->selectOneItem('products');

if($parduotuveDatabase->updateItem('products')){
    header('Location: index.php?page=products&subpage=index');
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
            <input class="form-control" name="price" type="number" placeholder="Price" value="<?php echo $item['price'] ?>">
            <select class="form-select" name="category_id">
                <?php
                foreach ($parduotuveDatabase->getValues('categories', 'id, title') as $categoryItem) { ?>
                    <option value='<?php echo $categoryItem['id']; ?>' <?php if($categoryItem['id'] == $item['category_id']) echo 'selected="selected"'; ?>><?php echo $categoryItem["title"]; ?></option>
                <?php } ?>
                ?>

            </select>

            <!-- image -->
            <?php
            //var_dump($parduotuveDatabase->getValues('categories','id, title'));
            ?>
            <label for="myfile">Select an image:</label>
            <input type="file" id="myfile" name="myfile" disabled>
            <br />
            <button class="btn btn-primary" type="submit" name="update">Update</button>
        </form>
    </div>
</body>

</html>