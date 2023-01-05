<?php


declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY') || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists('rozard_gamayun_forms') ) {
    
    class rozard_gamayun_forms{
    
        
    /** DATUMS */

        
        private array $data = array();


    /** RUNITS */


        public function __construct() {
            define( 'rozard_forms', __DIR__ . '/' );
            $this->load();
        }
    

        private function load() {
            
            define( 'forms_field', rozard_forms . 'fields/' );
            define( 'forms_model', rozard_forms . 'models/' );
            require_once  rozard_forms . 'fields/helper.php';
            $this->runs();
        }

        private function runs() {

            if ( uris_has( array( 'upload.php', 'media-new.php', 'admin-ajax' ))) {
                add_action( 'admin_init', array( $this, 'media' ));
            }
            else if ( uris_has( array ('post-new.php', 'post.php', 'admin-ajax' ))) {
                add_action( 'admin_init', array( $this, 'metabox' ));
            }
            else if ( uris_has( array(  'term.php', 'edit-tags.php', 'admin-ajax' ))) {
                add_action( 'init', array( $this, 'term' ));
            }
            else if ( uris_has( array( 'user-edit.php', 'profile.php', 'admin-ajax' ))) {
                add_action( 'admin_init', array( $this, 'user' ));
            }
            else if ( uris_has( array( 'options', 'admin-ajax' ))) {
                add_action( 'admin_init', array( $this, 'option' ));
            }
            else if ( uris_has( array(  'wp-signup.php', 'admin-ajax' ))) {
                add_action( 'init', array( $this, 'signup' ));
            }
        }


        private function data( string $scope ) {

            // register form filter
            $raw  = array();  
            $data = apply_filters( 'register_form' , $raw );
            $type = array( 'custom', 'media', 'metabox', 'option', 'signup', 'term', 'user'  );
            

            // validate laoder form
            if ( ! has_filter( 'register_form' ) || ! in_array( $scope, $type ) || empty( $data[ $scope ] ) ){
               return null;
            }
           
            // property storage for extracted scope
            $former = array();

            // extracting kind value and set extended attribute
            foreach( $data[ $scope ] as $form_id => $forms ) {

                $form_cap = $forms['access'];
                $scope_id = str_slug( $scope .'-'. $form_id );
                $formated = array();
                

                // validate current user has capability to access this form
                if ( ! has_caps( $form_cap ) ) {
                    continue;
                }

                // process field data
                foreach( $forms['fields'] as $field_id => $field ) {
                    
                    // validate current user has capability to access this field
                    if ( ! usr_can( $field['rules']['access'] )) {
                        continue;
                    }

                    // assigned unique value to field array key for prevent collision
                    $unique = str_slug( $form_id .'-'. $field_id );

                    // reassign all default field data to new object field
                    $formated[$unique] = $field;

                    // assign "unique" field attribute to new object field
                    $formated[$unique]['rules']['unique'] = $unique;
                    
                    // assigned "filter" field attribute to new object field
                    $formated[$unique]['rules']['filter'] = $forms['filter'];

                    // assigned "datums" field attribute to new object field
                    $formated[$unique]['rules']['datums'] = $forms['datums'];

                }

                // construct property form prototype model
                $former[$scope_id]['title']   = $forms['title'];
                $former[$scope_id]['filter']  = $forms['filter'];
                $former[$scope_id]['context'] = $forms['context'];
                $former[$scope_id]['layout']  = $forms['layout'];
                $former[$scope_id]['fields']  = $formated;
            }

            // return form proto data request
            return $former;
        } 
    

    
    /** METHOD */

        public function media() {
            $media = $this->data( 'media' );
            if ( ! empty( $media ) ) { 
                require_once rozard_forms . 'object/media.php';
                new rozard_gamayun_media( $media );
            }
            return;
        }


        public function metabox() {
            $metabox = $this->data( 'metabox' );
            if ( ! empty( $metabox ) ) { 
                require_once rozard_forms . 'object/metabox.php';
                new rozard_gamayun_metabox( $metabox );
            }
            return;
        }


        public function option() {
            $option = $this->data( 'option' );
            if ( ! empty( $option ) ) { 
                require_once rozard_forms . 'object/option.php';
                new rozard_gamayun_option( $option );
            }
            return;
        }


        public function signup() {
            $signup = $this->data( 'signup' );
            if ( ! empty( $signup ) ) { 
                require_once rozard_forms . 'object/signup.php';
                new rozard_gamayun_signup( $option );
            }
            return;
        }


        public function user() {
            $user = $this->data( 'user' );
            if ( ! empty( $user ) ) { 
                require_once rozard_forms . 'object/user.php';
                new rozard_gamayun_user( $user );
            }
            return;
        }


        public function term() {
            $term = $this->data( 'term' );
            if ( has_filter( 'term_form' ) ){
                require_once rozard_forms . 'object/term.php';
                foreach( $term as $key => $form ) {
                    new rozard_gamayun_term( $key, $form );
                }
            }
            return;
        }
    
    /** HELPER */


        public function prepare( $data ) {
            $saved = array();
            foreach( $data as $key => $box ) {
                $filter = ( empty( $box['filter'] ) ? null : $box['filter'] );
                foreach( $box['fields'] as $field_id => $field ) {
                    $field['unique'] = str_slug( $key.'-'. $field_id );
                    $field['filter'] = $filter;
                    $saved[]  = $field ;
                }
            }
            return $saved;
        }
    }
    new rozard_gamayun_forms;
}