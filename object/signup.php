<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists( 'rozard_gamayun_signup' ) ) {

    class rozard_gamayun_signup extends rozard_gamayun_forms{

    /** DATUM */


        private array $fields;


    /** RUNIT */

        public function __construct( array $data ) {

          
            $this->fields = $this->prepare( $data );
         
            //add_action( 'signup_extra_fields', array( $this, 'render_form' ) );
            add_action( 'signup_extra_fields', array( $this, 'render' ), 10, 1 ); 
            add_action( 'user_register',       array( $this, 'saving' ), 10, 2 );
        }


    /** RENDER */

        public function render( $errors ) {
            foreach ( $this->fields as $key => $field ) {
                require_once forms_field . $field['type'] .'.php';
                call_user_func( 'rozard_render_signup_'. $field['type'] .'_field' , $field, null );
            }
        }


    /** SAVING */

        public function saving( $user_id, $userdata ) {
            foreach ( $this->fields as $key => $field ) {
                require_once forms_field . $field['type'] .'.php';
                call_user_func( 'rozard_saving_signup_'. $field['type'] .'_field' , $field, $user_id );
            }
        }
    }
}




/**
 *  registration form
 *  https://www.cssigniter.com/how-to-add-custom-fields-to-the-wordpress-registration-form/
 * 
 *  hook belum menyimpa value, cari hook untuk user
 */