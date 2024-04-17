<?php
// Default to 'home' if no specific resource is requested
$request = $_GET['url'] ?? 'home';

switch ($request) {
    case 'home':
    case '': // Home page
        require './home.php';
        break;
    default:
        require 'views/404.php'; // 404 page
        break;
}

// // Check if the request is for the root directory
// if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php') {
//     // Serve home.php as the default page
//     include_once 'home.php';
//     exit(); // Exit to prevent further execution
// }