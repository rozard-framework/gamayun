<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }

if ( ! function_exists('proto_select_field') ){ 

    function proto_render_select_field( string $field_id, string $attrb = null , $field = array() ){

        if ( ! is_array( $field['option'] ) && empty( $field['option'] ) ) {
            return;
        }

        printf( '<select id="%s" name="%s" class="select" %s >' , 
            esc_attr( $field_id ),
            esc_attr( $field_id ),
            $attrb
        );

        foreach( $field['option'] as $id => $option ) {

            $value = sanitize_text_field( $option );
            
            printf('<option value="%s" %s>%s</option>',
                esc_attr( $id ), 
                selected( esc_attr( $id ) , esc_attr( $value ), false ),
                esc_html( $value ), 
            );
        }

        printf( '</select>');
    }
}
