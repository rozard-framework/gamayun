<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }

if ( ! function_exists('proto_render_email_field') ){ 

    function proto_render_email_field( $field_id, $attrb, $field ){

        $value = ( ! empty ( $field['value' ]) ) ? sanitize_email( $field['value'] ) : null;

        printf('<input type="email" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value )
        );
    }
}

