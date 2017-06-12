<?php
/* @var $menu array */
use Remittance\Core\Common;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Меню Оператора</title>
</head>

<body>

<h1>Меню Оператора</h1>
<?php
$isSet = isset($menu);
$isValid = false;
if($isSet){
    $isValid = Common::isValidArray($menu);
}

if ($isValid)
    include('operator_menu.php');
?>

</body>

</html>
