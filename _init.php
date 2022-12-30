<?php


declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! defined( 'rozard_forms' ) ) { define( 'rozard_forms', __DIR__ . '/forms' .'/' ) ; }
if ( ! defined( 'rozard_field' ) ) { define( 'rozard_field', __DIR__ . '/field' .'/' ) ; }

require_once rozard_forms . 'init.php';
require_once rozard_field . 'init.php';