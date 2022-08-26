<!DOCTYPE html>
<html lang="en">
<body class="sub-body">

    <h1>Produkts</h1>
    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php $productsDatabase = new parduotuveDatabase(); ?>
        <?php $productsDatabase->deleteItem("products"); ?>
        
    </table>
    <?php

    
    ?>
</body>
</html>