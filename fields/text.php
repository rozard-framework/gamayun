<?php



/** OBJECT */



    // media
    function rozard_render_media_text_field( array $field, int $post_id ) {
        $template = rozard_text_field_media( $field, $post_id );
        return $template;
    }

    function rozard_saving_media_text_field( array $field, array $args ) {
        $attachment  = $args[1];
        $unique      = $field['unique'];      
        $value       = $attachment[ $unique ];
        $post        = $args[0];
        $final       = ( isset( $value ) ) ? update_post_meta( $post['ID'], $unique , $value ) : delete_post_meta( $post['ID'], $unique );
    }



    // metabox
    function rozard_render_metabox_text_field( array $field, int $post_id ) {
        $value = get_post_meta( $post_id, $field['unique'] , true );
        rozard_text_field_block( $value, $field );
    }

    function rozard_saving_metabox_text_field( array $field, int $post_id ) {
        $unique = $field['unique'];
        $datum  = $_POST[ $unique  ];
        $value  = sanitize_text_field( $datum );
        $final  = ( isset( $datum ) ) ? update_post_meta( $post_id, $unique , $value ) : delete_post_meta( $post_id, $unique );
    }



    // option 
    function rozard_render_option_text_field( array $field, $unique ) {
        $value = get_option( $unique );
        rozard_text_field_option( $value, $field );
    }


    function rozard_saving_option_text_field( array $field, string $value ) {
        $final = sanitize_text_field( $value );
        return $final;
    }

    

    // signup
    function rozard_render_signup_text_field( array $field, $unique ) {
        rozard_text_field_signup( $field, $unique);
    }

    function rozard_saving_signup_text_field( array $field, int $user_id ) {
        $unique = $field['unique'];
        $datum  = $_POST[ $unique  ];
        $value  = sanitize_text_field( $datum );
        $final  = ( isset( $datum ) ) ? update_user_meta( $user_id, $unique, $value ) : null ;
    }



    // term
    function rozard_render_term_text_field( array $field, $args ) {
        $value = ( $args === null ) ? null : get_term_meta( $args, $field['unique'], true );  
        if ( $value === null ) {
            rozard_text_field_terms( $value, $field );
        }
        else {
            rozard_text_field_table( $value, $field );
        }
    }

    function rozard_saving_term_text_field( array $field, int $term_id ) {
        $unique = $field['unique'];
        $datum  = $_POST[ $unique ];
        $value  = sanitize_text_field( $datum );
        $final  = ( isset( $datum ) ) ? update_term_meta( $term_id, $unique , $value ) : update_term_meta( $term_id, $unique );
    }



    // user
    function rozard_render_user_text_field( array $field, int $user_id ) {
        $value = get_user_meta( $user_id, $field['unique'] , true );
        rozard_text_field_table(  $value, $field  );
    }

    function rozard_saving_user_text_field( array $field, int $user_id ) {
        $unique = $field['unique'];
        $datum  = $_POST[ $unique  ];
        $value  = sanitize_text_field( $datum );
        $final  = ( isset( $datum ) ) ? update_user_meta( $user_id, $unique, $value ) : delete_user_meta( $user_id, $unique );
    }




/** RENDER */

   
    // block ( general )
    function rozard_text_field_block( string $value, array $field ) {

        $unique = $field['unique'];
        $attrb  = '';
        $label  = sanitize_text_field( $field['label'] );

        printf( '<div class="row">' );
        printf( '<label for="%s">%s</label>', esc_attr( $unique ), esc_html( $label ) );
        printf('<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $unique ),
            esc_attr( $unique ), 
            $attrb,
            esc_attr( $value )
        );
        printf( '</div>' );
    }


    
    // table ( general )
    function rozard_text_field_table( string $value, array $field ) {

        $unique  = $field['unique'];
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        
        printf( '<tr class="form-field">' );
        printf( '<th class="row"><label for="%s">%s</label></th>', esc_attr( $unique ), esc_html( $label ) );
        printf( '<td><input type="text" id="%s" name="%s" %s value="%s"></td>',
            esc_attr( $unique ),
            esc_attr( $unique ), 
            $attrb,
            esc_attr( $value )
        );
        printf( '</tr>' );
    }


    // media ( upload.php )
    function rozard_text_field_media( array $field, int $post_id ) {
        
        $unique  = $field['unique'];
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );

        $value = str_text( get_post_meta( $post_id, $unique, true ) );
        $id_el = 'attachments-'. esc_attr( $post_id ) . '-'. esc_attr( $unique );
        $names = 'attachments['. esc_attr( $post_id ).']['. esc_attr( $unique ) .']';
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


    // signup ( wp-signup.php )
    function rozard_text_field_signup( array $field, $args ) {
        $unique  = $field['unique'];
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );
        printf( '<div class="row">' );
            printf( '<label for="%s">%s</label>', esc_attr( $unique ), esc_html( ucwords( $label )) );
            printf( '<input type="text" id="%s" name="%s" %s value="">',
                esc_attr( $unique ),
                esc_attr( $unique ), 
                $attrb,
            );
        printf( '</div>' );
    }


    // options ( for eption page )
    function rozard_text_field_option( string $value, array $field ) {

        $unique  = $field['unique'];
        $attrb   = '';

        printf( '<input type="text" id="%s" name="%s" %s value="%s">',
            esc_attr( $unique ),
            esc_attr( $unique ), 
            $attrb,
            esc_attr( $value )
        );
    }


    // term ( edit-tags.php )
    function rozard_text_field_terms( $value, array $field  ) {

        $unique  = $field['unique'];
        $attrb   = '';
        $label   = sanitize_text_field( $field['label'] );

        printf( '<div class="form-field %s">', esc_attr( $unique ));
            printf( '<label for="%s">%s</label>', esc_attr( $unique ), esc_html( $label ) );
            printf('<input type="text" id="%s" name="%s" %s>',
                esc_attr( $unique ),
                esc_attr( $unique ), 
                $attrb,
            );
        printf( '</div>' );
    }