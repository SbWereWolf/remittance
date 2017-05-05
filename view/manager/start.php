<?php
/* @var $menu array */
use Remittance\Core\Common;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Меню менеджера</title>
</head>

<body>
<?php
$isSet = isset($menu);
$isValid = false;
if($isSet){
    $isValid = Common::isValidArray($menu);
}

if ($isValid)
    include('manager_menu.php');
?>

</body>

</html>
