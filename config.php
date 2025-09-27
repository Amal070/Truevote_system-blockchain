<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Config
define("DB_HOST", "localhost");
define("DB_NAME", "truevote_db");   // your DB name
define("DB_USER", "root");
define("DB_PASS", "");


// App Config
define("APP_NAME", "TrueVote");
define("APP_URL", "http://localhost/truevote_system/");

// Error Reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);
