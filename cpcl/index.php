<?php
session_start();
require_once "parser.php";
require_once "EntityDTO.php";
require_once "IOUtils.php";

$_SESSION['new_file'] = null;
$_SESSION['uploaded_file'] = null;
$uploadDir = 'uploads/';

function parseAndShow($fileContent): void
{
    // Parse the file into sections (entities)
    $parser = new Parser();
    $data = $parser->parseYamlFile($fileContent);
    $parser->extractEntities($data);
    $entities = $parser->getEntities();

    // Save the entities in the session
    $_SESSION['parsed_entities'] = serialize($entities);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileToParse = null;
    // Check if the main upload form is submitted
    if (isset($_FILES["file"])) {
        // Handle the logic for uploading a file from the local PC

        $file = $_FILES["file"];

        // Check if the file is a .yaml file
        $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
        if (strtolower($fileType) !== "yaml") {
            echo "<p>Only config files (.yaml) are allowed.</p>";
        } else {

            // Move the uploaded file to a persistent location

            $fileToParse = $uploadDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $fileToParse);
        }

        // Save the file path in the session
        $_SESSION['new_file'] = basename($file['name']);
        $_SESSION['uploaded_file'] = basename($file['name']);

    }
    // Check if the upload from uploads/ form is submitted
    if (isset($_POST["uploadedFile"])) {
        $fileToParse = $_POST["uploadedFile"];
        $_SESSION['uploaded_file'] = explode("/", $fileToParse)[1];
    }

    if ($fileToParse != null) {
        $io = new IOUtils();
        // Read the file content
        $fileContent = $io->readFile($fileToParse);
        // Parse the file into sections (entities)
        parseAndShow($fileContent);
    }

    ?>

    <?php

    // Check if the delete button is clicked
    if (isset($_POST['delete_entities'])) {
        // Clear the parsed entities from the session
        unset($_SESSION['parsed_entities']);
    }


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proxy configuration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">-->

</head>
<body>
<div class="container ">
    <p class="h3 mt-2 mb-5">Proxy configuration</p>

    <div class="container row d-flex justify-content-between align-items-center">
        <div class="input-group ">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                  enctype="multipart/form-data">

                <div class="input-group input-group-sm mb-3">

                    <div class="input-group-prepend">
                        <button type="submit" class="input-group-append btn btn-outline-success">⬆upload</button>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" accept=".yaml" required>
                        <label class="custom-file-label"
                               for="file"><?= $_SESSION['new_file'] ?? "Choose file" ?></label>
                    </div>
                </div>

            </form>
        </div>

        <div class="input-group">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-group input-group-sm mb-3">

                    <select class="custom-select " name="uploadedFile" required>
                        <?php

                        // List files in the uploads/ directory
                        $uploadDir = 'uploads/';
                        $files = scandir($uploadDir);

                        foreach ($files as $file) {
                            if ($file != "." && $file != "..") {

                                if ($_SESSION['uploaded_file'] === $file) {
                                    echo "<option selected value=\"$uploadDir$file\">$file</option>";
                                } else {
                                    echo "<option value=\"$uploadDir$file\">$file</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="input-group-append btn btn-outline-primary">Get from uploads/
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <button type="submit" form="deleteForm" name="delete_entities"
                class="btn btn-sm btn-outline-danger">Clear
        </button>

    </div>

    <?php

    // Check if the delete button is clicked
    if (isset($_POST['delete_entities'])) {
        // Clear the parsed entities from the session
        unset($_SESSION['parsed_entities']);
    }
    ?>

    <!-- Hidden form to submit when the button is clicked -->
    <form id="deleteForm" method="post"></form>


    <?php


    function generateEntriesArray($entities)
    {
        $entries = [];

        foreach ($entities as $entity) {
            $entry = [
                'name' => $entity->getName(), // Replace with the actual method to get the name
                'status' => getStatus($entity), // Replace with the actual method to get the status
            ];

            $entries[] = $entry;
        }

        return $entries;
    }

    function getStatus($entity)
    {
        // Replace this logic with the actual logic to determine the status
        // For example, you might have methods like $entity->isProcessed(), $entity->isPending(), etc.
        // You can define your logic based on your application's requirements.
        // For simplicity, I'm using a random function here.

        $statuses = ['processed', 'pending', 'failed'];
        return $statuses[array_rand($statuses)];
    }

    function displayEntitiesByType($entities, $section)
    {
        $foundEntities = false;
        if ($section == Section::ALL && !empty($entities)) {
            $foundEntities = true;
            // Example usage:
            $entries = generateEntriesArray($entities);

            displayCPCLEntries($entries);
        }
        foreach ($entities as $entity) {
            if ($entity->getSection() === $section) {
                $foundEntities = true;

                $protocol = $entity->getProtocol();
                switch ($section) {
                    case Section::IN or Section::OUT:
                        switch ($protocol) {
                            case EntityProtocol::SAML:
                                displayEntityForSAML($entity);
                                break;
                            case EntityProtocol::OIDC:
                                displayEntityForOIDC($entity);
                                break;
                        }
                    case Section::RULES:
                        displayEntityForRules($entity);
                        break;
                    // Add more cases if needed
                }
            }
        }
        if (!$foundEntities) {
            displayDefaultEmpty("No entities found for this section.");
        }
    }


    function displayDefaultEmpty($message)
    {
        ?>
        <div class="d-flex align-items-center justify-content-center vh-75">
            <div class="text-center">
                <p><?= $message ?></p>
            </div>
        </div>
        <?php
    }

    function defaultSAMLCard($entity, $scriptUrl): void
    {
        $title = $entity->getName();
        $entityId = str_replace(' ', '', $entity->getName()); // Assuming there's a getTitle() method on your entity
        $editModalId = "editModal_$entityId";
        $url = $entity->getResourceLocation();
        $body = "<p class='card-text'>{$entity->getDescription()}</p>
            <p class='card-text'>Type: {$entity->getType()}</p>
            <p class='card-text'>Protocol: {$entity->getProtocol()}</p>
            <p class='card-text'>Metadata url: <a href='{$url}'>{$url}</a></p>
            <p class='card-text'>Entity ID: {$entity->getId()}</p>";

        ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0"><?= $entity->getName(); ?></h5>
                <div class="btn-group" role="group" aria-label="Card Actions">
                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#<?= $editModalId ?>">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" onclick="runScript('<?= $scriptUrl ?>', this)"
                            id="processButton_<?= $entityId ?>">
                        <i class="bi bi-arrow-right"></i> Process
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= $body ?>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="<?= $editModalId ?>" tabindex="-1" role="dialog"
             aria-labelledby="<?= $editModalId ?>Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $editModalId ?>Label">Edit <?= $title ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your edit form or content here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="confirmEdit('<?= $entityId ?>')">Save
                            changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function runScript(scriptUrl, button) {
                // Add loader logic here (e.g., show loading spinner)

                // Simulate a script execution (replace this with your actual script logic)
                setTimeout(function () {
                    // Hide loader and show success state
                    alert('Script executed successfully!');

                    // Disable the Process button
                    button.disabled = true;

                    // Add additional logic or update UI based on the script response
                }, 2000); // Simulating a 2-second script execution
            }

            function confirmEdit(entityId) {
                // Add logic to handle confirmation (e.g., update UI or enable Process button)
                alert('Edit confirmed for entity: ' + entityId);

                // Enable the Process button
                document.getElementById('processButton_' + entityId).disabled = false;
            }
        </script>
        <?php
    }

    function defaultOIDCCard($entity, $scriptUrl): void
    {
        $entityId = str_replace(' ', '', $entity->getName()); // Assuming there's a getTitle() method on your entity
        $editModalId = "editModal_$entityId";
        $title = $entity->getName();
        $body = "<p class='card-title'>{$entity->getDescription()}</p>
            <p class='card-text'>Type: {$entity->getType()}</p>
            <p class='card-text'>Protocol: {$entity->getProtocol()}</p>
            <p class='card-text'>Resource Location: {$entity->getResourceLocation()}</p>
            <p class='card-text'>ID: {$entity->getId()}</p>
            <p class='card-text'>Dynamic Registration: {$entity->getDynamicRegistration()}</p>
            <p class='card-text'>Client Secret: {$entity->getClientSecret()}</p>";
        ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0"><?= $entity->getName(); ?></h5>
                <div class="btn-group" role="group" aria-label="Card Actions">
                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#<?= $editModalId ?>">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" onclick="runScript('<?= $scriptUrl ?>', this)"
                            id="processButton_<?= $entityId ?>">
                        <i class="bi bi-arrow-right"></i> Process
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= $body ?>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="<?= $editModalId ?>" tabindex="-1" role="dialog"
             aria-labelledby="<?= $editModalId ?>Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $editModalId ?>Label">Edit <?= $title ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your edit form or content here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="confirmEdit('<?= $entityId ?>')">Save
                            changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function runScript(scriptUrl, button) {
                // Add loader logic here (e.g., show loading spinner)

                // Simulate a script execution (replace this with your actual script logic)
                setTimeout(function () {
                    // Hide loader and show success state
                    alert('Script executed successfully!');

                    // Disable the Process button
                    button.disabled = true;

                    // Add additional logic or update UI based on the script response
                }, 2000); // Simulating a 2-second script execution
            }

            function confirmEdit(entityId) {
                // Add logic to handle confirmation (e.g., update UI or enable Process button)
                alert('Edit confirmed for entity: ' + entityId);

                // Enable the Process button
                document.getElementById('processButton_' + entityId).disabled = false;
            }
        </script>
        <?php

    }

    function displayEntityForSAML($entity): void
    {
        defaultSAMLCard($entity, "someScriptForSaml");
    }

    function displayEntityForOIDC(mixed $entity): void
    {
        defaultOIDCCard($entity, "someScriptForOidc");
    }

    function displayEntityForRules(mixed $entity)
    {
        ?>

        <?php

    }

    function displayCPCLEntries($entries)
    {

        ?>
        <div class="container mt-3">
            <h4>Configuration Entries</h4>


            <ul class="list-group">
                <?php foreach ($entries as $entry): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $entry['name'] ?> <!-- Replace with actual entry property -->

                        <!-- Status badge -->
                        <?php if ($entry['status'] === 'processed'): ?>
                            <span class="badge badge-success">Processed</span>
                        <?php elseif ($entry['status'] === 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Failed</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }


    // Display the parsed sections
    if (isset($_SESSION['parsed_entities'])) {
        $entities = unserialize($_SESSION['parsed_entities']);
        $filename = $_SESSION['uploaded_file'];
        ?>
        <h5 class=" mt-5"><?= $filename; ?></h5>
        <ul class="nav nav-tabs" id="entityTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="in-tab" data-toggle="tab" href="#in" role="tab" aria-controls="in"
                   aria-selected="true">IN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="out-tab" data-toggle="tab" href="#out" role="tab" aria-controls="out"
                   aria-selected="false">OUT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rules-tab" data-toggle="tab" href="#rules" role="tab" aria-controls="rules"
                   aria-selected="false">Rules</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all"
                   aria-selected="false">All</a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <!-- IN Tab -->
            <div class="tab-pane fade show active" id="in" role="tabpanel" aria-labelledby="in-tab">
                <?php displayEntitiesByType($entities, Section::IN); ?>
            </div>

            <!-- OUT Tab -->
            <div class="tab-pane fade" id="out" role="tabpanel" aria-labelledby="out-tab">
                <?php displayEntitiesByType($entities, Section::OUT); ?>
            </div>

            <!-- Rules Tab -->
            <div class="tab-pane fade" id="rules" role="tabpanel" aria-labelledby="rules-tab">
                <?php displayEntitiesByType($entities, Section::RULES); ?>
            </div>

            <!-- All Tab -->
            <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                <?php displayEntitiesByType($entities, Section::ALL); ?>
            </div>
        </div>
        <?php
    }
    ?>

</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Enable Bootstrap tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // file input from local fs
    $(document).ready(function () {
        // Update the label when a file is selected
        $('#file').on('change', function () {
            var fileName = $(this).val().split('\\').pop(); // Get the file name without the path
            $('.custom-file-label').html(fileName);
        });
    });
</script>
</body>
</html>
