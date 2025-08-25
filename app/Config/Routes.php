<?php 
use CodeIgniter\Router\RouteCollection;

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Admin');

$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'Admin::index');
$routes->get('/login', 'Admin::index');
$routes->get('/logout', 'Admin::logout');
$routes->post('/admin/checkAdmin', 'Admin::checkAdmin');

$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/profile', 'Admin::profile');
$routes->post('/admin/profile', 'Admin::profile');
$routes->post('/admin/uploadImage', 'Admin::uploadImage');


