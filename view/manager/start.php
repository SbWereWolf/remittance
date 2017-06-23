<?php
/* @var $menu ManagerMenu */

use Remittance\Presentation\Web\Page\ManagerMenu;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Меню менеджера</title>
</head>

<body>

<h1>Меню менеджера</h1>
<?php
$isSet = isset($menu);
$isValid = false;
if ($isSet) {
    $isValid = $menu instanceof ManagerMenu;
}

if ($isValid)
    include('manager_menu.php');
?>

</body>

</html>
