<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }


/** RENDER */

    if ( ! function_exists('proto_render_color_field') ){ 

        function proto_render_color_field( $field_id, $attrb, $field ){

            $value = ( ! empty ( $field['value' ]) ) ? sanitize_hex_color( $field['value'] ) : sanitize_hex_color('#000');

            printf('<input type="color" id="%s" name="%s" %s value="%x">',
                esc_attr( $field_id ),
                esc_attr( $field_id ), 
                $attrb,
                esc_attr( $value )
            );
        }
    }
