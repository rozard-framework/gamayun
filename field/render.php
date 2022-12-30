<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }



if ( ! function_exists( 'rozard_field_render' ) ) {


    function rozard_field_render( string $id, string $object, array $fields ) {
  
        $table_mod = array( 'profile' );
        $block_mod = array( 'metabox' );

        if ( in_array( $object , $table_mod )  ) {
            call_user_func( 'rozard_field_mode_table' , $id, $fields );
        }
        else if ( in_array( $object , $block_mod )  ){
            call_user_func( 'rozard_field_mode_block' , $id, $fields );
        }
        else {
            dev('Display method is undefined for field id' . esc_attr( $id ) );
        }
    }

    
    function rozard_field_mode_table( string $id, array $fields ) {

        echo '<table id="'. esc_attr( $id ) .'" class="form table">';

            foreach( $fields as $field_id => $field ) {

                if ( empty( $field['type'] ) || ! is_caps_valid( $field['caps'] ) || empty( $field_id ) ) {
                    continue;
                }

                $label = ( ! empty( $field['label'] ) ) ? sanitize_text_field( $field['label'] ) : null ;
                $type  = ( ! empty( $field['type'] ) )  ? ' field-'. sanitize_html_class( $field['type'] ) : null ;
                $class = ( ! empty( $field['elm-cls'] ) ) ? ' ' .sanitize_html_class( $field['elm-cls'] ) : null;
                $id    = ( ! empty( $field['elm-ids'] ) ) ? sanitize_html_class( $field['elm-ids'] ) : null; 
                $width = ( ! empty( $field['elm-wid'] ) ) ? 'width:' . sanitize_html_class( $field['elm-wid'] ) .'%': null;  
                
                echo '<tr id="'.esc_attr( $id ).'" class="row'.esc_attr( $class . $type ).'" style="'.esc_attr( $width ).'">';
                    echo '<th class="title">';
                        echo '<label for="'. esc_attr($field_id). '">'. esc_html( $label ) .'</label>';
                    echo '</th>';
                    echo '<td>';
                        call_user_func( 'rozard_field_render_method' , $field_id, $field );
                    echo '</td>';
                echo '</tr>';
            }
        echo '</table>';
    }


    function rozard_field_mode_block( string $id, array $fields ) {

        echo '<section id="'. esc_attr( $id ) .'" class="form block">';
            
            foreach( $fields as $field_id => $field ) {

                if ( empty( $field['type'] ) || ! is_caps_valid( $field['caps'] ) || empty( $field_id ) ) {
                    continue;
                }

                $label = ( ! empty( $field['label'] ) ) ? sanitize_text_field( $field['label'] ) : null;
                $type  = ( ! empty( $field['type'] ) )  ? ' field-'. sanitize_html_class( $field['type'] ) : null;
                $class = ( ! empty( $field['elm-cls'] ) ) ? sanitize_html_class( $field['elm-cls'] ) : null;
                $id    = ( ! empty( $field['elm-ids'] ) ) ? sanitize_html_class( $field['elm-ids'] ) : null; 
                $width = ( ! empty( $field['elm-wid'] ) ) ? 'width:' . sanitize_html_class( $field['elm-wid'] ) .'%': null; 

                echo '<div id="'.esc_attr( $id ).'" class="row '.esc_attr( $class . $type ).'" style="width: '.esc_attr( $width ).'">';
                    echo '<div class="title">';
                        echo '<label for="'. esc_attr($field_id). '">'. esc_html( $label ) .'</label>';
                    echo '</div>';
                    echo '<div class="field">';
                        call_user_func( 'rozard_field_render_method' , $field_id, $field );
                    echo '</div>';
                echo '</div>';
            }
        echo '</section>';
    }


    function rozard_field_render_method( string $field_id, array $field ) {

        $type  = sanitize_key( $field['type'] );
        $attrb = rozard_render_field_attrib( $field );
        
        printf( '<div class="box">' );
            rozard_render_field_before( $field );
            call_user_func( 'render_field_' . $type , $field_id, $attrb, $field );
            rozard_render_field_afters( $field );
        printf( '</div>' );

        rozard_render_field_descs( $field );
        rozard_render_field_error( $field );
    }




/** NATIVES TYPE FIELD */


    function render_field_button( string $field_id, string $attrb = null, array $data ) {

        printf('<input type="button" class="button button-primary" id="%s" name="%s" %s value="%s">',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr($data['value'])
        );
    }


    function render_field_checkbox( string $field_id, string $attrb = null , array $data ) {
        require_once __DIR__ . '/native/checkbox.php';
        proto_render_checkbox_field( $field_id, $attrb, $data );
    }


    function render_field_color( string $field_id, string $attrb, array $data ) {
        require_once __DIR__ . '/native/color.php';
        proto_render_color_field( $field_id, $attrb, $data );
    }


    function render_field_date( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/date.php';
        proto_render_date_field( $field_id, $attrb, $data );
    }


    function render_field_datetime( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/datetime.php';
        proto_render_datetime_field( $field_id, $attrb, $data );
    }


    function render_field_divider( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/divider.php';
        proto_render_divider_field( $field_id, $attrb, $data );
    }


    function render_field_editor( string $field_id, array $attrb, array $data ) {
        $value = ( ! empty ( $data['value' ]) ) ? $data['value'] : '';
        wp_editor( $value , $field_id , $attrb );
    }


    function render_field_email( string $field_id, string $attrb, array $data ) {
        require_once __DIR__ . '/native/email.php';
        proto_render_email_field( $field_id, $attrb, $data );
    }


    function render_field_hidden( string $field_id, string $attrb, array $data ) {
        require_once __DIR__ . '/native/hidden.php';
        proto_render_hidden_field( $field_id, $attrb, $data );
    }


    function render_field_image( string $field_id, string $attrb, array $data ) {
        require_once __DIR__ . '/native/image.php';
        proto_render_image_field( $field_id, $attrb, $data );
    }


    function render_field_month( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/month.php';
        proto_render_month_field( $field_id, $attrb, $data );
    }


    function render_field_number( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/number.php';
        proto_render_number_field( $field_id, $attrb, $data );
    }


    function render_field_password( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/password.php';
        proto_render_password_field( $field_id, $attrb, $data );
    }


    function render_field_phone( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/phone.php';
        proto_render_phone_field( $field_id, $attrb, $data );
    }


    function render_field_radio( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/radio.php';
        proto_render_radio_field( $field_id, $attrb, $data );
    }


    function render_field_range( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/range.php';
        proto_render_range_field( $field_id, $attrb, $data );
    }


    function render_field_search( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/search.php';
        proto_render_search_field( $field_id, $attrb, $data );
    }


    function render_field_switch( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/switch.php';
        proto_render_switch_field( $field_id, $attrb, $data );
    }


    function render_field_text( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/text.php';
        proto_render_text_field( $field_id, $attrb, $data );
    }


    function render_field_textarea( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/textarea.php';
        proto_render_textarea_field( $field_id, $attrb, $data );
    }


    function render_field_time( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/time.php';
        proto_render_time_field( $field_id, $attrb, $data );
    }


    function render_field_url( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/url.php';
        proto_render_url_field( $field_id, $attrb, $data );
    }


    function render_field_week( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/native/week.php';
        proto_render_week_field( $field_id, $attrb, $data );
    }

    

/** OBJECTS TYPE FIELD */



    function render_field_file( string $field_id, string $attrb, array $data ) {
        require_once __DIR__ . '/object/file.php';
        proto_render_file_field( $field_id, $attrb, $data );
    }



/** OPTIONS TYPE FIELD */



    function render_field_select( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/option/select.php';
        proto_render_select_field( $field_id, $attrb, $data );
    }


    function render_field_multiple( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/option/multiple.php';
        proto_render_multiple_field( $field_id, $attrb, $data );
    }


    function render_field_checklist( string $field_id, string $attrb = null, array $data ) {
        require_once __DIR__ . '/option/checklist.php';
        proto_render_checklist_field( $field_id, $attrb, $data );
    }




/** RENDERS HELPER */


    function rozard_render_field_attrib( array $data ) {

        if ( $data['type'] !==  'editor' ) {
            $attribute = ( ! empty( $data['attrib'] ) && is_array( $data['attrib'] ) ) ? array_attr( $data['attrib'] ) : null;
        }
        else {
            $attribute = $data['attrib'] ;
        }
        return $attribute;
    }


    function rozard_render_field_before( array $data ) {

        if ( ! empty( $data['before'] ) ) {
            printf( '<span class="before">%s</span>', esc_html( $data['before'] ) );
        }
    }


    function rozard_render_field_afters( array $data ) {
        
        if ( ! empty( $data['after'] ) ) {
            printf( '<span class="after">%s</span>', esc_html( $data['after'] ) );
        }

    }


    function rozard_render_field_descs( array $data ) {

        if ( ! empty( $data['desc'] ) ) {
            printf( '<p class="description">%s</p>', esc_html( $data['desc'] ) );
        }

    }


    function rozard_render_field_error( array $data ) {

        if ( ! empty( $data['error'] ) ) {
            printf( '<p class="error">%s</p>', esc_html( $data['error'] ) );
        }

    }
}

