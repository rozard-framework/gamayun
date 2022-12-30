<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }


if ( ! function_exists('proto_render_checklist_field') ){ 

    function proto_render_checklist_field( string $field_id, string $attrb = null , $field = array() ){

        if ( ! is_array( $field['option'] ) && empty( $field['option'] ) ) {
            return;
        }

        foreach( $field['option'] as $id => $option ) {
            echo '<div class="option">';
                $value = sanitize_text_field( $option['value'] );
                printf('<input type="checkbox" id="%s" name="%s" %s value="%s">',
                    esc_attr( $id ),
                    esc_attr( $id ), 
                    $attrb,
                    esc_attr( $value )
                );

                $label = sanitize_text_field( $option['label'] );
                printf('<label for="%s">%s</label>', esc_attr( $id ), esc_html( $label ) );
            echo '</div>';
        }
    }
}
