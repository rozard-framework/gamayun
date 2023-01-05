<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms') ) { exit; }
if ( ! class_exists('rozard_gamayun_media') ) {

    class rozard_gamayun_media extends rozard_gamayun_forms {

        
    /** DATUMS */

        private $fields;


    /** RUNITS */


        public function __construct( array $data ) {
            $this->hook( $data );
        }


        private function hook( array $data ) {

            // load field datums
            $this->fields = $this->prepare( $data );
                 
            add_filter( 'attachment_fields_to_edit', array( $this, 'edit' ), 10, 2);
            add_filter( 'attachment_fields_to_save', array( $this, 'save' ), 10, 2);
        }



    /** RENDER */


        public function edit( $form_fields, $post ) {
            foreach ( $this->fields as $key => $field ) {
                if ( ! usr_can( $field['access']['caps'] ) ) { 
                    continue;
                }
                $unique = $field['unique'];
                require_once forms_field . $field['type'] .'.php';
                $form_fields[$unique] = call_user_func( 'rozard_render_media_'. $field['type'] .'_field' , $field, $post->ID );
            }
            return $form_fields;
        }


    
    /** SAVING */


        public function save( $post, $attachment ) {
            foreach ( $this->fields as $key => $field ) {
                if ( ! usr_can( $field['access']['caps'] ) ) {
                    continue;
                }
                require_once forms_field . $field['type'] .'.php';
                call_user_func( 'rozard_saving_media_'. $field['type'] .'_field' , $field, array( $post, $attachment ) );
            }  
            return $post;
        }



    /** HELPER */
    }
}

/**
 * https://www.kevinleary.net/blog/add-custom-meta-fields-media-attachments-wordpress/
 */
