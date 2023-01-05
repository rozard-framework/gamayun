<?php



/** RENDER FIELD  */


    // method
    function rozard_render_field( array $field, $value = null ) {

        // supported field
        $string  = array( 'checkbox', 'color', 'date', 'datetime', 'email', 'hidden', 'image', 'month', 'password', 'tel', 'radio', 'text', 'time', 'url', 'week' );
        $decimal = array( 'number', 'range' );
        $custom  = array( 'divider', 'search', 'switch', 'textarea' );


        // current field type
        $format = $field['type'];
        

        // load field template
        if ( in_array( $format, $string ) ) 
        {
            rozard_render_string_field( $field, $value );
        }
        else if ( in_array( $format, $decimal ) ) 
        {
            rozard_render_decimal_field( $field, $value );
        }
        else 
        {
            dev( $format .' field type it\'s not supported.' );
        }
    }


    // strings
    function rozard_render_string_field( array $field, string $value = null ) {

        // field metadata
        $value  = pure_text( $value );
        $unique = $field['unique'];


        // render field
        $fields = sprintf( '<input type="%s" id="%s" name="%s" value="%s" />', 
                            esc_attr( $type ), 
                            esc_attr( $unique ), 
                            esc_attr( $unique ), 
                            esc_attr( $value ) 
                        );


        // return field
        return $fields;
    }


    // decimal
    function rozard_render_decimal_field( array $field, int $value = null ) {

        // field metadata
        $value  = absint( $value );
        $unique = $field['unique'];


        // render field
        $fields = sprintf( '<input type="%s" id="%s" name="%s" value="%u" />', 
                    esc_attr( $type ), 
                    esc_attr( $unique ), 
                    esc_attr( $unique ), 
                    esc_attr( $value ) 
                );


        // return field        
        return $fields;
    }