<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms') ){ exit; }
if ( ! class_exists('rozard_gamayun_user') ) {

    class rozard_gamayun_user extends rozard_gamayun_forms{


    /** DATUMS */

        private $public;
        private $privat;
        private $saving;
    

    /** RUNITS */


        public function __construct( array $data ) {
            // if ( locks_form !== 'user' ) { return; }
            $this->hooker( $data );
        }


        private function hooker( array $data ) {

            // data populating
            foreach ( $data as $key => $form ) {
                $datum = ( $form['filter'] === 'private' ) ? $this->privat[$key] = $form : $this->public[$key] = $form;
            }
            $this->saving = $this->prepare( $data );

            // init hook
            if ( ! empty( $this->privat ) ) {
                add_action( 'profile_personal_options', array( $this, 'private' ) );
            }
           
            if ( ! empty( $this->public ) ) { 
                add_action( 'show_user_profile',    array( $this, 'publics' ) );
                add_action( 'edit_user_profile',    array( $this, 'publics' ) );
            }

            add_action( 'edit_user_profile_update', array( $this, 'savings' ) );
            add_action( 'personal_options_update',  array( $this, 'savings' ) );
        }


    
    /** RENDER */


        public function private( $user ) {
            
            $data_pv = $this->privat;
            $user_id = $this->userid( $user );

            if ( ! empty( $data_pv ) && ! empty( $user_id ) && usr_can( 'edit_user', $user_id ) ) {
                foreach( $data_pv as $key => $data ) {
                    $this->parser( $key, $data, $user_id );
                }
            }
          
            return;
        }


        public function publics( $user ) {

            $data_pb = $this->public;
            $user_id = $this->userid( $user );

            if ( ! empty( $data_pb ) && ! empty( $user_id ) && current_user_can( 'edit_user', $user_id ) ) {
                foreach( $data_pb as $key => $data ) {
                    $this->parser( $key, $data, $user_id );
                }
            }
            return;
        }


        private function parser( $key, $data, $user_id ) {

            $unique = sanitize_key( $key );
            $titles = sanitize_text_field( $data['title'] );
            $fields = $data['fields'];

            // prepare field unique
            foreach ( $fields as $key => $field ) {
                $fields[$key]['unique'] = str_slug( $unique .'-'. $key );
            }

            $this->render( $unique, $titles, $fields, $user_id );
        }


        private function render( string $unique, string $title, array $fields, int $user_id ) {

            printf( '<section id="%s">', esc_attr( $unique ) );
            printf( '<h2> %s </h2>', esc_html( $title ) );
            printf( '<table class="form-table" role="presentation">');
                foreach ( $fields as $key => $field ) {
                    if ( ! usr_can( $field['rules']['access'] ) ) {
                        continue;
                    }
                    require_once forms_field . $field['type'] .'.php';
                    call_user_func( 'rozard_render_user_'. $field['type'] .'_field' , $field, $user_id );
                }
            printf( '</table>');
            printf( '</section>' );
        }



    /** SAVING */


        public function savings( $user_id ) {

            // validate 
            if( ! isset( $_POST[ '_wpnonce' ] ) || ! wp_verify_nonce( $_POST[ '_wpnonce' ], 'update-user_' . $user_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
                return;
            }

            foreach( $this->saving as $field ) {
                if ( ! usr_can( $field['rules']['access'] ) ) {
                    continue;
                }
                require_once forms_field . $field['type'] .'.php';
                call_user_func( 'rozard_saving_user_'. $field['type'] .'_field' , $field, $user_id );
            }
        }


    /** HELPER */


        private function userid( $user ) {

            if ( ! empty( $user->ID ) ) {
                $user_id = $user->ID;
                return $user_id;
            }
            else if ( empty( $user->ID ) && take_uri_param( 'user_id' ) !== null ) {
                $user_id = take_uri_param( 'user_id' );
                return $user_id;
            } 
            else if ( ! empty( get_current_user_id() ) ) {
                $user_id = get_current_user_id();
                return $user_id;
            }
            else {
                return null;
            } 
        }
    }
}