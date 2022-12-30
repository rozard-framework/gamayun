<?php


declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists( 'input_form_metabox' ) ) {




/** METHOD SECTION */

    class input_form_metabox{

        private $formed;
        private $unique;
        private $titles;
        private $policy;
        private $object;
        private $filter;
        private $access;
        private $fields;
        private $contex;


        public function __construct( array $formed ) {

            $this->formed = $formed;
            $this->fields = $formed['field_id'];
            $this->unique = sanitize_key( $formed['keys'] );
            $this->titles = sanitize_text_field( $formed['title'] );
            $this->object = $formed['object'];
            $this->policy = $formed['policy'];
            $this->filter = $formed['filter'];
            $this->access = $formed['caps'];
            $this->contex = sanitize_key( $formed['context'] );
            
            // render metabox
            add_action( 'add_meta_boxes', array( $this, 'register_metabox' ), 99 );
            add_action( 'save_post',      array( $this, 'savings_metaboxs' ), 99, 2);
        }


        public function register_metabox( $post_type ) {

            add_meta_box(
                $this->unique,
				__( $this->titles , 'rozard-engine' ),
				array( $this, 'render_metaboxes' ),
                $this->filter,
                $this->contex,
				null,
			);
        }


        public function render_metaboxes( $post ) {

            
            // validate current user capabilities
            if ( ! is_caps_valid( $this->access ) ) {
                dev( 'Render field aborted, current user need higgher access level.' );
                return $post;
            }

            
            // check current user permissions
            $post_caps = get_post_type_object( $post->post_type );
            if ( ! current_user_can( $post_caps->cap->edit_post, $post->ID ) ) {
                return $post;
            }
            

            // validate post type here
            if( ! in_array( $post->post_type, $this->filter ) ) {
                dev( 'Render field aborted, this post its not a apart from metabox target render on form with id ' . esc_attr( $this->unique ) . '.' );
                return $post;
            }
            
            
            // define nonce for this metabox
            wp_nonce_field( $this->unique , '_rozard_metabox_nonce' );

            
            // render form policy
            if ( $this->policy === true ) {
                render_form_policy( $this->object, $post->ID );
            }

            
            // render form fields
            rozard_field(  $this->unique, 'render', $this->object , $this->fields );
        }


        
        public function savings_metaboxs( $post_id, $post ) {


            // nonce validation
            if ( ! isset( $_POST[ '_rozard_metabox_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_rozard_metabox_nonce' ], $this->unique ) ) {
                return $post_id;
            }


            // check current user permissions
            $post_type = get_post_type_object( $post->post_type );
            if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
                return $post_id;
            }


            // Do not save the data if autosave
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return $post_id;
            }


            // validate post type here
            if( ! in_array( $post->post_type, $this->filter ) ) {
                dev( 'Render field aborted, this post its not a apart from metabox target render on form with id ' . esc_attr( $this->unique ) . '.' );
                return $post_id;
            }


            // saved form policy
            if ( $this->policy === true  ) {
                saving_form_policy( $this->object, $post_id );
            }

            // saved form fields
            rozard_field( $this->unique, 'saving', $this->object, $this->fields );
        }
    }
}