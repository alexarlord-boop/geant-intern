<?php
?>
            <div class="card mb-4">
                <div class="card-header">
                    <?= $entity->getName(); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $entity->getDescription(); ?></h5>
                    <p class="card-text">Type: <?= $entity->getType(); ?></p>
                    <p class="card-text">Protocol: <?= $entity->getProtocol(); ?></p>
                    <p class="card-text">Resource Location: <?= $entity->getResourceLocation(); ?></p>
                    <p class="card-text">ID: <?= $entity->getId(); ?></p>
                    <p class="card-text">Dynamic Registration: <?= $entity->getDynamicRegistration(); ?></p>
                    <p class="card-text">Client Secret: <?= $entity->getClientSecret(); ?></p>
                </div>
            </div>

<?php