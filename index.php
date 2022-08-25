<?php include("classes/parduotuveDatabase-class.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <title>Duombaze - Pagrindinis</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    // IF
    // we are no longer at the beginning
    // big box for selecting
    // ELSE
    // navigation to products or categories on top left
    if (isset($_GET["page"])) {
    ?>
        <header id="header">
            <div class="nav-tables">
                <div>
                    <a class="small" 
                    <?php if ($_GET["page"] == 'products') echo "style='font-weight: bold;'";  ?> href="index.php?page=products&subpage=index">Produktai</a>
                </div>
                <div>
                    <a class="small" <?php if ($_GET["page"] == 'categories') echo "style='font-weight: bold;'";  ?> href="index.php?page=categories&subpage=index">Kategorijos</a>
                </div>
            </div>
            <nav id="nav-bar">
                <ul>
                    <!-- class="nav-link" -->
                    <?php
                        if($_GET["page"] == "products")
                        {
                            echo '<li ><a class="nav-link" href="index.php?page=products&subpage=index">Index</a></li>';
                            echo '<li ><a class="nav-link" href="index.php?page=products&subpage=create">Create</a></li>';
                            //echo '<li ><a class="nav-link" href="index.php?page=products&subpage=edit">Edit</a></li>';
                        } else if($_GET["page"] == "categories"){
                            echo '<li ><a class="nav-link" href="index.php?page=categories&subpage=index">Index</a></li>';
                            echo '<li ><a class="nav-link" href="index.php?page=categories&subpage=create">Create</a></li>';
                            //echo '<li ><a class="nav-link" href="index.php?page=categories&subpage=edit">Edit</a></li>';
                        }
                    ?>
                </ul>
            </nav>
        </header>
    <?php
    } else {
        //we are at the beginning so we can show the front page
        echo '<div class="my-container">
                <div>
                    <a class="big" href="index.php?page=products&subpage=index">Produktai</a>
                </div>
                <div>
                    <a class="big" href="index.php?page=categories&subpage=index">Kategorijos</a>
                </div>
            </div>';
    }
    ?>
    

    <?php
        //pagal page ir subpage busime nukreipiami i ten kur reikia
        // jeigu page set bet subpage not tada nukreipiu tiesiai i index
        if(isset($_GET["page"])){
            $page = $_GET["page"];
            
            if(isset($_GET["subpage"])){
                $subpage = ($_GET["subpage"]);
                include($page."\\".$subpage.".php");
            }else{
                include($page."\\index.php");
            }
        }
    ?>




    <?php
    //ikelti 150 random
    //$tempConn = new parduotuveDatabase();
    //echo $tempConn->createRandomItems(150, 'products');
    ?>
</body>

</html>