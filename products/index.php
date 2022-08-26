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
            <!-- filter by category by making a select here -->
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
        <?php $productsDatabase = new parduotuveDatabase(); ?>
        <?php $productsDatabase->deleteItem("products"); ?>

    </table>
    <?php


    ?>
</body>

</html>