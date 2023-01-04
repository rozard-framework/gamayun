<?php


/**
 * 
 * https://stackoverflow.com/questions/59589444/create-custom-registration-form-for-wordpress-multisite
 */


 
declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists( 'rozard_registration_form' ) ) {

    class rozard_registration_form {

        private string $titles;
        private string $unique;
        private string $policy;
        private string $former;

        private array  $fields;

        public function __construct( string $unique, array $former ) {

            $this->unique = $unique;
            $this->titles = sanitize_text_field( $former['title'] );
            $this->policy = sanitize_key( $former['policy'] );
            $this->fields = $former['fields'];

  
            //add_action( 'signup_extra_fields', array( $this, 'render_form' ) );
            add_action( 'signup_extra_fields', 'render_form',10,1 ); 
            add_action( 'user_register', array( $this, 'saving_form' ) );

        }


        public function render_form() {

            ?>
            <p>
                <label for="first_name">
                    <?php esc_html_e( 'First Name', 'first_name' ) ?> <br/>
                    <input type="text" class="regular_text" name="first_name" />
                </label>
            </p>
            <p>
                <label for="last_name">
                    <?php esc_html_e( 'Last Name', 'last_name' ) ?> <br/>
                    <input type="text" class="regular_text" name="last_name" />
                </label>
            </p>
            <?php
             return;
        }

        public function saving_form( $user_id ) {
            
        }

    }
}