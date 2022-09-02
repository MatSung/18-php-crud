<?php
//if page number is not set or 0
// lets do all with get and cookies
// if we post we make cookies and if we don't post we check cookies

// make selection in construct
// get items with page

// cookies contain - [category_id => #, sort_type => ASC/DESC, sort_col => "categories"]
$cookieName = "filters";

//make default cookie container
$cookieContainer = array(
    "category_id" => 0,
    "sort_type" => "ASC",
    "sort_col" => "id",
    "page_size" => 0
);

//if cookies are already set then overwrite the cookie container
if (isset($_COOKIE[$cookieName])) {
    $previousCookies = json_decode($_COOKIE[$cookieName], true);
    $cookieContainer = array(
        "category_id" => $previousCookies["category_id"],
        "sort_type" => $previousCookies["sort_type"],
        "sort_col" => $previousCookies["sort_col"],
        "page_size" => $previousCookies["page_size"]
    );
    if(isset($previousCookies["search_criteria"])){
        //echo "i have received cookie of search criteria ".$previousCookies["search_criteria"];
        $cookieContainer["search_criteria"] = $previousCookies["search_criteria"];
    }
}

//if we posted that we want different sort by cat then we replace cookie container
if (isset($_POST["sort_by_cat"])) {
    $cookieContainer["sort_type"] = $_POST["sort_type"];
    $cookieContainer["sort_col"] = "category";
}

//if we want to reset the sort then we replace the sort to sort asc and col to id
if (isset($_POST["sort_by_reset"])) {
    $cookieContainer["sort_type"] = "ASC";
    $cookieContainer["sort_col"] = "id";
}

// if we posted that we want different filter then we replace the cookie container
if (isset($_POST["filter_by_cat"])) {
    $cookieContainer["category_id"] = $_POST["category_id"];
}

//if we posted that we want a different page size
if (isset($_POST["set_page_size"])) {
    $cookieContainer["page_size"] = $_POST["page_size"];
}

//search cookie
if (isset($_POST["search"])) {
    $cookieContainer["search_criteria"] = $_POST["search_criteria"];
}

//push to cookie
setcookie($cookieName, json_encode($cookieContainer), time() + (86400 * 30), "/"); // 86400 = 1 day

//reset filters button here
if (isset($_POST["reset_cookie"])) {
    setcookie($cookieName, "", time() - 3600, "/");
    $cookieContainer = array(
        "category_id" => 0,
        "sort_type" => "ASC",
        "sort_col" => "id",
        "page_size" => 0
    );
    header('Location: index.php?page=products&subpage=index');
    //echo "cookies have been reset";
}
?>

<?php $productsDatabase = new parduotuveDatabase("products", $cookieContainer); ?>
<?php $settings = new parduotuveDatabase('settings'); ?>

<?php
// if we reset anything, reset page to 1,
// if we didn't reset anything and we get the page number then page number is GET
// if we didn't reset anything nor do we have get then page number is 1
if (isset($_POST["sort_by_cat"]) || isset($_POST["sort_by_reset"]) || isset($_POST["filter_by_cat"]) || isset($_POST["set_page_size"]) || isset($_POST["search"])) {
    $current_page = 1;
    unset($_GET["page-number"]);
} else if (isset($_GET["page-number"])) {
    $current_page = $_GET["page-number"];
} else {
    $current_page = 1;
}
$current_page_amount = (($cookieContainer['page_size'] == 0) ? 1 : ceil(count($productsDatabase->parduotuve) / $cookieContainer['page_size']));
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">

    <div id="filter-container">
        <!-- FILTER SETTING -->
        <div class="filter-box">
            <h1>Produkts</h1>
            <h2>Filter by category</h2>
            <form method="POST">
                <select style="width: 200px;" class="form-select" name="category_id">
                    <option value="0">None</option>
                    <?php
                    foreach ($productsDatabase->getValues('categories', 'id, title') as $categoryItem) { ?>
                        <option <?php if ($cookieContainer["category_id"] == $categoryItem["id"]) echo 'selected="selected"'; ?> value='<?php echo $categoryItem['id']; ?>'><?php echo $categoryItem["title"]; ?></option>
                    <?php } ?>
                </select>
                <button class="btn btn-primary" type="submit" name="filter_by_cat">Go</button>
            </form>
        </div>
        <!-- PAGE SETTING -->
        <div class="filter-box">
            <h2>Show per page</h2>
            <form method="POST">
                <!-- select page size -->
                <select style="width: 200px;" class="form-select" name="page_size">
                    <?php
                    for ($i = 0; $i < count($settings->parduotuve); $i++) {
                    ?>
                        <option <?php if ($cookieContainer["page_size"] == $settings->parduotuve[$i]['value']) echo 'selected="selected"'; ?> value="<?php echo $settings->parduotuve[$i]['value']; ?>"><?php echo $settings->parduotuve[$i]['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
                <button class="btn btn-primary" type="submit" value="1" name="set_page_size">Go</button>
            </form>
        </div>

        <!-- PAGE SELECT -->
        <div class="filter-box">
            <p>amount of items: <?php echo count($productsDatabase->parduotuve); ?></p>
            <p><?php echo "you are on page: " .  $current_page . " out of " . $current_page_amount; ?></p>
            <form method="GET">
                <input hidden value="products" name="page">
                <input hidden value="index" name="subpage">
                <?php
                if ($cookieContainer["page_size"] > 0) {
                    for ($i = 1; $i <= $current_page_amount; $i++) { ?>

                        <button name="page-number" value="<?php echo $i ?>" class="btn btn-<?php echo ($current_page == $i) ? "light" : "dark"; ?>"><?php echo $i; ?></button>

                <?php }
                }
                ?>
            </form>
        </div>
        
        <!-- SEARCH -->
        <div class="filter-box">
            <h2>Search</h2>
            <p>Search by %something% case sensitive</p>
            <form method="POST">
                <input class="form-control" style="width: 200px;" type="text" name="search_criteria" placeholder="search" value="<?php if(isset($cookieContainer["search_criteria"])) echo $cookieContainer["search_criteria"] ?>">
                <button class="btn btn-primary" type="submit" value="1" name="search">Go</button>
            </form>
        </div>
    </div>

    <form method="POST">
        <button name="reset_cookie" value="1" class="btn btn-danger">RESET</button>
    </form>

    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category

                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='DESC'>
                    <button class='btn btn-primary' type='submit' name='sort_by_cat'>
                        <i class="fa-solid fa-angles-down"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <input type='hidden' name='sort_type' value='ASC'>
                    <button class='btn btn-primary' type='submit' name='sort_by_cat'>
                        <i class="fa-solid fa-angles-up"></i></button>
                </form>
                <form class="sort-form" method="POST">
                    <button class='btn btn-primary' type='submit' name='sort_by_reset'>
                        <i class="fa-solid fa-x"></i></button>
                </form>
            </th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        
            if ($cookieContainer["page_size"] > 0) {
                $productsDatabase->getItems($current_page, $cookieContainer["page_size"]);
            } else {
                $productsDatabase->getItems();
            }
        



        ?>
        <?php $productsDatabase->deleteItem("products"); ?>

    </table>
</body>

</html>