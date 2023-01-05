<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) || ! defined( 'rozard_forms') ){ exit; }
if ( ! class_exists('rozard_gamayun_metabox') ) {

    class rozard_gamayun_metabox extends rozard_gamayun_forms {

    
    /** DATUMS */

        private array $render;
        private array $saving;


    /** RUNITS */


        public function __construct( array $data ) {
            // if ( locks_form !== 'metabox' ) { return; }
            $this->hookers( $data );
        }

        
        private function hookers( array $data ) {

            // assigned data
            $this->render = $data;
            $this->saving = $this->prepare( $data );

            // assigned hook
            add_action( 'add_meta_boxes', array( $this, 'creates' ), 99, 2 );
            add_action( 'save_post',      array( $this, 'savings' ), 99, 2 );
        }



    /** RENDER */


        public function creates( $post_type, $post ){

            foreach( $this->render as $id => $box ) {
              
                if ( ! in_array( $post_type, $box['filter'] ) || ! has_caps( $box['access'] ) ) {
                    continue;
                }

                $titles  =  __( pure_text( $box['title'] ) , 'rozard-engine' ) ;
                $contex  =  pure_key( $box['context'] );
                $policy  =  is_bole( $box['policy'] );
                $render  =  array( $this, 'renders' );
                $unique  =  str_slug( $id );
                $filter  =  pure_array( $box['filter'] );
                $fields  =  $box['fields'];
             
                // prepare field unique
                foreach ( $fields as $key => $field ) {
                    $fields[$key]['unique'] = str_slug( $unique .'-'. $key );
                    $fields[$key]['filter'] = $filter;
                }
                $parser  =  array( 'fields' => $fields );
               
                add_meta_box( $unique, $titles , $render , $filter, $contex, 'default', $parser );
            }
        }


        public function renders( $post, $parse ) {

            $data = $parse['args']['fields'];
            $poid = $post->ID;

            // validate users
            $post_type = get_post_type_object( $post->post_type );
			if ( ! usr_can( $post_type->cap->edit_post, $poid ) ) {
                return $poid;
			}
                     
            wp_nonce_field( $poid , '_metanonce' );

            foreach ( $data as $key => $field ) {
                if ( ! in_array( $post->post_type, $field['filter'] ) ) {
                    continue;
                }
                if ( ! has_caps( $field['caps'] ) ) {
                    continue;
                }
                require_once rozard_field . $field['type'] .'.php';
                call_user_func( 'rozard_render_metabox_'. $field['type'] .'_field' , $field, $poid );
            }
        }



    /** SAVING */


        public function savings( $post_id, $post ){

            // validate nonce
            if ( ! isset( $_POST[ '_metanonce' ] ) || ! wp_verify_nonce( $_POST[ '_metanonce' ], $post_id ) ) {
                return $post_id;
            }

            // validate users
            $post_type = get_post_type_object( $post->post_type );
			if ( ! usr_can( $post_type->cap->edit_post, $post_id ) ) {
                return $post_id;
			}
            
            // disable autosave
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { 
				return $post_id;
			}

            foreach( $this->saving as $field ) {
                if ( ! in_array( $post->post_type, $field['filter'] ) ) {
                    continue;
                }
                if ( ! has_caps( $field['caps'] ) ) {
                    continue;
                }
                require_once rozard_field . $field['type'] .'.php';
                call_user_func( 'rozard_saving_metabox_'. $field['type'] .'_field' , $field, $post_id );
            }
        }
    }
}