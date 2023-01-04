<?php



declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms' )  ){ exit; }


if ( ! function_exists('proto_render_text_field') ){ 

    function proto_render_text_field( string $unique, string $field_id, $policy, string $refs, array $field, $args ){
        
        if ( $refs === 'media' ) {
            $field = media_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
            return $field;
        } 
        else if ( $refs === 'metabox' ) {
            metabox_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
        } 
        else if ( $refs === 'profile' ) {
            profile_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
        } 
        else if ( $refs === 'registration' ) {
            registration_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
        } 
        else if ( $refs === 'taxonomy-add' ) {
            taxonomy_adds_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
        }
        else if ( $refs === 'taxonomy-edit' ) {
            taxonomy_edit_proto_render_text_field( $unique, $field_id, $policy, $field, $args );
        }
        
    }

/** MEDIA HANDLER */
    function media_proto_render_text_field( string $unique, string $field_id, $policy, array $field, int $post_id ) {

        $value = str_text( get_post_meta( $post_id, $unique, true ) );
        $id_el = 'attachments-'. esc_attr( $post_id ) . '-'. esc_attr( $unique );
        $names = 'attachments['. esc_attr( $post_id ).']['. esc_attr( $unique ) .']';
        $attrb = '';
        $label = str_text( $field['label'] );
        $helps = 'testing';
        $model = sprintf( '<input type="text" id="%s" name="%s" %s value="%s">',
                    esc_attr( $id_el ),
                    esc_attr( $names ), 
                    $attrb,
                    $value
                );
        $field = array( 'label' => $label, 'input' => 'html', 'html' => $model, 'help' => $helps );
        return $field;
    }


/** METABOX HANDLER */

    function metabox_proto_render_text_field( string $unique, string $field_id, $policy, array $field, int $args ) {

        $keys_id = str_slug( $unique .'-'. $field_id );
        $value   = get_post_meta( $args, $keys_id, true ) ;
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<label for="%s">%s</label>', esc_attr($keys_id ), esc_html( $label ) );
        printf('<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $keys_id ),
            esc_attr( $keys_id ), 
            $attrb,
            esc_attr( $value )
        );
    }


/** PROFILE HANDLER */

    function profile_proto_render_text_field( string $unique, string $field_id, $policy, array $field, int $args ) {

        $keys_id = str_slug( $unique .'-'. $field_id );
        $value   = get_user_meta( $args,$keys_id, true ) ;
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<label for="%s">%s</label>', esc_attr($keys_id ), esc_html( $label ) );
        printf( '<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $keys_id ),
            esc_attr( $keys_id ), 
            $attrb,
            esc_attr( $value )
        );

        return;
    }


/** REGISTRATION HANDLER */

    function registration_proto_render_text_field( string $unique, string $field_id, $policy, array $field, $args ) {
       
        $keys_id = str_slug( $unique .'-'. $field_id );
        $value   = '';
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<p>' );
        printf( '<label for="%s">%s</label>', esc_attr( $keys_id ), esc_html( $label ) );
        printf( '<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $keys_id ),
            esc_attr( $keys_id ), 
            $attrb,
            esc_attr( $value )
        );
        printf( '</p>' );

        return;
    }


/** TAXONOMY HANDLER */

    function taxonomy_adds_proto_render_text_field( string $unique, string $field_id, $policy, array $field, $args ) {

        $keys_id = str_slug( $unique .'-'. $field_id );
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<div class="form-field">' );
        printf( '<label for="%s">%s</label>', esc_attr( $keys_id ), esc_html( $label ) );
        printf( '<input type="text" name="%s" id="%s" %s >',
            esc_attr( $keys_id ),
            esc_attr( $keys_id ), 
            $attrb,
        );
        printf( '</div>' );

        return;
    }

    
    function taxonomy_edit_proto_render_text_field( string $unique, string $field_id, $policy, array $field, $args ) {

        // prepare taxonomy id
        $term_object = $args[0];
        $taxo_object = $args[1];
        $term_id = $term_object->term_id;

        // prepare field value
        $keys_id = str_slug( $unique .'-'. $field_id );
        $value   = get_term_meta( $term_id,$keys_id, true ) ;
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<tr class="form-field">' );
        printf( '<th><label for="%s">%s</label></th>', esc_attr( $keys_id ), esc_html( $label ) );
        printf( '<td>' );
        printf('<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $keys_id ),
            esc_attr( $keys_id ), 
            $attrb,
            esc_attr( $value )
        );
        printf( '</td>' );
        printf( '</tr>' );

        return;
    }
}