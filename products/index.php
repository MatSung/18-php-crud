<?php $productsDatabase = new parduotuveDatabase(); ?>
<?php $settings = new parduotuveDatabase('settings'); ?>
<?php
    //if page number is not set or 0
    
    if (isset($_GET['page-number']) && $_GET['page-size'] != 0) {
        $current_page = $_GET['page-number'];
        //echo " ";
        $current_page_size = $_GET['page-size'];
        //echo " ";
        $current_page_amount = ceil(count($productsDatabase->parduotuve) / $current_page_size);
    } else {
        $current_page_amount = 1;
        $current_page = 1;
        $current_page_size = 0;
    }
    if(isset($_POST["filter_by_cat"])){
        $current_filter = $_POST["category_id"];
        $current_page = 1;
    }else if (isset($_GET["filter_by_cat"]) && $_GET["category_id"] > 0){
        $current_filter = $_GET["category_id"];
        $filter_enabled = 1;
    }
     else {
        unset($_GET['category_id']);
        $current_filter = 0;
    }

    ?>
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
                <option <?php if (isset($_REQUEST['filter_by_cat']) && $_REQUEST['category_id'] == $categoryItem['id']) echo 'selected="selected"'; ?> value='<?php echo $categoryItem['id']; ?>'><?php echo $categoryItem["title"]; ?></option>
            <?php } ?>
        </select>
        <button class="btn btn-primary" type="submit" name="filter_by_cat">Go</button>
    </form>
    <h2>Show per page</h2>
    <form method="GET">
        <!-- current page -->
        <input hidden value="products" name="page">
        <input hidden value="index" name="subpage">
        <input hidden value="" name="filter_by_cat">
        <input hidden value="<?php echo $current_filter ?>" name="category_id">
        <!-- select page amount -->
        <select style="width: 200px;" class="form-select" name="page-size">
            <?php
            for ($i = 0; $i < count($settings->parduotuve); $i++) {
            ?>
                <option <?php if (isset($_GET['page-size']) && $_GET['page-size'] == $settings->parduotuve[$i]['value']) echo 'selected="selected"'; ?> value="<?php echo $settings->parduotuve[$i]['value']; ?>"><?php echo $settings->parduotuve[$i]['name']; ?></option>
            <?php
            }
            ?>
        </select>
        <button class="btn btn-primary" type="submit" value="1" name="page-number">Go</button>
    </form>

    

    <p>amount of items: <?php echo count($productsDatabase->parduotuve); ?></p>
    <p><?php echo "you are on page: " .  $current_page . " out of " . $current_page_amount; ?></p>
    <form method="GET">
        <input hidden value="products" name="page">
        <input hidden value="index" name="subpage">
        <!-- if filter by cat is enabled -->
        <input hidden value="" name="filter_by_cat">
        <input hidden value="<?php echo $current_filter ?>" name="category_id">
        <input hidden value="<?php echo $current_page_size; ?>" name="page-size">
        <?php
        if ($current_page_size != 0) {
            for ($i = 1; $i <= $current_page_amount; $i++) { ?>

                <button name="page-number" value="<?php echo $i ?>" class="btn btn-<?php echo ($current_page == $i) ? "light" : "dark"; ?>"><?php echo $i; ?></button>

        <?php }
        }
        ?>
    </form>

    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
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
        <?php
        if (isset($_GET['page-number']) && $_GET['page-size'] != 0) {
            $productsDatabase->getItems($current_page, $current_page_size);
        } else {
            $productsDatabase->getItems();
        }


        ?>
        <?php $productsDatabase->deleteItem("products"); ?>

    </table>
</body>

</html>