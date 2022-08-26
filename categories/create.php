<?php
$parduotuveDatabase = new parduotuveDatabase();
if($parduotuveDatabase->createItem('categories')){
    header('Location: index.php?page=categories&subpage=index');
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <div class="container">
        <form method="POST">
            <input class="form-control" name="title" placeholder="Title">
            <input class="form-control" name="description" placeholder="Description">
            <button class="btn btn-primary" type="submit" name="submit">Create</button>
        </form>
    </div>
</body>

</html>