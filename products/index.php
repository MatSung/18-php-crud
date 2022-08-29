<?php $productsDatabase = new parduotuveDatabase(); ?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">

    <h1>Produkts</h1>
    <h2>Filter by category</h2>
    <form method="POST">
        <select style="width: 200px;" class="form-select" name="category_id">
            <option value="0">None</option>
            <?php
            foreach ($productsDatabase->getValues('categories', 'id, title') as $categoryItem) { ?>
                <option value='<?php echo $categoryItem['id']; ?>'><?php echo $categoryItem["title"]; ?></option>
            <?php } ?>
        </select>
        <button class="btn btn-primary" type="submit" name="filter_by_cat">Go</button>
    </form>
    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <!-- filter by category by making a select here
                 it's very simple, only select stuff where category = what you chose in the select -->
            <th>Category
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='desc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_cat'>
                        <i class="fa-solid fa-angles-down"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='asc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_cat'>
                        <i class="fa-solid fa-angles-up"></i></button>
                </form>
            </th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php $productsDatabase->getItems(); ?>
        <?php $productsDatabase->deleteItem("products"); ?>

    </table>
</body>

</html>