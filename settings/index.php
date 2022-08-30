<!DOCTYPE html>
<html lang="en">

<body class="sub-body">
    <table class="table table-striped ">
        <tr>
            <th>ID</th>
            <th>Value</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php $settingsDatabase = new parduotuveDatabase("settings"); ?>
        <?php $settingsDatabase->getItems(); ?>
        <?php $settingsDatabase->deleteItem("settings"); ?>

    </table>
</body>

</html>