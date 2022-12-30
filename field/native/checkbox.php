<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }


/** RENDER FIELDS */

    if ( ! function_exists('proto_render_checkbox_field') ){ 

        function proto_render_checkbox_field( string $field_id, string $attrb = null , $field = array() ){

            if ( ! is_array( $field['option'] ) && empty( $field['option'] ) ) {
                return;
            }

            $value = sanitize_text_field( $field['option']['value'] );
            printf('<input type="checkbox" id="%s" name="%s" %s value="%s">',
                esc_attr( $field_id ),
                esc_attr( $field_id ), 
                $attrb,
                esc_attr( $value )
            );

            $label = sanitize_text_field( $field['option']['label'] );
            printf('<label>%s</label>', esc_html( $label ) );

        }
    };