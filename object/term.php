<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms')  ){ exit; }
if ( ! class_exists('rozard_gamayun_term') ) {

    class rozard_gamayun_term  extends rozard_gamayun_forms{

    /** DATUMS */

        private array $fields;
        private string $terms;

    /** RUNITS */


        public function __construct( string $unique, array $data ) {
            $this->fielder( $unique, $data );
        }


        private function fielder( string $unique, array $data ){
            
            // set field unique
            $fields = $data['fields'];
            foreach ( $fields as $key => $field ) {
                $fields[$key]['unique'] = str_slug( $unique .'-'. $key );
            }

            // asign field
            $this->fields = $fields;

            // asign terms
            $this->terms = $data[ 'filter' ];
           
            // init hooker
            $this->hookers($this->terms);
        }


        private function hookers( string $term ) {
            add_action( $term .'_add_form_fields',  array( $this, 'add_term_meta' ) );
            add_action( $term .'_edit_form_fields', array( $this, 'editing' ), 10, 2 );
            add_action( 'created_'. $term,  array( $this, 'savings' ), 10, 2 );
            add_action( 'edited_'. $term,  array( $this, 'savings' ), 10, 2 );
        }



    /** RENDER */


        public function add_term_meta( $taxonomy ) {
            if ( ! current_user_can( 'edit_'. $this->terms ) ) {
                return;
            }
            foreach ( $this->fields as $key => $field ) {
                if ( ! is_caps( $field['caps'] ) ) {
                    continue;
                }
                require_once rozard_field . $field['type'] .'.php';
                call_user_func( 'rozard_render_term_'. $field['type'] .'_field' , $field, null );
            }
        }


        public function editing( $term, $taxonomy ) {
            if ( ! current_user_can( 'edit_'. $this->terms ) ) {
                return;
            }
            foreach ( $this->fields as $key => $field ) {
                if ( ! is_caps( $field['caps'] ) ) {
                    continue;
                }
                require_once rozard_field . $field['type'] .'.php';
                call_user_func( 'rozard_render_term_'. $field['type'] .'_field' , $field, $term->term_id );
            }
        }



    /** SAVING */


        public function savings( $term_id ) {
            if ( ! current_user_can( 'edit_'. $this->terms ) ) {
                return;
            }
            foreach ( $this->fields as $key => $field ) {
                if ( ! is_caps( $field['caps'] ) ) {
                    continue;
                }
                require_once rozard_field . $field['type'] .'.php';
                call_user_func( 'rozard_saving_term_'. $field['type'] .'_field' , $field, $term_id );
            }
        }
    }
}