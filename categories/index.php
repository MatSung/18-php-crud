<!DOCTYPE html>
<html lang="en">
<body class="sub-body">
    <h1>Categories</h1>
    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php $productsDatabase = new parduotuveDatabase("categories"); ?>
        <?php $productsDatabase->deleteItem("categories"); ?>
        
    </table>
</body>
</html>