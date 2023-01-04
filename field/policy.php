<?php


/** FORMS POLICY */


    // RENDER METHOD
    function rozard_render_general_form_policy( string $object, $args ) {
        
    }

  
    function rozard_metabox_render_form_policy( string $object, int $args ) {
        $encryption = get_post_meta( $args, 'data-encryption-policy', true );
        $diseminate = get_post_meta( $args, 'disemination-policy', true );
        rozard_render_layout_form_policy( $encryption, $diseminate );
    }


    // FORM POLICY LAYOUT
    function rozard_render_layout_form_policy( $encryption, $diseminate ) {

        echo '<div class="form-policy flex-a">';
            echo '<h2> Information Policy </h2>';
            echo '<div class="protection">';
                echo '<label class="scope"> Form Data Processing </label>';
                render_form_cryptography_policy( $encryption );
            echo '</div>';
            echo '<div class="disemination">';
                echo '<label class="scope"> Scope of Dissemination </label>';
                render_form_disemination_policy( $diseminate );
            echo '</div>';
        echo '</div>';
    }


    function render_form_cryptography_policy( $encryption ) {

        printf(
            '<div class="option">
                <span class="normal">Normal </span>
                <label class="form-switch">
                    <input type="checkbox" id="%s" name="%s" value="%s" %s><i class="form-icon"></i> 
                </label>
                <span class="encrypt">Encrypt</span> 
            </div>',
            esc_attr( 'data-encryption-policy' ),
            esc_attr( 'data-encryption-policy' ), 
            esc_attr( $encryption ), 
            checked( $encryption, true , false ),
        );
    }


    function render_form_disemination_policy( $diseminate ) {

        echo '<div class="option">';
            render_form_public_disemination( $diseminate );
            render_form_intern_disemination( $diseminate );
            render_form_privat_disemination( $diseminate );
        echo '</div>';

    }


    function render_form_public_disemination( $diseminate ) {
        
        printf(' 
            <input type="radio" id="%s" name="%s" value="public" %s>
            <label for="%s">Public </label>',
            esc_attr( 'disemination-policy' ), 
            esc_attr( 'disemination-policy' ), 
            checked(  $diseminate, 'public', false ),
            esc_attr( 'disemination-policy-public' ), 
        );
    }


    function render_form_intern_disemination( $diseminate ) {

        printf(' 
            <input type="radio" id="%s" name="%s" value="internal" %s>
            <label for="%s">Internal </label>',
            esc_attr( 'disemination-policy' ), 
            esc_attr( 'disemination-policy' ), 
            checked(  $diseminate, 'internal', false ),
            esc_attr( 'disemination-policy-internal' ), 
        );
    }


    function render_form_privat_disemination( $diseminate ) {

        printf(' 
            <input type="radio" id="%s" name="%s" value="private" %s>
            <label for="%s">Private </label>',
            esc_attr( 'disemination-policy' ), 
            esc_attr( 'disemination-policy' ), 
            checked(  $diseminate, 'private', false ),
            esc_attr( 'disemination-policy-private' ), 
        );
    }



    // SAVING METHOD 
    function saving_form_policy( string $object, $args = null ) {

        if ( $object === 'metabox' ) {
            rozard_metabox_saving_form_policy( $args );
        }
    }


    function rozard_metabox_saving_form_policy( $post_id ) {

        if ( isset( $_POST[ 'data-encryption-policy' ] ) ) {
            update_post_meta( $post_id, 'data-encryption-policy', true );
        } 
        else {
            delete_post_meta( $post_id, 'data-encryption-policy' );
        }

        
        // DISEMINATION POLICY
        if( isset( $_POST[ 'disemination-policy' ] ) ) {
            $disemination_valid = array( 'public', 'internal', 'private' );
            $disemination_value = sanitize_key( $_POST[ 'disemination-policy' ] );
            if ( in_array( $disemination_value, $disemination_valid  ) ) {
                update_post_meta( $post_id, 'disemination-policy', sanitize_key( $_POST[ 'disemination-policy' ] ) );
            }
        } 
        else {
            delete_post_meta( $post_id, 'disemination-policy' );
        }
    }