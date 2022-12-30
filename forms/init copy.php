<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! defined( 'rozard_form' ) ) { define( 'rozard_form', __DIR__ . '/' ) ; }


if ( ! function_exists( 'create_metabox' ) ) {

   

    /** METABOX FORM */

        function create_metabox( array $form ) {
            require_once rozard . 'inputs/field/init.php';
            require_once rozard_form . 'default/metabox.php';
            new input_form_metabox( $form );
        }

} 
