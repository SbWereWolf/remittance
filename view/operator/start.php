<?php
/* @var $menu OperatorMenu */

use Remittance\Presentation\Web\Page\OperatorMenu;

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
    $isValid = $menu instanceof OperatorMenu;
}

if ($isValid)
    include('operator_menu.php');
?>

</body>

</html>
