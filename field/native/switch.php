<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_form' )  ){ exit; }


if ( ! function_exists('proto_render_switch_field') ){ 

    function proto_render_switch_field( $field_id, $attrb, $field ){

        $value = ( ! empty ( $field['value' ]) ) ?  $field['value'] : null;

        printf('<label class="form-switch">');

        printf('<input type="checkbox" id="%s" name="%s" %s value="%s" %s><i class="form-icon"></i> ',
            esc_attr( $field_id ),
            esc_attr( $field_id ), 
            $attrb,
            esc_attr( $value ),
            checked( $field_id, $value, false )
        );


        printf('</label>');
    }
}

