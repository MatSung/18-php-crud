<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <table class="table table-striped ">
        <tr>
            <th>ID
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='desc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_id'>
                        <i class="fa-solid fa-angles-up"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='asc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_id'>
                        <i class="fa-solid fa-angles-down"></i></button>
                </form>
            </th>
            <th>Title
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='desc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_title'>
                        <i class="fa-solid fa-angles-up"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='asc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_title'>
                        <i class="fa-solid fa-angles-down"></i></button>
                </form>
            </th>
            <th>Description
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='desc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_desc'>
                        <i class="fa-solid fa-angles-up"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='asc'>
                    <button class='btn btn-primary' type='submit' name='sort_by_desc'>
                        <i class="fa-solid fa-angles-down"></i></button>
                </form>
            </th>
            <th>Actions</th>
        </tr>
        <?php $productsDatabase = new parduotuveDatabase("categories"); ?>
        <?php $productsDatabase->getItems(); ?>
        <?php $productsDatabase->deleteItem("categories"); ?>

    </table>
</body>

</html>