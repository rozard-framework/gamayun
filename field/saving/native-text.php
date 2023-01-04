<?php


if ( function_exists('proto_saving_text_field') ) {
    return;
}


function proto_saving_text_field( string $unique, string $field_id, string $policy, string $refs, array $field, $args ) {

    if ( $refs === 'media' ) {
        $value = media_proto_saving_text_field( $unique, $field_id, $policy, $field, $args );
            return $value;
    }
    else if ( $refs === 'metabox' ) {
        metabox_proto_saving_text_field( $unique, $field_id, $policy, $field, $args );
    } 
    else if ( $refs === 'profile' ) {
        profile_proto_saving_text_field( $unique, $field_id, $policy, $field, $args );
    } 
    else if ( $refs === 'taxonomy' ) {
        taxonomy_proto_saving_text_field( $unique, $field_id, $policy, $field, $args );
    } 

}

/** MEDIA HANDLER */

    function media_proto_saving_text_field( $unique, $field_id, $policy, $field, $args ) {
        $value = sanitize_text_field( $args ); // for media form this function just to handler policy and value only
        return $value;
    }


/** METABOX HANDLER  */

    function metabox_proto_saving_text_field( $unique, $field_id, $policy, $field, int $args ) {
        $keys_id = str_slug( $unique .'-'. $field_id );
        $post_id = $args;
        if ( isset( $_POST[ $keys_id ] ) ) {
            update_post_meta( $post_id, $keys_id , sanitize_text_field( $_POST[ $keys_id ] ) );
        } else {
            delete_post_meta( $post_id, $keys_id );
        }
    }


/** PROFILE HANDLER  */

    function profile_proto_saving_text_field( $unique, $field_id, $policy, $field, $args ) {

        $keys_id = str_slug( $unique .'-'. $field_id );
        $user_id = $args;

        if ( isset( $_POST[ $keys_id ] ) ) {
            update_user_meta( $user_id, $keys_id, sanitize_text_field( $_POST[ $keys_id ] ) );
        } else {
            delete_user_meta( $user_id, $keys_id );
        }
        return;
    }


/** TAXONOMY HANDLER  */

    function taxonomy_proto_saving_text_field( $unique, $field_id, $policy, $field, $args ) {

        $keys_id = str_slug( $unique .'-'. $field_id );
        $term_id = $args;

        if ( isset( $_POST[ $keys_id ] ) ) {
            update_term_meta( $term_id, $keys_id, sanitize_text_field( $_POST[ $keys_id ] ) );
        }
        return;
    }