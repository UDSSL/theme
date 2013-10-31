<?php
/**
 * UDSSL Cron Bootstrapper
 *
 *  wget -q -O – http://local.udssl.dev/wp-content/themes/udssl-theme/cron/bootstrapper.php >/dev/null 2>&1
 */
require_once '../../../../wp-load.php';

require_once UDS_PATH . 'cron/class-udssl-report.php';
$reports = new UDSSL_Reports();
$reports->send_email_report();

global $udssl_theme;
$udssl_theme->utils->log('general', 'cron bootstrapper called');
?>
