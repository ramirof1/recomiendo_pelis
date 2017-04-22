<?php

   /**
    * Class widget_imdb_connector_movie
    */
   class widget_imdb_connector_movie extends WP_Widget {
      /**
       * widget_imdb_connector_movie constructor.
       */
      public function __construct() {
         parent::__construct(
            "imdb_connector_movie",
            __("IMDb Connector movie", "imdb_connector"),
            array(
               "description" => __("Displays a specific movie and its details.", "imdb_connector")
            )
         );
      }

      /**
       * @param array $sidebar_info
       * @param array $options
       */
      public function widget($sidebar_info, $options) {
         $checkboxes = array(
            "show_movie_title",
            "show_poster",
            "show_plot"
         );

         $widget         = new widget_imdb_connector_movie();
         $merged_options = array();
         foreach($widget->widget_default_options() as $option => $default_value) {
            if(isset($options[$option]) && !empty($options[$option])) {
               $value = $options[$option];
            }
            else {
               $value = $default_value;
               if(in_array($option, $checkboxes, false)) {
                  $value = "off";
               }
            }
            $merged_options[$option] = $value;
         }
         $options = $merged_options;

         echo $sidebar_info["before_widget"];
         echo $sidebar_info["before_title"];
         echo $options["widget_title"];
         echo $sidebar_info["after_title"];

         $movie = imdb_connector_get_movie($options["movie_title"]);

         /** Start building the widget content */
         $movie_title = "";
         if($options["show_movie_title"] === "on") {
            $movie_title = '<div class="movie-title">' . $movie["title"] . '</div>';
            if($options["movie_title_position"] === "top") {
               echo $movie_title;
            }
         }
         if($options["show_poster"] === "on") {
            ?>
            <div class="movie-poster-container">
               <?php
                  if($options["poster_target"] !== "off") {
                     $target = $movie["poster"];
                     if($options["poster_target"] === "imdb_url") {
                        $target = "http://www.imdb.com/title/" . $movie["imdbid"] . "/";
                     }
                     elseif($options["poster_target"] === "custom") {
                        $target = $options["poster_link_custom_url"];
                     }
                     echo '<a href="' . $target . '" class="poster-link" target="_blank">';
                  }
               ?>
               <img src="<?php echo $movie["poster"]; ?>" alt="<?php echo $movie["title"]; ?>" class="movie-poster" width="<?php echo $options["poster_size_width"]; ?>" height="<?php echo $options["poster_size_height"]; ?>"/>
               <?php
                  if($options["poster_target"] !== "off") {
                     echo "</a>";
                  }
               ?>
            </div>
            <?php
         }
         if($options["show_movie_title"] === "on" && $options["movie_title_position"] === "below_poster") {
            echo $movie_title;
         }
         if($options["show_plot"] === "on") {
            ?>
            <div class="movie-plot">
               <p><?php echo $movie["plot"]; ?></p>
            </div>
            <?php
         }
         if($options["show_movie_title"] === "on" && $options["movie_title_position"] === "below_plot") {
            echo $movie_title;
         }
         if($options["bottom_text"]) {
            ?>
            <div class="bottom-text">
               <p><?php echo $options["bottom_text"]; ?></p>
            </div>
            <?php
         }
         if($options["show_movie_title"] === "on" && $options["movie_title_position"] === "bottom") {
            echo $movie_title;
         }
         echo $sidebar_info["after_widget"];
      }

      /**
       * Global function to define all setting fields and their defaults.
       *
       * @return array
       */
      private function widget_default_options() {
         $default_options = array(
            "widget_title"           => __("IMDb Connector movie", "imdb_connector"),
            "movie_title"            => "Apocalypse Now",
            "show_movie_title"       => "on",
            "movie_title_position"   => "top",
            "show_poster"            => "on",
            "poster_size_width"      => "200",
            "poster_size_height"     => "230",
            "poster_target"          => "cover",
            "poster_link_custom_url" => "",
            "show_plot"              => "on",
            "bottom_text"            => ""
         );

         return $default_options;
      }

      /**
       * @param array $new_options
       * @param array $old_options
       *
       * @return array
       */
      public function update($new_options, $old_options) {
         $widget         = new widget_imdb_connector_movie();
         $output_options = array();
         /** Save each option */
         foreach($widget->widget_default_options() as $option => $default_value) {
            $output_options[$option] = $new_options[$option];
         }

         return $output_options;
      }

      /**
       * @param array $instance
       *
       * @return string|void
       */
      public function form($instance) {
         $checkboxes = array(
            "show_movie_title",
            "show_poster",
            "show_plot"
         );
         $values     = array();
         $widget     = new widget_imdb_connector_movie();
         foreach($widget->widget_default_options() as $option => $default_value) {
            if(isset($instance[$option])) {
               $value = $instance[$option];
            }
            else {
               $value = $default_value;
               if(in_array($option, $checkboxes, false)) {
                  $value = "off";
               }
            }
            $values[$option] = $value;
         }
         /** Define widget admin fields */
         ?>
         <div class="imdb-connector-widget-admin">
            <p>
               <label for="<?php echo $this->get_field_id("widget-title"); ?>">
                  <?php _e("Widget title", "imdb_connector"); ?>:
               </label>
               <input type="text" id="<?php echo $this->get_field_id("widget-title"); ?>" name="<?php echo $this->get_field_name("widget_title"); ?>" value="<?php echo $values["widget_title"]; ?>" class="widefat" placeholder="<?php _e("Enter widget title...", "imdb_connector"); ?>"/>
            </p>

            <p>
               <label for="<?php echo $this->get_field_id("movie-title"); ?>">
                  <?php _e("Movie title or IMDb-ID", "imdb_connector"); ?>:
               </label>
               <input type="text" id="<?php echo $this->get_field_id("movie-title"); ?>" name="<?php echo $this->get_field_name("movie_title"); ?>" value="<?php echo $values["movie_title"]; ?>" class="widefat" placeholder="<?php _e("Enter movie title or ID...", "imdb_connector"); ?>"/>
            </p>

            <p class="show-movie-title">
               <input type="checkbox" name="<?php echo $this->get_field_name("show_movie_title"); ?>" id="<?php echo $this->get_field_id("show-movie-title"); ?>" value="on"<?php if($values["show_movie_title"] === "on") {
                  echo ' checked="checked"';
               } ?> />
               <label for="<?php echo $this->get_field_id("show-movie-title"); ?>"><?php _e("Show movie title", "imdb_connector"); ?></label>
            </p>

            <p class="movie-title-position">
               <label for="<?php echo $this->get_field_id("movie-title-position"); ?>">
                  <?php _e("Movie title position", "imdb_connector"); ?>:
               </label>
               <br/>
               <select name="<?php echo $this->get_field_name("movie_title_position"); ?>" id="<?php echo $this->get_field_id("movie-title-position"); ?>">
                  <option value="top"<?php if($values["movie_title_position"] === "top") {
                     echo ' selected="selected"';
                  } ?>>
                     <?php _e("Top", "imdb_connector"); ?>
                  </option>
                  <option value="below_poster"<?php if($values["movie_title_position"] === "below_poster") {
                     echo ' selected="selected"';
                  } ?>>
                     <?php _e("Below poster", "imdb_connector"); ?>
                  </option>
                  <option value="below_plot"<?php if($values["movie_title_position"] === "below_plot") {
                     echo ' selected="selected"';
                  } ?>>
                     <?php _e("Below plot", "imdb_connector"); ?>
                  </option>
                  <option value="bottom"<?php if($values["movie_title_position"] === "bottom") {
                     echo ' selected="selected"';
                  } ?>>
                     <?php _e("Bottom", "imdb_connector"); ?>
                  </option>
               </select>
            </p>
            <p class="show-poster">
               <input type="checkbox" name="<?php echo $this->get_field_name("show_poster"); ?>" class="show-poster-checkbox" id="<?php echo $this->get_field_id("show-poster"); ?>" value="on"<?php if($values["show_poster"] === "on") {
                  echo ' checked="checked"';
               } ?> />
               <label for="<?php echo $this->get_field_id("show-poster"); ?>"><?php _e("Show poster", "imdb_connector"); ?></label>
            </p>

            <div class="poster-options">
               <p>
                  <label for="<?php echo $this->get_field_id("poster-size-width"); ?>">
                     <?php _e("Poster width", "imdb_connector"); ?>:
                  </label>
                  <input type="text" size="3" maxlength="3" name="<?php echo $this->get_field_name("poster_size_width"); ?>" id="<?php echo $this->get_field_id("poster-size-width"); ?>" value="<?php echo $values["poster_size_width"]; ?>">
               </p>

               <p>
                  <label for="<?php echo $this->get_field_id("poster-size-height"); ?>">
                     <?php _e("Poster height", "imdb_connector"); ?>:
                  </label>
                  <input type="text" size="3" maxlength="3" name="<?php echo $this->get_field_name("poster_size_height"); ?>" id="<?php echo $this->get_field_id("poster-size-height"); ?>" value="<?php echo $values["poster_size_height"]; ?>">

               <p class="poster-target">
                  <label for="<?php echo $this->get_field_id("poster-target"); ?>"><?php _e("Poster link", "imdb_connector"); ?>:</label>
                  <br/>
                  <select name="<?php echo $this->get_field_name("poster_target"); ?>" id="<?php echo $this->get_field_id("poster-target"); ?>">
                     <option value="off"<?php if($values["poster_target"] === "off") {
                        echo " selected";
                     } ?>><?php _e("Off", "imdb_connector"); ?></option>
                     <option value="poster_url"<?php if($values["poster_target"] === "poster_url") {
                        echo " selected";
                     } ?>><?php _e("To cover file", "imdb_connector"); ?></option>
                     <option value="imdb_url"<?php if($values["poster_target"] === "imdb_url") {
                        echo " selected";
                     } ?>><?php _e("To movie on IMDb.com", "imdb_connector"); ?></option>
                     <option value="custom"<?php if($values["poster_target"] === "custom") {
                        echo " selected";
                     } ?>><?php _e("Custom", "imdb_connector"); ?></option>
                  </select>
               </p>
               <p class="poster-target-custom-url">
                  <?php
                     $custom_url = $values["poster_link_custom_url"];
                  ?>
                  <label for="<?php echo $this->get_field_id("poster-link-custom-url"); ?>">
                     <?php _e("Custom URL", "imdb_connector"); ?>:
                  </label>
                  <input type="url" placeholder="<?php _e("Enter custom URL...", "imdb_connector"); ?>" class="widefat" name="<?php echo $this->get_field_name("poster_link_custom_url"); ?>" id="<?php echo $this->get_field_id("poster-link-custom-url"); ?>" value="<?php echo $custom_url; ?>">
               </p>
            </div>
            <p>
               <input type="checkbox" name="<?php echo $this->get_field_name("show_plot"); ?>" id="<?php echo $this->get_field_id("show-plot"); ?>" value="on"<?php if($values["show_plot"] === "on") {
                  echo ' checked="checked"';
               } ?> />
               <label for="<?php echo $this->get_field_id("show-plot"); ?>"><?php _e("Show plot", "imdb_connector"); ?></label>
            </p>

            <p>
               <label for="<?php echo $this->get_field_id("bottom-text"); ?>"><?php _e("Bottom text", "imdb_connector"); ?>:</label>
               <textarea rows="3" name="<?php echo $this->get_field_name("bottom_text"); ?>" id="<?php echo $this->get_field_id("bottom-text"); ?>" class="widefat" placeholder="<?php _e("Enter text that will be displayed in the widget bottom...", ""); ?>"><?php echo $values["bottom_text"]; ?></textarea>
            </p>
         </div>
         <?php
      }
   }

   function init_widget_imdb_connector_movie() {
      register_widget("widget_imdb_connector_movie");
   }

   add_action("widgets_init", "init_widget_imdb_connector_movie");