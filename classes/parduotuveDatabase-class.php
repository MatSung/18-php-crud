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
            $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"]);
        } else {
            $table2 = 'products';
            $this->parduotuve = $this->selectAction($table1);

        }

        //var_dump($this->parduotuve[0]);
        //if we are in index, print the whole list plus editing functionality
        if (isset($_GET["page"]) || isset($_GET["subpage"])) {
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
                    echo "<td>" . $item["image_url"] . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $item["id"] . "'>";
                    //code delete action
                    echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";
                    echo "</form>";
                    //code edit action to go to edit screen (update in sql)
                    //echo "<a href='index.php?page=update&id='. $movie["id"]. "' class='btn btn-success'>EDIT</a>";
                    //echo "<a href='index.php?page=update&id=" . $movie["id"] . "' class='btn btn-success'>EDIT</a>";
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
                    echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";
                    echo "</form>";
                    //code edit action to go to edit screen (update in sql)
                    //echo "<a href='index.php?page=update&id='. $movie["id"]. "' class='btn btn-success'>EDIT</a>";
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

    public function createItem(){
        if(isset($_POST['submit'])){
            
        }
    }


    public function getValues($table, $col){
        $this->categories = $this->selectByColAction($table, $col);
        return $this->categories;
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
