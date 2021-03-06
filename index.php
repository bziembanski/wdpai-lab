<?
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('home', 'DefaultController');
Routing::get('search', 'AnnouncementController');
Routing::get('addAnn', 'AnnouncementController');

Routing::get('ann', 'AnnouncementController');
Routing::get('profile', 'ProfileController');
Routing::get('user', 'ProfileController');
Routing::post('rate', 'RatingController');
Routing::post('deleteAnn', 'AnnouncementController');
Routing::post('searchAction', 'AnnouncementController');
Routing::post('getNotifications', 'NotificationController');
Routing::post('sendNotif', 'NotificationController');
Routing::post('deleteNotif', 'NotificationController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::post('logout', 'SecurityController');
Routing::post('editProfile', 'EditProfileController');

Routing::run($path);