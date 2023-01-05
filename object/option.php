<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms') ){ exit; }
if ( ! class_exists('rozard_gamayun_option') ) {

    class rozard_gamayun_option{

    /** DATUMS */

        private $option;

    /** RUNITS */


        public function __construct( array $data ) {
            $this->hookers( $data  );
        }


        private function hookers( $data ) {
            foreach( $data as $form_id => $option ) {
                if ( ! uri_has( $option['filter'] ) && ! has_caps( $option['access']  ) ) {
                    continue;
                }
                $this->caller( $form_id , $option );
            }
        }



    /** PROPER */


        private function unique( string $form_id, array $option ) {
            $fields = $option;
            foreach ( $fields as $key => $field ) {
                $fields[$key]['unique'] = str_slug( $form_id .'-'. $key );
            }
            return $fields;
        }


        private function caller( string $form_id, array $option ) {

            // prepare option data
            $contex = pure_slug( $option['context'] );
            $filter = pure_slug( $option['filter'] );
            $fields = $this->unique( $form_id, $option['fields']);

            $this->parser( $contex, $filter, $fields);

        }


        private function parser( string $contex, string $filter, array $fields ) {

            foreach ( $fields as $key => $field ) {
                if ( ! has_caps( $field['caps'] ) ) {
                    continue;
                } 

                $caller = array( $this, 'render' );
                $unique = str_slug( $field['unique'] );
                $titles = pure_text( $field['label'] );
                $parser = array(
                    'field'     => $field,
                );

                // module
                require_once rozard_field . $field['type'] .'.php';

                // render
                add_settings_field( $unique, $titles, $caller, $filter, $contex, $parser );

                // saving
                $this->saving( $unique, $field );
            }
        }


    
    /** RENDER */


        public function render( array $data ) {
            $field = $data['field'];
            $types = $data['field']['type'];
            $optid = $data['field']['unique'];
            call_user_func( 'rozard_render_option_'. $types .'_field' , $field, $optid );
        }



    /** SAVING */


        public function saving( string $unique, array $field ) {
            $types = $field['type'];
            if ( isset( $_POST[ $unique ] ) ) {
                $value = call_user_func( 'rozard_saving_option_'. $types .'_field' , $field , $_POST[ $unique ] );
                update_option( $unique,  $value );
            }
        }
    }
}