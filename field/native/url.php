<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }


if ( ! function_exists('proto_render_url_field') ){ 

    function proto_render_url_field( $field_id, $attrb, $field ){

        $value = ( ! empty ( $field['value' ]) ) ? sanitize_url( $field['value'] ) : null;

        printf('<input type="url" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value )
        );
    }
}

