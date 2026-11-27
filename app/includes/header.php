<?php
// app/includes/header.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title><?= APP_NAME ?> - A little magic in every story</title>
    <meta name="description" content="Explore wonderful children's stories, learn new things, and let your imagination grow.">
    <meta name="theme-color" content="#7C5CFC">

    <!-- CSRF Token for JavaScript Fetch API Requests -->
    <meta name="csrf-token" content="<?= generateCSRFToken() ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Global Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <!-- Specific Module Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/reader.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    
    <!-- ADDED: Professional Homepage Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">

    <!-- Expose BASE_URL to JavaScript -->
    <script>
        const BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>
<body>