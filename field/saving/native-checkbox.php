<?php

declare(strict_types=1);
// if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }



/** METABOX SAVING FIELD */

    if ( ! function_exists('proto_saving_checkbox_field') ){ 


        function proto_saving_checkbox_field( string $unique, string $field_id, string $policy, string $refs, array $field, $args = null  ) {

            $key_id = sanitize_key( $unique .'_'. $field_id );
            $post_id = $args;

            if( isset( $_POST[ $key_id  ] ) ) {
                update_post_meta( $post_id, $key_id , sanitize_key( $_POST[ $key_id ] ) );
            } else {
                delete_post_meta( $post_id, $key_id );
            }
        }




        // CHEKBOX SAVING HELPER
    };