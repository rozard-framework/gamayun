<?php

declare(strict_types=1);
if ( ! defined('ABSPATH') || ! defined('WP_LIBRARY')  || ! defined( 'rozard' ) ){ exit; }
if ( ! defined( 'rozard_form' ) ) { define( 'rozard_form', __DIR__ . '/' ) ; }


function rozard_register_forms( $metaboxes ) {

    return $metaboxes;
}
add_filter( 'register_forms', 'rozard_register_forms' );



function rozard_metabox() {

    $forms = array();

    if( has_filter( 'register_forms' ) ) {

        $forms = apply_filters( 'register_forms', $forms );

        foreach( $forms as $form ) {

            add_meta_box(
                $form['unique'],
				__( $form['titles'] , 'rozard-engine' ),
				'render_metaboxes' ,
                $form['filter'],
                $form['context'],
				null,
			);
        }
    }
}
add_action( 'add_meta_boxes', 'rozard_metabox' , 99 );


function render_metaboxes() {
    
}




