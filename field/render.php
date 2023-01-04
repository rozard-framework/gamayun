<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }
if ( ! function_exists( 'rozard_field_render' ) ) {


/** REFERER */

    function rozard_render_field( string $unique, $policy, string $refs, array $fields, $args = null ) {
        foreach( $fields as $field_id => $field ) {
            $field_type = sanitize_key( $field['type'] );
            call_user_func( 'render_field_' . $field_type , $unique, $field_id, $policy, $refs, $field, $args );
        }
    }

    function rozard_render_media_field( string $unique, $policy, string $refs, array $field, $args ) {
        $field_type = sanitize_key( $field['type'] );
        $field = call_user_func( 'render_field_' . $field_type , $unique, $unique, $policy, $refs, $field, $args );
        return $field;
    }


/** NATIVES FIELD */ 

    function render_field_text( string $unique, string $field_id, $policy, string $refs, array $field, $args ) {
        require_once __DIR__ . '/render/native-text.php';

        if ( $refs == 'media') {
            $field =  proto_render_text_field( $unique, $field_id, $policy, $refs, $field, $args );
            return $field;
        }
        else {
            proto_render_text_field( $unique, $field_id, $policy, $refs, $field, $args );
        }
    }
}

