<?php

// Load the stylesheet of the parent theme
   add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
   function enqueue_parent_styles() {
      wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   }



// Dark toggle widget
   if (isset($_COOKIE['darkMode'])) {
      if ($_COOKIE['darkMode'] == "enabled") {
         $darkMode = "enabled";
      } else {
         $darkMode = "disabled";
      }
   }

   class dark_toggle_widget extends WP_Widget {
   
      function __construct() {
         parent::__construct(
         
         // Base ID of your widget
         'dark_toggle_widget', 
         
         // Widget name will appear in UI
         __('Dark Toggle', 'dark_toggle_widget'), 
         
         // Widget description
         array( 'description' => __( 'Widget for toggling between dark and light theme.', 'dark_toggle_widget' ), ) 
         );
      }
      
      // Front-end
      public function widget( $args, $instance ) {
         $title = apply_filters( 'widget_title', $instance['title'] );
         
         // before and after widget arguments are defined by themes
         echo $args['before_widget'];
         if ( ! empty( $title ) )
         echo $args['before_title'] . $title . $args['after_title'];
         
         // This is where you run the code and display the output
         // echo __( 'Hello, World!', 'dark_toggle_widget' );
         ?>
            <p id="dark-toggle">
               <label class="dark-switch">
                  <input type="checkbox" id="dark-checkbox" <?php if ($GLOBALS['darkMode'] != "disabled") { echo 'checked'; } ?> onclick="darkToggle()">
                  <span class="dark-slider round"></span>
               </label>
               <style>
                  #dark-toggle {
                     line-height: 20px;
                  }

                  .dark-switch {
                     position: relative;
                     display: inline-block;
                     width: 60px;
                     height: 34px;

                     width: 46px;
                     height: 20px;
                     margin-right: 11px;
                  }

                  .dark-switch input { 
                     opacity: 0;
                     width: 0;
                     height: 0;
                  }

                  .dark-slider {
                     position: absolute;
                     cursor: pointer;
                     top: 0;
                     left: 0;
                     right: 0;
                     bottom: 0;
                     background-color: #ccc;
                     -webkit-transition: .4s;
                     transition: .4s;
                  }

                  .dark-slider:before {
                     position: absolute;
                     content: "";
                     height: 26px;
                     width: 26px;
                     left: 4px;
                     bottom: 4px;
                     background-color: white;
                     -webkit-transition: .4s;
                     transition: .4s;

                     height: 10px;
                     width: 10px;
                     left: 5px;
                     bottom: 5px;
                  }

                  input:checked + .dark-slider {
                     background-color: #1fa7e4;
                  }

                  input:focus + .dark-slider {
                     box-shadow: 0 0 1px #2196F3;
                  }

                  input:checked + .dark-slider:before {
                     -webkit-transform: translateX(26px);
                     -ms-transform: translateX(26px);
                     transform: translateX(26px);
                  }

                  /* Rounded dark-sliders */
                  .dark-slider.round {
                     border-radius: 34px;

                     border-radius: 17px;
                  }

                  .dark-slider.round:before {
                     border-radius: 50%;
                  }
               </style>
               <script src="<?php echo get_theme_file_uri( 'js/jquery.cookie.js' );?>"></script>
               <script>
               <?php
                  if ($GLOBALS['darkMode'] == "disabled") {
                     echo '
                        $("#twentytwelve-style-css").attr( "disabled", "disabled" );
                     ';
                  }
               ?>
                  function darkToggle() {
                     var checkBox = document.getElementById("dark-checkbox");
                     if (checkBox.checked == true){
                        // Dark mode enabled
                        $("#twentytwelve-style-css").removeAttr( "disabled");
                        // Set cookie
                        if (jQuery.cookie('darkMode') ) { jQuery.cookie( 'darkMode', null) }
                        jQuery.cookie('darkMode', 'enabled', { expires: 30 });
                     } else {
                        // Dark mode disabled
                        $("#twentytwelve-style-css").attr( "disabled", "disabled" );
                        // Set cookie
                        if (jQuery.cookie('darkMode') ) { jQuery.cookie( 'darkMode', null) }
                        jQuery.cookie('darkMode', 'disabled', { expires: 30 });
                     }
                  }
               </script>
               <span>Dark mode</span>
               
            </p>
         <?php
         echo $args['after_widget'];
      }

      // Backend 
      public function form( $instance ) {
         if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
         }
         else {
            $title = __( 'Dark Mode', 'dark_toggle_widget' );
         }
         // Widget admin form
         ?>
         <p>
         <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
         <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
         </p>
         <?php 
      }
            
      // Updating widget replacing old instances with new
      public function update( $new_instance, $old_instance ) {
         $instance = array();
         $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
         return $instance;
      }
      
   } // Class ends here
      
      
   // Register and load the widget
   function load_dark_toggle_widget() {
      register_widget( 'dark_toggle_widget' );
   }
   add_action( 'widgets_init', 'load_dark_toggle_widget' );