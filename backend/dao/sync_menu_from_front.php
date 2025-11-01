<?php
require_once __DIR__ . '/menuItemDAO.php';
require_once __DIR__ . '/categoryDAO.php';

$categoryDao = new CategoryDao();
$menuItemDao = new MenuItemDao();


$frontMenu = [
    'Starters' => [
        [ 'name' => 'Bruschetta al Pomodoro', 'description' => 'Toasted bread with fresh tomatoes, basil & olive oil', 'price' => 8 ],
        [ 'name' => 'Caprese Salad', 'description' => 'Buffalo mozzarella, tomatoes & balsamic glaze', 'price' => 10 ],
        [ 'name' => 'Garlic Shrimp', 'description' => 'Pan-seared shrimp with garlic butter and herbs', 'price' => 12 ],
        [ 'name' => 'Mini Crab Cakes', 'description' => 'Served with a creamy remoulade sauce', 'price' => 14 ],
        [ 'name' => 'Stuffed Mushrooms', 'description' => 'Filled with parmesan, herbs, and breadcrumbs', 'price' => 9 ],
        [ 'name' => 'Antipasto Skewers', 'description' => 'Mini skewers with mozzarella, olives, and cherry tomatoe', 'price' => 11 ]
    ],
    'Main Courses' => [
        [ 'name' => 'Filet Mignon', 'description' => 'Grilled tenderloin with red wine reduction & vegetables', 'price' => 28 ],
        [ 'name' => 'Sea Bass Fillet', 'description' => 'Served with lemon butter sauce & asparagus', 'price' => 24 ],
        [ 'name' => 'Truffle Risotto', 'description' => 'Creamy Arborio rice with truffle oil & parmesan', 'price' => 22 ],
        [ 'name' => 'Herb Roasted Chicken', 'description' => 'Served with mashed potatoes and seasonal vegetables', 'price' => 20 ],
        [ 'name' => 'Spaghetti Bolognese', 'description' => 'Classic Italian pasta with rich meat sauce and Parmesan cheese', 'price' => 18 ],
        [ 'name' => 'Grilled Salmon', 'description' => 'Served with dill sauce and roasted baby potatoes', 'price' => 23 ]
    ],
    'Desserts' => [
        [ 'name' => 'Tiramisu', 'description' => 'Classic Italian dessert with espresso & mascarpone', 'price' => 9 ],
        [ 'name' => 'Chocolate Lava Cake', 'description' => 'Warm chocolate cake with molten center & vanilla ice cream', 'price' => 11 ],
        [ 'name' => 'Panna Cotta', 'description' => 'Silky cream dessert with berry coulis', 'price' => 8 ],
        [ 'name' => 'Vanilla Cheesecake', 'description' => 'Rich cheesecake with strawberry sauce', 'price' => 10 ],
        [ 'name' => 'Italian Gelato', 'description' => 'Homemade ice cream with seasonal flavors', 'price' => 7 ],
        [ 'name' => 'Chocolate Mousse', 'description' => 'Light and airy chocolate dessert topped with whipped cream', 'price' => 9 ]
    ]
];


$categoryIdByName = [];
foreach ($frontMenu as $catName => $_) {
    $ex = $categoryDao->getByName($catName);
    if (!$ex) {
        $inserted = $categoryDao->add([ 'name' => $catName ]);
        $categoryIdByName[$catName] = $inserted['category_id'];
    } else {
        $categoryIdByName[$catName] = $ex['category_id'];
    }
}


$insertedCount = 0;
foreach ($frontMenu as $catName => $items) {
    $cid = $categoryIdByName[$catName];
    foreach ($items as $it) {
        $existing = $menuItemDao->searchByName($it['name']);
        $existsExact = false;
        foreach ($existing as $row) {
            if ($row['name'] === $it['name'] && (int)$row['category_id'] === (int)$cid) {
                $existsExact = true;
                break;
            }
        }
        if (!$existsExact) {
            $menuItemDao->add([
                'name' => $it['name'],
                'description' => $it['description'],
                'price' => $it['price'],
                'category_id' => $cid
            ]);
            $insertedCount++;
        }
    }
}

echo "Menu synced from frontend. Inserted $insertedCount items.\n";
?>


