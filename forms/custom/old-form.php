<?php

/*** layout MODULE */
if ( ! class_exists( 'forms' ) ) {

    class forms extends cores{

        // master
        protected $data = array();

        // models
        protected $cores;
        protected $render;
        protected $section;
        protected $extras;


        public function __construct( $data = array() ) {

            if ( empty( $data ) === false &&  $data !== null  ) {
                foreach( $data as $model ) {
                    array_push( $this->data, $model);
                }
            }
            add_action('current_screen', array( $this, 'render' ) );
        }


        public function render() {

            $validate = $this->access_validator( 'core / build', $this->data );
            
            foreach( $this->data as $model ) {

                $assets = new assets;
                $this->cores   = $model['cores'];
                $this->render  = $model['render'];
                $this->section = $model['section'];
                $this->extras  = $model['extras'];

                // prepare data
                $unique = sanitize_key( $model['cores']['unique'] );
                $hooks =  sanitize_key( $model['cores']['hook'] );

                if ( in_array( $unique, $validate ) && empty( $hooks ) === false ) {
                    add_action( $hooks, array(  $this, 'method' ) );
                }
                else if ( in_array( $unique, $validate ) ) { 
                    $this->method();
                }
            }
        }


        public function method() {
          
            $keysid = sanitize_key( $this->str_keys( $this->cores['unique'] ) ); 
            $unique = $this->str_slug( $this->cores['unique'] ); 
            $models = $this->render['model'] ; 
            $layout = $this->render['layout'] ; 
            $heading = array(
                'layout' => 'default',              // value : default | minimize
                'title' =>  $this->cores['label'],
                'icons' =>  $this->cores['icon'],
                'descs' =>  $this->cores['desc'],
            );
            $callbacks = $this->str_keys( 'layout_'. $this->render['layout'] );
            $attribute = $this->array_string( $this->extras['attribute'], 'html-attr' );  

            // layout
            if (  $models === 'form' ) 
            {
                echo '<form id="'. esc_attr( $unique ) .'" class="form '. esc_attr( $layout ).'-mode '. esc_attr( $this->str_slug( $unique ) ) .'"  '. $attribute .' >';
                    echo '<div class="form-header">';
                        $this->callback( 'form', 'main form header before', $keysid .'_main_form_header_before' );
                        new heading( $heading );
                        $this->callback( 'form', 'main form header after', $keysid  .'_main_form_header_after' );
                    echo '</div>';
                    echo '<div class="form-content">';
                        $this->callback( 'form', 'main form content before', $keysid .'_main_form_content_before' );
                        call_user_func( array( $this, $callbacks ),  ''  );
                        $this->callback( 'form', 'main form content before', $keysid .'_main_form_content_after' );
                    echo '</div>';
                echo '</form>';
            } 
            else if ( $models === 'block' ) 
            {
                echo '<div id="'. esc_attr( $unique ) .'" class="block '. esc_attr( $layout ).'-mode '. esc_attr( $this->str_slug( $unique ) ).'"  '. $attribute .'>';
                    echo '<div class="form-header">';
                        $this->callback( 'form', 'main form header before', $keysid .'_main_form_header_before' );
                        new heading( $heading );
                        $this->callback( 'form', 'main form header after', $keysid .'_main_form_header_after' );
                    echo '</div>';
                    echo '<div class="form-content">';
                        $this->callback( 'form', 'main form content before', $keysid .'_main_form_content_before' );
                        call_user_func( array( $this, $callbacks ),  ''  );
                        $this->callback( 'form', 'main form content after', $keysid .'_main_form_content_after' );
                    echo '</div>';
                echo '</div>';
            }
        } 
        

        /** LAYOUT */
        private function layout_full() {
            
            $keysid   = sanitize_key(( $this->str_keys( $this->cores['unique'] ) ) ); 
            $unique   = $this->str_slug( $this->cores['unique'] );

            foreach ( $this->section as $key => $section ) 
            {
                $heading = array(
                    'layout' => $section['model'],              // value : default | minimize
                    'title'  => $section['title'],
                    'icons'  => $section['icon'],
                    'descs'  => $section['desc'],
                );

                echo '<div class="section '. esc_attr( $this->str_slug( $section['title']) ) .'">';
                    
                    echo '<div class="heading">';

                        $this->callback( 'form', 'section '. esc_html( $section['title'] ) .' form heading before', $keysid .'_section_'.$this->str_keys( $section['title']).'_form_heading_before' );
                        new heading( $heading );
                        $this->callback( 'form', 'section '. esc_html( $section['title'] ) .' form heading after', $keysid .'_section_'.$this->str_keys( $section['title']).'_form_heading_after' );
                    
                    echo '</div>';

                    echo '<div class="content">';

                        $this->callback( 'form', 'section '. esc_html( $section['title'] ) .' form content before', $keysid .'_section_'. $this->str_keys( $section['title']) .'_form_content_before' );
    
                        echo '<div class="primary">';
                            call_user_func(array( $this, 'render_content_all' ), $section['content']);
                        echo '</div>';
    
                        $this->callback( 'form', 'section '. esc_html( $section['title'] ) .' form content after', $keysid .'_section_'. $this->str_keys( $section['title']) .'_form_content_after' );

                    echo '</div>';
                echo '</div>';
            }

            echo '<div class="action">';
                $this->render_actions_btn();
            echo '</div>';
        }


        private function layout_step() {

            $keysid   = sanitize_key(( $this->str_keys( $this->cores['unique'] ) ) ); 
            $unique   = $this->str_slug( $this->cores['unique'] );
            $sections = $this->section;
            $head_sum = 1;
            $post_sum = 1;
            $total    = count($sections) ;
            $active   = '';
         
            echo '<div class="step-layout">';

                // heading
                echo '<div class="heading">';

                    $this->callback( 'form', 'Step bar before', $keysid .'_step_bar_before' );
                    echo '<ul class="step navbar">';
                        foreach( $sections as $section ) 
                        {
                            $label  = $section['legend'];
                            $active = ( $head_sum === 1 ) ? 'active' : null;
                            echo '<li id="'. esc_attr( $unique ) .'-step-'. esc_attr( $head_sum ).'" class="step-item '. esc_attr( $active ) .'">';
                                echo '<a href="#" class="tooltip" data-tooltip="'. esc_attr( $label ).'">Step '. esc_html( $head_sum ) .'</a>';
                            echo '</li>';
                            $head_sum++;
                        }
                    echo '</ul>';
                    $this->callback( 'form', 'Step bar after', $keysid .'_step_bar_after' );

                echo '</div>';

                echo '<div class="content">';
                    foreach( $sections as $section ) 
                    {
                        $heading = array(
                            'layout' => $section['model'],              // value : default | minimize
                            'title'  => $section['title'],
                            'icons'  => $section['icon'],
                            'descs'  => $section['desc'],
                        );
                        $class    = $this->str_slug( $section['title'] );
                        $active   = ( $post_sum === 1 ) ? 'active' : null;
                        $callback =  'render_'.  $section['content']['type'];
                        

                        echo '<div id="'. esc_attr( $unique ) .'-tabs-'. esc_attr( $post_sum ).'" class="step-content '. esc_attr( $active ) .'">';
                            
                            echo '<div class="heading">';

                                $this->callback( 'form', 'Step section '.esc_html( $post_sum ).' form heading before', $keysid .'_step_'.$post_sum.'_form_heading_before' );
                                
                                new heading( $heading );
                                
                                $this->callback( 'form', 'Step section '. esc_html( $post_sum ) .' form heading after', $keysid .'_step_'.$post_sum.'_form_heading_after' );
                            
                            echo '</div>';

                            echo '<div class="content">';

                                $this->callback( 'form', 'Step section '. esc_html( $post_sum ) .' content before', $keysid .'_step_'.$post_sum.'_form_content_before' );
                                
                                echo '<div class="primary">';
                                    call_user_func(array( $this, 'render_content_all' ), $section['content']);
                                echo '</div>';
                                
                                $this->callback( 'form', 'Step section '. esc_html( $post_sum ) .' content after', $keysid .'_step_'.$post_sum.'_form_content_after' );
                            
                            echo '</div>';

        
                            echo '<div class="action">';
                                
                                // render navigation
                                $prev_tabs = $unique .'-tabs-'. ( $post_sum - 1 );
                                $next_step = $unique .'-step-'. ( $post_sum + 1 );
                                $next_tabs = $unique .'-tabs-'. ( $post_sum + 1 );
                                $prev_step = $unique .'-step-'. ( $post_sum - 1 );

                                if ( $post_sum === 1 ) 
                                {
                                    echo '<div class="btn btn-action step-action" data-step="'. esc_attr( $next_step ).'" data-tabs="'. esc_attr( $next_tabs ).'"><i class="ti-angle-right"></i></div>';
                                }
                                if ( $post_sum > 1 &&  $post_sum < $total  ) 
                                {
                                    echo '<div class="btn btn-action step-action" data-step="'. esc_attr( $prev_step ).'" data-tabs="'. esc_attr( $prev_tabs ).'"><i class="ti-angle-left"></i></div>';
                                    echo '<div class="btn btn-action step-action" data-step="'. esc_attr( $next_step ).'" data-tabs="'. esc_attr( $next_tabs ).'"><i class="ti-angle-right"></i></div>';
                                }
                                if ( $post_sum ==  $total  ) 
                                {
                                    echo '<div class="btn btn-action step-action" data-step="'. esc_attr( $prev_step ) .'" data-tabs="'. esc_attr( $prev_tabs ).'"><i class="ti-angle-left"></i></div>';
                                    $this->render_actions_btn();
                                }

                                // add step
                                $post_sum++;
                            echo '</div>';
                        echo '</div>';
                    }
                echo '</div>';
            echo '</div>';
        }


        private function layout_head_tab() {

            $unique   = $this->str_slug( $this->cores['unique'] );
            $sections = $this->section;
            
            echo '<div class="head-tabs '. esc_attr( $unique ).'-form">';
                echo '<div class="heading">';
                    call_user_func( array( $this, 'render_heading_tab' ), $sections, $unique );
                echo '</div>';
                echo '<div class="content">';
                    call_user_func( array( $this, 'render_section_tab' ), $sections, $unique );
                echo '</div>';
                echo '<div class="action">';
                    $this->render_actions_btn();
                echo '</div>';
            echo '</div>';
        }


        private function layout_left_tab() {
         
            $unique   = $this->str_slug( $this->cores['unique'] );
            $sections = $this->section;
         
            echo '<div class="left-tabs '. esc_attr( $unique ).'-form container">';
                echo '<div class="columns">';
                    echo '<div class="heading col-xs-4 col-4">';
                        call_user_func( array( $this, 'render_heading_tab' ), $sections, $unique );
                    echo '</div>';
                    echo '<div class="content col-xs-8 col-8">';
                        call_user_func( array( $this, 'render_section_tab' ), $sections , $unique );
                        echo '<div class="action">';
                            $this->render_actions_btn();
                         echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }


        private function layout_right_tab() {
            
            $unique   = $this->str_slug( $this->cores['unique'] );
            $sections = $this->section;
         
            echo '<div class="right-tabs '. esc_attr( $unique ).'-form container">';
                echo '<div class="columns">';
                    echo '<div class="content col-xs-8 col-8">';
                        call_user_func( array( $this, 'render_section_tab' ), $sections , $unique );
                        echo '<div class="action">';
                            $this->render_actions_btn();
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="heading col-xs-4 col-4">';
                        call_user_func( array( $this, 'render_heading_tab' ), $sections, $unique );
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }


        /** RENDER */
        private function render_heading_tab( $sections, $unique ) {

            $keysid   = sanitize_key(( $this->str_keys( $this->cores['unique'] ) ) ); 
            $head_sum = 1;
            $active   = '';


            $this->callback( 'form', 'section form header before', $keysid .'_section_form_header_before' );

            echo '<ul class="primary-tab-heading tab tab-block ">';
                foreach( $sections as $section ) 
                {
                    $label  = $section['legend'];
                    $active = ( $head_sum === 1 ) ? 'active' : null;
                    echo '<li id="'. esc_attr( $unique ) .'-tab-'. esc_attr( $head_sum ) .'" class="tab-item '. esc_attr( $active ) .'" data-parent="'. esc_attr( $unique ) .'-form" data-target="'. esc_attr( $unique ) .'-tabs-'. esc_attr( $head_sum ) .'">';
                        echo '<a href="#" class="tooltip" data-tooltip="'. esc_attr( $label ) .'">'. esc_html( $section['title'] ).'</a>';
                    echo '</li>';
                    $head_sum++;
                }
            echo '</ul>';

            $this->callback( 'form', 'section form header after', $keysid .'_section_form_header_after' );
        }


        private function render_section_tab( $sections, $unique ) {

            $keysid   = sanitize_key(( $this->str_keys( $this->cores['unique'] ) ) ); 
            $unique   = $this->str_slug( $this->cores['unique'] );
            $post_sum = 1;
           
            foreach( $sections as $section ) {

                $heading = [
                    'layout' => $section['model'],              // value : default | minimize
                    'title'  => $section['title'],
                    'icons'  => $section['icon'],
                    'descs'  => $section['desc'],
                ];

                $class  = $this->str_slug( $section['title'] );
                $active = ( $post_sum === 1 ) ? 'active' : null;
                $callback =  'render_'.  $section['content']['type'];
                
                echo '<div id="'. esc_attr( $unique ) .'-tabs-'. esc_attr( $post_sum ).'" class="tab-content section-'. esc_attr( $post_sum ).' '. esc_attr( $active ) .'">';
                    
                    echo '<div class="heading">';

                        $this->callback( 'form', 'section '. esc_html( $post_sum ) .' form heading before', $keysid .'_section_'.$post_sum.'_form_heading_before' );
                        new heading( $heading );
                        $this->callback( 'form', 'section '. esc_html( $post_sum ) .' form heading after', $keysid .'_section_'.$post_sum.'_form_heading_after' );
                    
                    echo '</div>';
                    echo '<div class="content">';
                        
                        $this->callback( 'form', 'section '.$post_sum.' form content before', $keysid .'_section_'.$post_sum.'_form_content_before' );
                        echo '<div class="primary">';
                            call_user_func(array( $this, 'render_content_all' ), $section['content']);
                        echo '</div>';
                        $this->callback( 'form', 'section '.$post_sum.' form content after', $keysid .'_section_'.$post_sum.'_form_content_after' );
                    
                    echo '</div>';

                    $post_sum++;
                echo '</div>';
            }
        }


        private function render_content_all( $args ) {

            $type = $args['type'];
            $data = $args['data'];

            if ( $type === 'field' ) {
                echo '<div class="form-group">';
                new fields($data);
                echo '</div>';
            }
            else if ( $type === 'hook' ) {
                $unique = $this->str_text( $this->cores['unique'] ); 
                $this->callback('form',   $unique .' form section', $data );
            }
            else if ( $type === 'table' ) {
                new table($data);
            }
        }


        private function render_actions_btn() {

            $buttons  = $this->extras['actions'];
           
            foreach (  $buttons  as $button ) {
                $unique = $button['unique'];
                $icons  = $button['icons'];
                $title  = $button['title'];
                $class  = $button['class'];
                $action = $button['action'];
                echo '<button id="'.esc_attr( $unique ) .'" class="btn '. esc_attr( $class ) .'"  name="'. esc_attr( $unique ) .'" data-action="'.  esc_attr( $action ) .'">'.esc_html( $title ).' <i class="'. esc_attr( $icons ) .'"></i></button>';
            }
        }
    }
}
