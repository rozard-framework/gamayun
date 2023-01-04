<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }


/** RENDER FIELD */

    if ( ! function_exists('proto_render_checkbox_field') ){ 

        function proto_render_checkbox_field( string $unique, string $field_id, $policy, string $refs, array $field, $args = null ){

            if ( ! is_array( $field['option'] ) && empty( $field['option'] ) ) {
                return;
            }

           
            $key_id = sanitize_key( $unique .'_'. $field_id );
            $value  = sanitize_text_field( $field['option']['value'] );
            
            dev(get_post_meta( $args, $key_id, true ));
            var_dump( get_post_meta( $args, $key_id, true ) ) ;
            
            printf('<input type="checkbox" id="%s" name="%s" value="%s">',
                esc_attr( $key_id ),
                esc_attr( $key_id ), 
                esc_attr( $value )
            );

            $label = sanitize_text_field( $field['option']['label'] );
            printf('<label>%s</label>', esc_html( $label ) );

        }
    };

