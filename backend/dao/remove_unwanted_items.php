<?php
require_once __DIR__ . '/menuItemDAO.php';

$menuItemDao = new MenuItemDao();

$toRemove = [
    'Spaghetti Carbonara',
    'Caesar Salad',
    'Espresso',
    'House Lemonade'
];

$deleted = $menuItemDao->deleteByExactNames($toRemove);
echo "Removed $deleted unwanted menu items.\n";
?>


