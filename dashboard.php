<?php

include_once("init.php");

if ($_SESSION['level'] == 0 || $_SESSION['level'] > 3) {
    die();
}
$handle = $config['dbo']->prepare('SELECT * FROM resources ORDER BY position, title');
$handle->execute();
$resources = $handle->fetchAll(\PDO::FETCH_ASSOC);

if ($_SESSION['level'] > 1) {
    if ($_POST['action'] == 'deleteResource') {
        $handle = $config['dbo']->prepare('DELETE FROM resources WHERE id = ?');
        $handle->bindValue(1, $_POST['resource_id']);
        $handle->execute();
        header("Location: ?");
        die();
    } else if ($_POST['action'] == 'newResource') {
        $handle = $config['dbo']->prepare('INSERT INTO resources (title, link, description, position) VALUES (?, ?, ?, ?)');
        $handle->bindValue(1, $_POST['title']);
        $handle->bindValue(2, $_POST['link']);
        $handle->bindValue(3, $_POST['description']);
        $handle->bindValue(4, $_POST['position']);
        $handle->execute();
        header("Location: ?");
        die();
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<h1 class="mt-2">Resources</h1>
<?php

if ($_SESSION['level'] > 1) {
    ?>
    <div class="d-flex mt-3 mb-3">
        <div class="btn-group flex-fill" role="group" aria-label="Basic example">
            <a href="?action=newResource" class="btn btn-warning">Create New Resource</a>
            <a href="?action=deleteResource" class="btn btn-warning">Delete Resource</a>
        </div>
    </div>
    <?php

    if ($_GET['action'] == 'newResource') {
        ?>
        <div class="pl-4 pr-4 mb-4">
            <form method="post">
                <div class="form-group">
                    <label for="newResourceTitle">Title</label>
                    <input name="title" type="text" class="form-control" id="newUserName" aria-describedby="emailHelp" placeholder="Roll Book" required>
                </div>
                <div class="form-group">
                    <label for="newResourceLink">Link</label>
                    <input name="link" type="url" class="form-control" id="newResourceLink" aria-describedby="emailHelp" placeholder="http://drive.google.com" required>
                </div>
                <div class="form-group">
                    <label for="newResourcePosition">Position</label>
                    <input name="position" type="text" class="form-control" id="newResourcePosition" aria-describedby="emailHelp" placeholder="Secretary" required>
                </div>
                <div class="form-group">
                    <label for="newResourceDescription">Description</label>
                    <input name="description" type="text" class="form-control" id="newResourceDescription" aria-describedby="emailHelp" placeholder="2019-2020 Roll Book" required>
                </div>
                <input type="hidden" name="action" value="newResource">
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
        <?php
    } else if ($_GET['action'] == 'deleteResource') {
        ?>
        <div class="pl-4 pr-4 mb-4">
            <form method="post">
                <div class="form-group">
                    <label for="deleteResourceResource">Resource</label>
                    <select name="resource_id" required id="deleteResourceResource">
                        <?php
                        foreach ($resources as $resource) {
                            echo "<option value='" . $resource['id'] . "'>" . $resource['position'] . ': ' . $resource['title'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="deleteResource">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        <?php
    }
}

echo "</br>";

if (count($resources) == 0) {
    echo "<h3>No Resources Found</h3>";
} else {
    ?>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Position</th>
                <th>Title</th>
                <th>Link</th>
                <th>Description</th>
            </tr>
        </thead>
    <?php
    foreach ($resources as $resource) {
        echo "<tr>";
            echo "<td>" . $resource['position'] . "</td>";
            echo "<td>" . $resource['title'] . "</td>";
            echo "<td><a href='" . $resource['link'] . "' target='_blank'>" . $resource['link'] . "</a></td>";
            echo "<td>" . $resource['description'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script></body>
</html>
