<?php
// config/config.php

/**
 * Application Configuration
 */

// Application Name
define('APP_NAME', 'StoryNest');

// Base URL Configuration
// IMPORTANT: This must point to the folder containing index.php.
// If your URL is http://localhost/testproject/public/index.php, this must be:
define('BASE_URL', 'http://localhost/testproject/public');

// Set Default Timezone
date_default_timezone_set('UTC');

/**
 * Session Configuration
 */
if (session_status() === PHP_SESSION_NONE) {
    // Prevent JavaScript access to session cookies (XSS protection)
    ini_set('session.cookie_httponly', 1);
    
    // Prevent session fixation attacks
    ini_set('session.use_only_cookies', 1);
    
    // If you have an SSL certificate locally, uncomment the line below for extra security:
    // ini_set('session.cookie_secure', 1);

    // Start the session
    session_start();
}

/**
 * Error Reporting (Development Mode)
 * Set these to 0 when you deploy to a live production server.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>