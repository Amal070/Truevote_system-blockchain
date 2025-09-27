<?php
/**
 * Simple Autoloader for PHPMailer (no Composer)
 */

spl_autoload_register(function ($class) {
    // Only handle PHPMailer classes
    if (strpos($class, 'PHPMailer\\PHPMailer') === 0) {
        $base = dirname(__DIR__) . '/includes/PHPMailer/';
        $map = [
            'PHPMailer\\PHPMailer\\PHPMailer' => 'PHPMailer.php',
            'PHPMailer\\PHPMailer\\SMTP'      => 'SMTP.php',
            'PHPMailer\\PHPMailer\\Exception' => 'Exception.php',
        ];

        if (isset($map[$class])) {
            $file = $base . $map[$class];
            if (file_exists($file)) {
                require_once $file;
                return;
            } else {
                throw new RuntimeException("PHPMailer file missing: $file");
            }
        }
    }
});
