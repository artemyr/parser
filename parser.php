<?php
include_once('core/functions.php');
$app = new Aplication;
$app->get_head();
?>

<body>
<?$app->get_body()?>
<?$app->get_scripts()?>
</body>