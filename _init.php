<?php


declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY') || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists('rozard_gamayun_forms') ) {
    
    class rozard_gamayun_forms{
    
    /** DATUMS */

        
        private array $data = array();


    /** RUNITS */


        public function __construct() {
            $this->modular();
            $this->hookers();
        }
    

        private function modular() {
            define( 'rozard_forms', __DIR__ . '/' );
            define( 'rozard_field', rozard_forms . 'fields/' );
            require_once  rozard_forms . 'fields/helper.php';
            // require_once  rozard_forms . 'object/registration.php';
        }

    
        private function hookers() {

            if ( uri_has( 'upload.php' ) || uri_has( 'media-new.php' ) || uri_has( 'admin-ajax' ) ) {
                add_action( 'admin_init', array($this, 'media'   ) );
            }

            if ( uri_has( 'post-new.php' ) || uri_has( 'post.php' )  || uri_has( 'admin-ajax' ) ) {
                add_action( 'admin_init', array($this, 'metabox' ) );
            }

            if ( uri_has( 'term.php' ) ||  uri_has( 'edit-tags.php' ) || uri_has( 'admin-ajax') ) {
                add_action( 'init', array($this, 'terms' ) );
            }

            if ( uri_has( 'user-edit.php' ) || uri_has( 'profile.php' ) || uri_has( 'admin-ajax' )  ) {
                add_action( 'admin_init', array($this, 'users' ) );
            }

            if ( uri_has( 'user-edit.php' ) || uri_has( 'profile.php' ) || uri_has( 'admin-ajax' )  ) {
                add_action( 'admin_init', array($this, 'users' ) );
            }

            if ( uri_has( 'options' ) || uri_has( 'admin-ajax' ) ) {
                add_action( 'admin_init', array($this, 'option' ) );
            }

            if ( uri_has( 'wp-signup.php' ) || uri_has( 'admin-ajax' )  ) {
                add_action( 'init', array($this, 'signup' ) );
            }
        }
    

    
    /** METHOD */

        
        public function media() {
            $media = apply_filters( 'media_form', $this->data );
            if ( has_filter( 'media_form' ) ){
                require_once  rozard_forms . 'object/media.php';
                new rozard_gamayun_media( $media );
            }
            return;
        }


        public function metabox() {
            $metabox = apply_filters( 'metabox_form', $this->data );
            if ( has_filter( 'user_form' ) ) { 
                require_once rozard_forms . 'object/metabox.php';
                new rozard_gamayun_metabox( $metabox );
            }
            return;
        }


        public function option() {
            $option = apply_filters( 'option_form', $this->data );
            if ( has_filter( 'option_form' ) ) { 
                require_once rozard_forms . 'object/option.php';
                new rozard_gamayun_option( $option );
            }
            return;
        }


        public function signup() {
            $option = apply_filters( 'signup_form', $this->data );
            if ( has_filter( 'signup_form' ) ) { 
                require_once rozard_forms . 'object/signup.php';
                new rozard_gamayun_signup( $option );
            }
            return;
        }


        public function users() {
            $users = apply_filters( 'user_form', $this->data );
            if ( has_filter( 'user_form' ) ) { 
                require_once  rozard_forms . 'object/user.php';
                new rozard_gamayun_user( $users );
            }
            return;
        }


        public function terms() {
            $term = apply_filters( 'term_form', $this->data );

            if ( has_filter( 'term_form' ) ){
                require_once  rozard_forms . 'object/term.php';
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