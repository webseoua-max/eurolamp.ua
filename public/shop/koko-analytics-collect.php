<?php
/**
 * @package koko-analytics
 * @license GPL-3.0+
 * @author Danny van Kooten
 *
 * This file acts as an optimized endpoint file for the Koko Analytics plugin.
 */

// path to pageviews.php file in uploads directory
define('KOKO_ANALYTICS_UPLOAD_DIR', 'wp-content/uploads/koko-analytics');
define('KOKO_ANALYTICS_TIMEZONE', '+03:00');

// required files
require 'wp-includes/plugin.php';
require 'wp-content/plugins/koko-analytics/src/Resources/functions/collect.php';


// check if IP address is on list of addresses to ignore
if (!isset($_POST['test']) && in_array(KokoAnalytics\get_client_ip(), array (
))) {
    exit;
}

// function call to collect the request data
KokoAnalytics\collect_request();
