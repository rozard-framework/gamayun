<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }

if ( ! function_exists('proto_week_field') ){ 

    function proto_render_week_field( $field_id, $attrb, $field ){

        $date  = current_time('Y-m-d');
        $week  = date('W', strtotime($date));
        $nows  = current_time('Y').'-W'.$week;
        $value = ( ! empty ( $field['value' ]) ) ? $field['value'] : $nows ;

        printf('<input type="week" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value )
        );
    }
}
