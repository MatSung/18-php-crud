<?php
include("classes/databaseConnection-class.php");

class parduotuveDatabase extends DatabaseConnection
{
    public $parduotuve;
    public $categories;
    public $pageSetting;

    public function __construct($table1 = 'products', $settings = "")
    {
        parent::__construct();

        //jeigu norim produktu tada kategorijos yra antrinis table
        //jeigu norim kategoriju tada products yra antrinis table
        if ($table1 == "settings") {
            $this->parduotuve = $this->selectAction($table1);
        } else if ($table1 == 'products') {
            $table2 = 'categories';
            //if filter id is 0, then default, else not default
            $filter = 1;
            //echo "I have not filetered yet but the criteria is  ".$settings["search_criteria"];
            
            if(isset($settings["search_criteria"]) && $settings["category_id"] > 0){
                $filter = "products.title LIKE " . "'%" . $settings["search_criteria"] . "%'". " AND " . "categories.id = " . $settings["category_id"];
            } else if (isset($settings["search_criteria"])){
                $filter = "products.title LIKE "."'%".$settings["search_criteria"]."%'";
                //echo "i have filetered because i have search criteria ".$settings["search_criteria"];
            } else if ($settings["category_id"] > 0){
                $filter = "categories.id = " . $settings["category_id"];
            }
            $this->parduotuve = $this->selectJoinAction($table1, $table2, 'category_id', 'id', "LEFT JOIN", ["products.id", "products.title", "products.description", "products.price", "categories.title as category", "products.image_url"], $settings["sort_col"], $settings["sort_type"], $filter);
        } else {
            $table2 = 'products';
            if (isset($_POST['sort_by_id'])) {
                if ($_POST['sort_type'] == "asc") {
                    $this->parduotuve = $this->selectAction($table1, "id", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1, "id", 'DESC');
                }
            } else if (isset($_POST['sort_by_title'])) {
                if ($_POST['sort_type'] == "asc") {
                    $this->parduotuve = $this->selectAction($table1, "title", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1, "title", 'DESC');
                }
            } else if (isset($_POST['sort_by_desc'])) {
                if ($_POST['sort_type'] == "asc") {
                    $this->parduotuve = $this->selectAction($table1, "description", 'ASC');
                } else {
                    $this->parduotuve = $this->selectAction($table1, "description", 'DESC');
                }
            } else {
                $this->parduotuve = $this->selectAction($table1);
            }
        }

        //var_dump($this->parduotuve[0]);
        //if we are in index, print the whole list plus editing functionality
    }

    public function getItems($pageNumber = 1, $pageSize = 0)
    {
        if ((isset($_GET["page"]) || isset($_GET["subpage"])) && !isset($_POST["delete"]) && !isset($_POST["update"])) {
            if ($_GET["page"] == "products" && $_GET["subpage"] == "index") {
                // if i get page number and size for loop to only print take 
                // $current_page_size(int) of items after $current_page(int) x $current_page_size
                if ($pageSize != 0) {
                    $current_page_item = ($pageNumber == 1) ? 0 : $pageSize * ($pageNumber-1);
                    $pageLimit =  ($current_page_item + $pageSize > count($this->parduotuve)) ? count($this->parduotuve) : ($current_page_item+$pageSize);
                    //or item limit whichever is higher
                    for($i = $current_page_item+1; $i <= $pageLimit; $i++){
                        echo "<tr>";
                        echo "<td>" . $this->parduotuve[$i-1]["id"] . "</td>";
                        echo "<td>" . $this->parduotuve[$i-1]["title"] . "</td>";
                        echo "<td>" . $this->parduotuve[$i-1]["description"] . "</td>";
                        echo "<td>" . $this->parduotuve[$i-1]["price"] . "</td>";
                        if (empty($this->parduotuve[$i-1]["category"])) {
                            echo "<td>Nėra kategorijos</td>";
                        } else {
                            echo "<td>" . $this->parduotuve[$i-1]["category"] . "</td>";
                        }
                        if (empty($this->parduotuve[$i-1]["image_url"])) {
                            echo "<td>NO IMAGE</td>";
                        } else {
                            $image_url = str_replace("\\", "/", $this->parduotuve[$i-1]['image_url']);
                            echo "<td><img height='100' width='100' src='" . $image_url . "'</td>";
                        }
                        echo "<td>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='id' value='" . $this->parduotuve[$i-1]["id"] . "'>";
                        //edit and delete
                        echo "<a href='index.php?page=products&subpage=update&id=" . $this->parduotuve[$i-1]["id"] . "' class='btn btn-primary'>EDIT</a>";
                        echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";
                        echo "</form>";
                        //code edit action to go to edit screen (update in sql)

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
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
                        if (empty($item["image_url"])) {
                            echo "<td>NO IMAGE</td>";
                        } else {
                            $image_url = str_replace("\\", "/", $item['image_url']);
                            echo "<td><img height='100' width='100' src='" . $image_url . "'</td>";
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
                }
            } else if ($_GET["page"] == "categories" && $_GET["subpage"] == "index") {
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
                    echo "</td>";
                    echo "</tr>";
                }
            } else if ($_GET["page"] == "settings" && $_GET["subpage"] == "index") {
                foreach ($this->parduotuve as $item) {
                    echo "<tr>";
                    echo "<td>" . $item["id"] . "</td>";
                    echo "<td>" . $item["value"] . "</td>";
                    echo "<td>" . $item["name"] . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $item["id"] . "'>";
                    //code delete action
                    echo "<a href='index.php?page=settings&subpage=update&id=" . $item["id"] . "' class='btn btn-primary'>EDIT</a>";
                    echo "<button class='btn btn-danger' type='submit' name='delete'>DELETE</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }


        //if we are in create then print a nothing
        //if we are in update idk yet

        //select with join bus cia kad parodytu database
    }

    public function deleteItem($table)
    {
        if (isset($_POST["delete"])) {
            $this->deleteAction($table, $_POST["id"]);
            header("Location: index.php?page=" . $table . "&subpage=index");
        }
    }

    public function createItem($table, $img_url = "")
    {
        if (isset($_POST['submit'])) {
            //set all text entries

            //skip id, title, description, price float, category_id provided, image_url
            $cols = ["title", "description"];
            $items = [
                '"' . $_POST['title'] . '"',
                '"' . $_POST['description'] . '"'
            ];
            if ($table == "products") {
                array_push($cols, "price", "category_id", "image_url");
                array_push($items, '"' . $_POST['price'] . '"', $_POST['category_id']);
                //image url
                echo $img_url;
                array_push($items, '"' . $img_url . '"');
            }
            if ($table == "settings") {
                $cols = ["value", "name"];
                $items = [
                    '"' . $_POST['value'] . '"',
                    '"' . $_POST['name'] . '"'
                ];
            }
            // $table = products\
            // $cols = [`title`, `description`, `price`, `category_id`, `image_url`]
            // $values = [title, description, 1.0, 1, "uploads/image.png"]
            if ($this->insertAction($table, $cols, $items)) {
                return 1;
            }
        }
        return 0;
    }


    public function getValues($table, $col)
    {
        $this->categories = $this->selectByColAction($table, $col);
        return $this->categories;
    }

    public function selectOneItem($table)
    {
        if (($_GET["subpage"] == "update" && isset($_GET["id"]))) {
            $item = $this->selectOneAction($table, $_GET["id"]);
            return $item[0];
        }
    }

    public function updateItem($table)
    {
        if (isset($_POST["update"])) {
            if($table != 'settings'){
                $data = array(
                    'title' => $_POST['title'],
                    'description' => $_POST['description']
                );
                if ($table == 'products') {
                    $data['price'] = $_POST['price'];
                    $data['category_id'] = $_POST['category_id'];
                }
    
                $this->updateAction($table, $_POST["id"], $data);
                return 1;
            } else {
                $data = array(
                    'value' => $_POST['value'],
                    'name' => $_POST['name']
                );
                $this->updateAction($table, $_POST["id"], $data);
                return 1;
            }
            
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
