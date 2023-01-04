<?php



/** OPERATION */


    function rozard_saving_field( string $unique, $policy, string $refs, array $fields, $args ) {
        foreach( $fields as $field_id => $field ) {
            $field_type = sanitize_key( $field['type'] );
            call_user_func( 'saving_field_' . $field_type , $unique, $field_id, $policy, $refs, $field, $args );
        }
    }

    function rozard_saving_media_field( string $unique, $policy, string $refs, array $field, $args ) {
        $field_type = sanitize_key( $field['type'] );
        $value = call_user_func( 'saving_field_' . $field_type , $unique, $unique, $policy, $refs, $field, $args );
        return $value;
    }



/** METHOD */

    function saving_field_text( string $unique, string $field_id,  $policy, string $refs, array $field, $args ) {
        require_once  rozard_forms . 'field/saving/native-text.php';
        proto_saving_text_field(  $unique, $field_id, $policy, $refs, $field, $args );
    }
