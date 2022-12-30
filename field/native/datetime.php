<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }

if ( ! function_exists('proto_render_datetime_field') ){ 

    function proto_render_datetime_field( $field_id, $attrb, $field ){

        $date  = current_time('Y-m-d');
        $time  = current_time('h:m:s');
        $nows  = $date.'T'.$time;
        $value = ( ! empty ( $field['value' ]) ) ? $field['value'] : $nows ;

        printf('<input type="datetime-local" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value )
        );
    }

}