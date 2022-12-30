<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }

if ( ! function_exists( 'rozard_field_saving ' ) ) {


    /** INITIALIZE */

        function rozard_field_saving( string $id, string $object, array $fields ) {


            // MODE SELECTIVE

            call_user_func( 'field_saving_'. $object, $id, $fields );
        }



        function field_saving_metabox( string $id, array $fields ) {

            


        }


    /** SAVING METHOD */
        
        
}