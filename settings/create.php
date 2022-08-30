<?php
$parduotuveDatabase = new parduotuveDatabase();
if($parduotuveDatabase->createItem('settings')){
    header('Location: index.php?page=settings&subpage=index');
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <div class="container">
        <form method="POST">
            <input class="form-control" type="number" step="1" min="0" name="value" placeholder="value">
            <input class="form-control" name="name" placeholder="name">
            <button class="btn btn-primary" type="submit" name="submit">Create</button>
        </form>
    </div>
</body>

</html>