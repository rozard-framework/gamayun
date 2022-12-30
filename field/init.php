<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }

if ( ! function_exists( 'rozard_field')  ) {


    require_once __DIR__ . '/policy.php';


    function rozard_field( string $field_id, string $mode, string $object, array $fields ) {
    

        // FIELD VALIDATION 
        
        if ( empty( $field_id ) ) {
            der( 'Render field error, field id is empty'); 
            return;
        }

        if ( empty( $object ) ) {
            dev( 'Render field mode used "block", due empty mode.' );
            return;
        }

        if ( empty( $fields ) ) {
            dev( 'Render field aborted, Data field object is empty at field id ' . $field_id . '.' );
            return;
        }


        // FIELD METHOD */    

        if ( $mode === 'render' ) {
            require_once __DIR__ . '/render.php';
            rozard_field_render( $field_id, $object, $fields );
        }
        else if ( $mode === 'saving' ) {
            require_once __DIR__ . '/saving.php';
            rozard_field_saving( $field_id, $object, $fields );
        }
    }
}





