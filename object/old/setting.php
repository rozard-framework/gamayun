<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! class_exists( 'rozard_form_setting' ) ) {


    class rozard_form_setting {

        private string $title; 
        private string $unique; 
        private array  $fields;
        private array  $content;


        public function __construct( array $data ) {

            // content field
            $this->title  = $data['title'];
            $this->unique = $data['unique'];
            $this->fields = $data['fields'];

            // render setting
            $title  = '';
            $unique = $data['unique']; 
            $render = array( $this, 'render_section' ); 
            $page   = $data['filter']; 
            
            add_settings_field(
                'ads',
                'Test field',
                'ads_show_settings',
                $unique,
                'ads_id',
                array ( 'label_for' => 'ads_id' )
            );
            add_settings_section( $unique, $title, $render, $page, '' );
        }


        public function render_section() {

            $unique = str_slug( $this->unique );
            $fields = $this->fields;
            $title  = str_slug( $this->title );

            printf( '<section class="%s">', esc_attr( $unique ) );
                printf( '<h1> %s</h1>', esc_html( $title ) );
                rozard_render_general_field( $unique, 'setting', $fields, '' );
            printf( '</section>' ) ;
        }
    }
}