<?php

require 'vendor/autoload.php';

require __DIR__ . '/rest/services/BaseService.php';
require __DIR__ . '/rest/services/ContactService.php';
require __DIR__ . '/rest/services/CategoryService.php';
require __DIR__ . '/rest/services/MenuItemService.php';
require __DIR__ . '/rest/services/ReservationService.php';
require __DIR__ . '/rest/services/UserService.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('contactService', 'ContactService');
Flight::register('categoryService', 'CategoryService');
Flight::register('menuItemService', 'MenuItemService');
Flight::register('reservationService', 'ReservationService');
Flight::register('userService', 'UserService');

require_once __DIR__ . '/rest/routes/ContactRoutes.php';
require_once __DIR__ . '/rest/routes/CategoryRoutes.php';
require_once __DIR__ . '/rest/routes/MenuItemRoutes.php';
require_once __DIR__ . '/rest/routes/ReservationRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';

Flight::start();

?>
