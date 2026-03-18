<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../src/load_env.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';

// Парсим путь напрямую из URL — не зависим от роутера
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri  = rtrim($uri, '/') ?: '/';
$path = ltrim(str_replace('/admin', '', $uri), '/');

// Login и logout — без проверки авторизации
if ($path === 'login' || $path === 'logout') {
    if ($path === 'login') {
        AdminController::login();
    } else {
        AdminController::logout();
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($path === 'events/delete') {
        AdminController::deleteEvent();
    }
    if ($path === 'events/toggle') {
        AdminController::toggleEvent();
    }
    if ($path === 'events/create') {
        AdminController::createEvent();
        exit;
    }
    if ($path === 'events/edit') {
        AdminController::editEvent();
        exit;
    }
    if ($path === 'faqs/save') {
        AdminController::saveFaq();
    }
    if ($path === 'faqs/delete') {
        AdminController::deleteFaq();
    }
    if ($path === 'testimonials/save') {
        AdminController::saveTestimonial();
    }
    if ($path === 'testimonials/delete') {
        AdminController::deleteTestimonial();
    }
    if ($path === 'brands/save') {
        AdminController::saveBrand();
    }
    if ($path === 'brands/delete') {
        AdminController::deleteBrand();
    }
    if ($path === 'clients/save') {
        AdminController::saveClient();
    }
    if ($path === 'clients/delete') {
        AdminController::deleteClient();
    }
    if ($path === 'suppliers/save') {
        AdminController::saveSupplier();
    }
    if ($path === 'suppliers/delete') {
        AdminController::deleteSupplier();
    }
    if ($path === 'settings') {
        AdminController::settings();
    }
    if ($path === 'gallery/delete') {
        AdminController::deleteFile();
        exit;
    }
}
if ($path === 'gallery/delete') {
    header('Location: /admin/gallery');
    exit;
}

switch ($path) {
    case '':
        AdminController::dashboard();
        break;
    case 'contacts':
        AdminController::contacts();
        break;
    case 'b2b':
        AdminController::b2b();
        break;
    case 'callbacks':
        AdminController::callbacks();
        break;
    case 'settings':
        AdminController::settings();
        break;
    case 'brands':
        AdminController::brands();
        break;
    case 'clients':
        AdminController::clients();
        break;
    case 'suppliers':
        AdminController::suppliers();
        break;
    case 'suppliers/save':
        AdminController::saveSupplier();
        break;
    case 'suppliers/delete':
        AdminController::deleteSupplier();
        break;
    case 'stats':
        AdminController::stats();
        break;
    case 'gallery':
        AdminController::gallery();
        break;
    case 'events':
        AdminController::events();
        break;
    case 'events/create':
        AdminController::createEvent();
        break;
    case 'events/edit':
        AdminController::editEvent();
        break;
    case 'faqs':
        AdminController::faqs();
        break;
    case 'faqs/save':
        AdminController::saveFaq();
        break;
    case 'faqs/delete':
        AdminController::deleteFaq();
        break;
    case 'testimonials':
        AdminController::testimonials();
        break;
    case 'testimonials/save':
        AdminController::saveTestimonial();
        break;
    case 'testimonials/delete':
        AdminController::deleteTestimonial();
        break;
    case 'users':
        AdminController::users();
        break;
    case 'users/export':
        AdminController::exportUser();
        break;
    case 'users/delete':
        AdminController::deleteUser();
        break;
    default:
        header('Location: /admin');
        exit;
}
