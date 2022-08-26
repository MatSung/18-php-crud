<?php
$parduotuveDatabase = new parduotuveDatabase();
// include("upload.php");
if($parduotuveDatabase->createItem('products')){
    header('Location: index.php?page=products&subpage=index');
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <input class="form-control" name="title" placeholder="Title">
            <input class="form-control" name="description" placeholder="Description">
            <input class="form-control" name="price" type="number" placeholder="Price">
            <select class="form-select" name="category_id">
                <?php
                var_dump($parduotuveDatabase->getValues('categories', 'id, title'));
                ?>
                <?php

                foreach ($parduotuveDatabase->getValues('categories', 'id, title') as $categoryItem) { ?>
                    <option value='<?php echo $categoryItem['id']; ?>'><?php echo $categoryItem["title"]; ?></option>
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
            <button class="btn btn-primary" type="submit" name="submit">Create</button>
        </form>
    </div>
</body>

</html>