<?php
require_once __DIR__ . '/userDAO.php';
require_once __DIR__ . '/menuItemDAO.php';
require_once __DIR__ . '/categoryDAO.php';
require_once __DIR__ . '/reservationDAO.php';
require_once __DIR__ . '/contactDAO.php';

$userDao = new UserDao();
$menuItemDao = new MenuItemDao();
$categoryDao = new CategoryDao();
$reservationDao = new ReservationDao();
$contactDao = new ContactDao();


$categoryNames = [
    'Appetizers',
    'Main Course',
    'Desserts',
    'Drinks'
];

$menuItemsSeed = [
    [ 'name' => 'Bruschetta', 'description' => 'Toasted bread with tomatoes and basil', 'price' => 6.50, 'category' => 'Appetizers' ],
    [ 'name' => 'Caesar Salad', 'description' => 'Romaine, croutons, parmesan, Caesar dressing', 'price' => 8.90, 'category' => 'Appetizers' ],
    [ 'name' => 'Spaghetti Carbonara', 'description' => 'Classic Italian pasta dish', 'price' => 12.99, 'category' => 'Main Course' ],
    [ 'name' => 'Grilled Salmon', 'description' => 'With lemon butter sauce', 'price' => 18.50, 'category' => 'Main Course' ],
    [ 'name' => 'Tiramisu', 'description' => 'Coffee-flavored Italian dessert', 'price' => 6.90, 'category' => 'Desserts' ],
    [ 'name' => 'Chocolate Lava Cake', 'description' => 'Warm cake with molten center', 'price' => 7.50, 'category' => 'Desserts' ],
    [ 'name' => 'Espresso', 'description' => 'Strong and bold', 'price' => 2.50, 'category' => 'Drinks' ],
    [ 'name' => 'House Lemonade', 'description' => 'Freshly squeezed', 'price' => 3.80, 'category' => 'Drinks' ]
];

$usersSeed = [
    [ 'name' => 'Admin', 'email' => 'admin@delicia.local', 'password' => 'admin123', 'role' => 'admin' ],
    [ 'name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password123', 'role' => 'customer' ]
];


$categoryIdByName = [];
foreach ($categoryNames as $catName) {
    $existing = $categoryDao->getByName($catName);
    if (!$existing) {
        $inserted = $categoryDao->add([ 'name' => $catName ]);
        $categoryIdByName[$catName] = $inserted['category_id'];
    } else {
        $categoryIdByName[$catName] = $existing['category_id'];
    }
}


$userIdByEmail = [];
foreach ($usersSeed as $u) {
    $existing = $userDao->getByEmail($u['email']);
    if (!$existing) {
        $inserted = $userDao->add([
            'name' => $u['name'],
            'email' => $u['email'],
            'password' => password_hash($u['password'], PASSWORD_DEFAULT),
            'role' => $u['role']
        ]);
        $userIdByEmail[$u['email']] = $inserted['user_id'];
    } else {
        $userIdByEmail[$u['email']] = $existing['user_id'];
    }
}


foreach ($menuItemsSeed as $mi) {
    $cid = $categoryIdByName[$mi['category']];
    $existingList = $menuItemDao->searchByName($mi['name']);
    $existsExact = false;
    foreach ($existingList as $row) {
        if ($row['name'] === $mi['name'] && (int)$row['category_id'] === (int)$cid) {
            $existsExact = true;
            break;
        }
    }
    if (!$existsExact) {
        $menuItemDao->add([
            'name' => $mi['name'],
            'description' => $mi['description'],
            'price' => $mi['price'],
            'category_id' => $cid
        ]);
    }
}


$johnId = $userIdByEmail['john@example.com'] ?? null;
if ($johnId) {
    $date = date('Y-m-d', strtotime('+1 day'));
    $time = '19:00:00';
    if ($reservationDao->isSlotAvailable($date, $time)) {
        $reservationDao->add([
            'user_id' => $johnId,
            'date' => $date,
            'time' => $time
        ]);
    }
}


$contactEmail = 'john@example.com';
$contactSubject = 'Info';
$contactMessage = 'Delicious food, thank you!';
$already = false;
$existingContacts = $contactDao->getByEmail($contactEmail);
foreach ($existingContacts as $c) {
    if (($c['subject'] ?? '') === $contactSubject && ($c['message'] ?? '') === $contactMessage) {
        $already = true;
        break;
    }
}
if (!$already) {
    $contactDao->add([
        'user_name' => 'John Doe',
        'user_email' => $contactEmail,
        'subject' => $contactSubject,
        'message' => $contactMessage
    ]);
}

echo "Seeding completed.\n";
