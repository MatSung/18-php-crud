<?php
include("classes/databaseConnection-class.php");

class parduotuveDatabase extends DatabaseConnection
{
    public $parduotuve;
    public $categories;

    public function __construct($table1 = 'products')
    {
        parent::__construct();

        //jeigu norim produktu tada kategorijos yra antrinis table
        //jeigu norim kategoriju tada products yra antrinis table
        if ($table1 == 'products') {
            $table2 = 'categories';
            if (isset($_POST['sort_by_cat'])){
                if ($_POST['sort_type'] == "asc"){
                    $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"], "category");
                } else {
                    $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"], "category", "DESC");
                }
            } else if(isset($_POST['filter_by_cat']) && $_POST['category_id'] != 0) {
                $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"], "category", "DESC", "categories.id = ".$_POST['category_id']);
            } else {
                $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"]);
            }
        } else {
            $table2 = 'products';
            if (isset($_POST['sort_by_id'])){
                if ($_POST['sort_type'] == "asc"){
                    $this->parduotuve = $this->selectAction($table1,"id", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1,"id", 'DESC');
                }
            } else if(isset($_POST['sort_by_title'])){
                if ($_POST['sort_type'] == "asc"){
                    $this->parduotuve = $this->selectAction($table1,"title", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1,"title", 'DESC');
                }
            } else if (isset($_POST['sort_by_desc'])){
                if ($_POST['sort_type'] == "asc"){
                    $this->parduotuve = $this->selectAction($table1,"description", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1,"description", 'DESC');
                }
            }
             else {
                $this->parduotuve = $this->selectAction($table1);
            }

        }

        //var_dump($this->parduotuve[0]);
        //if we are in index, print the whole list plus editing functionality
    }

    public function getItems(){
        if ((isset($_GET["page"]) || isset($_GET["subpage"])) && !isset($_POST["delete"]) && !isset($_POST["update"])) {
            if ($_GET["page"] == "products" && $_GET["subpage"] == "index") {
                foreach ($this->parduotuve as $item) {
                    echo "<tr>";
                    echo "<td>" . $item["id"] . "</td>";
                    echo "<td>" . $item["title"] . "</td>";
                    echo "<td>" . $item["description"] . "</td>";
                    echo "<td>" . $item["price"] . "</td>";
                    if (empty($item["category"])) {
                        echo "<td>Nėra kategorijos</td>";
                    } else {
                        echo "<td>" . $item["category"] . "</td>";
                    }
                    // <image href="$item["image_url"]" >
                    if(empty($item["image_url"])){
                        echo "<td>NO IMAGE</td>";
                    } else {
                        $image_url = str_replace("\\", "/", $item['image_url']);
                        echo "<td><img height='100' width='100' src='products/". $image_url ."'</td>";
                    }
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $item["id"] . "'>";
                    //edit and delete
                    echo "<a href='index.php?page=products&subpage=update&id=" . $item["id"] . "' class='btn btn-primary'>EDIT</a>";
                    echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";
                    echo "</form>";
                    //code edit action to go to edit screen (update in sql)
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else if($_GET["page"] == "categories" && $_GET["subpage"] == "index"){
                foreach ($this->parduotuve as $item) {
                    echo "<tr>";
                    echo "<td>" . $item["id"] . "</td>";
                    echo "<td>" . $item["title"] . "</td>";
                    echo "<td>" . $item["description"] . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $item["id"] . "'>";
                    //code delete action
                    echo "<a href='index.php?page=categories&subpage=update&id=" . $item["id"] . "' class='btn btn-primary'>EDIT</a>";
                    echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";                    
                    echo "</form>";
                    //code edit action to go to edit screen (update in sql)
                    //echo "<a href='index.php?page=update&id=" . $movie["id"] . "' class='btn btn-success'>EDIT</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }


        //if we are in create then print a nothing
        //if we are in update idk yet

        //select with join bus cia kad parodytu database
    }

    public function deleteItem($table){
        if(isset($_POST["delete"])){
            $this->deleteAction($table, $_POST["id"]);
            header("Location: index.php?page=".$table."&subpage=index");
        }
    }

    public function createItem($table){
        if(isset($_POST['submit'])){
            //set all text entries
            
            //skip id, title, description, price float, category_id provided, image_url
            $cols = ["title", "description"];
            $items = [
                '"' . $_POST['title'] . '"',
                '"' . $_POST['description']. '"'
            ];
            if($table == "products")
            {
                array_push($cols, "price", "category_id", "image_url");
                array_push($items, '"' . $_POST['price'] . '"', $_POST['category_id']);
                //image url
                array_push($items, '"files/SkateDog.png"');
            }
            // $table = products\
            // $cols = [`title`, `description`, `price`, `category_id`, `image_url`]
            // $values = [title, description, 1.0, 1, "uploads/image.png"]
            if($this->insertAction($table, $cols, $items)){
                return 1;
            }
        }
        return 0;
    }


    public function getValues($table, $col){
        $this->categories = $this->selectByColAction($table, $col);
        return $this->categories;
    }

    public function selectOneItem($table) {
        if(($_GET["subpage"] == "update" && isset($_GET["id"]))) {
            $item = $this->selectOneAction($table, $_GET["id"]);
            return $item[0];
            
        }
    }

    public function updateItem($table) {
        if(isset($_POST["update"])) {
            $data = array(
                'title' => $_POST['title'],
                'description' => $_POST['description']
            );
            if($table == 'products'){
                $data['price'] = $_POST['price'];
                $data['category_id'] = $_POST['category_id'];
            }

            $this->updateAction($table, $_POST["id"] , $data);
            return 1;
        }
        return 0;
    }

    public function createRandomItems($amount, $table)
    {
        //jeigu paspaustas mygtukas gal?
        for ($i = 0; $i < $amount; $i++) {
            // skip id, title, description, price float, category id 1to3, no image

            //insert action
            // table = products
            // $cols = [`title`, `description`, `price`, `category_id`]
            // $values = [title, description, 1.0, 1]
            $items = [
                '"' . 'Food_item_' . $i . '"',
                '"' . 'Description_' . $i . '"',
                '"' . rand(100, 10000) / 100 . '"',
                '"' . rand(1, 3) . '"'
            ];
            $cols = ["title", "description", "price", "category_id"];
            $this->insertAction($table, $cols, $items);
        }
        return ' complete';
    }
}
