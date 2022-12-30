<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }


if ( ! function_exists('proto_render_text_field') ){ 

    function proto_render_text_field( $field_id, $attrb, $field ){

        $value = ( ! empty ( $field['value' ]) ) ? sanitize_text_field( $field['value'] ) : null;

        printf('<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value )
        );
    }
}
