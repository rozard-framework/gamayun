<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }


/** RENDER FIELD */

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

/** SAVING FIELD */

    if ( ! function_exists('proto_saving_checkbox_field') ){ 

        function proto_saving_checkbox_field( string $object_id, string $field_id, string $object, array $field, $args = null ){

            call_user_func( $object .'_saving_checkbox_field', $object_id, $field_id, $field, $args );

        }

        // METABOX OBJECT
        function metabox_saving_checkbox_field( $object_id, $field_id, $field, $post_id ) {

            $key_id = $object_id .'_'. $field_id;

            if( isset( $_POST[ $key_id  ] ) ) {
                update_post_meta( $post_id, $key_id , sanitize_text_field( $_POST[ $key_id ] ) );
            } else {
                delete_post_meta( $post_id, $key_id );
            }
        }

        // CHEKBOX SAVING HELPER
    };