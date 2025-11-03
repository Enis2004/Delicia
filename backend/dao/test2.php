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

function hr_line($title) {
    echo "\n==================== $title ====================\n";
}


hr_line('CATEGORY CRUD');
$cat = $categoryDao->add([ 'name' => 'Test Category' ]);
print_r(['created_category' => $cat]);

$catFetched = $categoryDao->getByName('Test Category');
print_r(['fetched_by_name' => $catFetched]);

$categoryDao->rename($cat['category_id'], 'Test Category Updated');
$catUpdated = $categoryDao->getById($cat['category_id']);
print_r(['updated_category' => $catUpdated]);

$catsWithCounts = $categoryDao->listWithItemCounts();
print_r(['list_with_counts' => $catsWithCounts]);

// MENU ITEM CRUD
hr_line('MENU ITEM CRUD');
// ensure a category exists for item
$itemCategory = $categoryDao->add([ 'name' => 'Temp Items Cat' ]);
$item = $menuItemDao->add([
    'name' => 'Test Item',
    'description' => 'Test description',
    'price' => 9.99,
    'category_id' => $itemCategory['category_id']
]);
print_r(['created_item' => $item]);

$itemFetched = $menuItemDao->getWithCategory($item['item_id']);
print_r(['fetched_with_category' => $itemFetched]);

$menuItemDao->update([ 'price' => 11.49 ], $item['item_id']);
$itemUpdated = $menuItemDao->getById($item['item_id']);
print_r(['updated_item' => $itemUpdated]);

$search = $menuItemDao->searchByName('Test');
print_r(['search_by_name' => $search]);

$priceFilter = $menuItemDao->filterByPriceRange(5, 12);
print_r(['filter_by_price' => $priceFilter]);

// USER CRUD
hr_line('USER CRUD');
$user = $userDao->add([
    'name' => 'CRUD Tester',
    'email' => 'crud.tester@example.com',
    'password' => password_hash('initialPass1', PASSWORD_DEFAULT),
    'role' => 'customer'
]);
print_r(['created_user' => $user]);

$byEmail = $userDao->getByEmail('crud.tester@example.com');
print_r(['fetched_by_email' => $byEmail]);

$userDao->update([ 'name' => 'CRUD Tester Updated' ], $user['user_id']);
$userUpdated = $userDao->getById($user['user_id']);
print_r(['updated_user' => $userUpdated]);

$customers = $userDao->listByRole('customer');
print_r(['list_by_role_customer' => $customers]);

$userDao->changePassword($user['user_id'], 'newSecret123');
$authOk = $userDao->authenticate('crud.tester@example.com', 'newSecret123');
print_r(['auth_after_password_change' => (bool)$authOk]);

// RESERVATION CRUD
hr_line('RESERVATION CRUD');
$resDate = date('Y-m-d', strtotime('+2 day'));
$resTime = '20:00:00';
$available = $reservationDao->isSlotAvailable($resDate, $resTime);
print_r(['slot_available_before' => $available]);

if ($available) {
    $reservation = $reservationDao->add([
        'user_id' => $user['user_id'],
        'date' => $resDate,
        'time' => $resTime
    ]);
    print_r(['created_reservation' => $reservation]);
}

$byUser = $reservationDao->getByUserId($user['user_id']);
print_r(['reservations_by_user' => $byUser]);

$range = $reservationDao->getByDateRange(date('Y-m-d'), date('Y-m-d', strtotime('+7 day')));
print_r(['reservations_next_7_days' => $range]);

$forDay = $reservationDao->listForDayWithUsers($resDate);
print_r(['reservations_for_day_with_users' => $forDay]);

if (!empty($byUser)) {
    $reservationDao->cancel($byUser[0]['reservation_id']);
    $byUserAfterCancel = $reservationDao->getByUserId($user['user_id']);
    print_r(['by_user_after_cancel' => $byUserAfterCancel]);
}

// CONTACT CRUD
hr_line('CONTACT CRUD');
$contact = $contactDao->add([
    'user_name' => 'CRUD Tester',
    'user_email' => 'crud.tester@example.com',
    'subject' => 'Test Subject',
    'message' => 'Hello from CRUD tests'
]);
print_r(['created_contact' => $contact]);

$byContactEmail = $contactDao->getByEmail('crud.tester@example.com');
print_r(['contacts_by_email' => $byContactEmail]);

$searchSubject = $contactDao->searchBySubject('Test');
$recent = $contactDao->getRecent(5, 0);
print_r(['search_by_subject' => $searchSubject]);
print_r(['recent_contacts' => $recent]);

// CLEANUP (delete created records) - optional; comment out to keep data
hr_line('CLEANUP');
if (isset($contact['contact_id'])) {
    $contactDao->delete($contact['contact_id']);
}
if (isset($user['user_id'])) {
    $userDao->delete($user['user_id']);
}
if (isset($item['item_id'])) {
    $menuItemDao->delete($item['item_id']);
}
if (isset($itemCategory['category_id'])) {
    $categoryDao->delete($itemCategory['category_id']);
}
if (isset($cat['category_id'])) {
    $categoryDao->delete($cat['category_id']);
}

echo "\nAll CRUD tests executed.\n";
?>


