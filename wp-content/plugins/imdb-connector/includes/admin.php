<?php
   /**
    * Kolja Nolte
    * kolja.nolte@gmail.com
    * http://www.koljanolte.com
    * Created on 2015-08-17 02:27 UTC+7
    */

   /** Prevents this file from being called directly */
   if(!function_exists("add_action")) {
      return;
   }

   /**
    * Builds the plugin's settings page.
    */
   function build_admin_settings_page() {
      $saved = false;

      /** Save settings */
      if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_settings_nonce"]) && wp_verify_nonce($_POST["save_settings_nonce"], "save_settings")) {
         foreach(imdb_connector_get_settings() as $setting => $value) {
            $field = str_replace("imdb_connector_", "", $setting);

            /** Skip setting that hasn't been set */
            if($field === "create_database_table" && !isset($_POST[$field])) {
               $_POST[$field] = "off";
            }
            elseif(!isset($_POST[$field])) {
               continue;
            }

            update_option($setting, $_POST[$field]);
         }
         $saved = true;
      }
      elseif(isset($_GET["action"], $_GET["nonce"]) && $_GET["action"] === "reset_settings" && wp_verify_nonce($_GET["nonce"], "reset_settings")) {
         foreach((array)imdb_connector_get_default_settings() as $setting => $default_value) {
            update_option($setting, $default_value);
         }
         ?>
         <div class="updated">
            <p><?php _e("All settings have been successfully resetted to default.", "imdb_connector"); ?></p>
         </div>
         <?php
      }
      ?>
      <div class="wrap" id="imdb-connector-settings">
         <h2>
            <i class="fa fa-film"></i>
            <?php echo "IMDb Connector &raquo; " . get_admin_page_title(); ?>
         </h2>
         <?php if($saved) { ?>
            <div class="updated settings-error">
               <p><?php _e("The settings have been successfully saved.", "imdb_connector"); ?></p>
            </div>
         <?php } ?>
         <form method="post" action="<?php echo admin_url(); ?>options-general.php?page=imdb-connector" id="settings-form">
            <input type="hidden" id="remote-actions-url" value="<?php echo imdb_connector_get_url(); ?>includes/remote-actions.php"/>
            <table class="form-table">
               <tbody>
                  <tr id="allow-caching-row">
                     <?php
                        $cache_path         = imdb_connector_get_cache_path();
                        $invalid_cache_path = "";
                        if(!is_dir($cache_path) && !mkdir($cache_path)) {
                           $invalid_cache_path = ' disabled="disabled"';
                        }
                     ?>
                     <th scope="row">
                        <label for="allow-caching-on">
                           <i class="fa fa-files-o"></i>
                           <?php _e("Caching", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <input type="radio" name="allow_caching" id="allow-caching-on" class="first" value="on"<?php imdb_connector_check_setting("allow_caching", "on");
                           echo $invalid_cache_path; ?> />
                        <label for="allow-caching-on">
                           <?php echo __("Movie details and posters", "imdb_connector"); ?>
                        </label>
                        <input type="radio" name="allow_caching" id="allow-caching-only-movie-details" value="on_no_poster"<?php imdb_connector_check_setting("allow_caching", "on_no_poster"); ?> />
                        <label for="allow-caching-only-movie-details">
                           <?php echo __("Only movie details", "imdb_connector"); ?>
                        </label>
                        <input type="radio" name="allow_caching" id="allow_caching_off" value="off"<?php imdb_connector_check_setting("allow_caching", "off");
                           echo $invalid_cache_path; ?> />
                        <label for="allow_caching_off"><?php _e("Off", "imdb_connector"); ?></label>
                        <p id="delete-cache-container">
                           <?php
                              $cached_movies = imdb_connector_get_cached_movies();
                              $value         = sprintf(__("Delete cached movies (%s)", "imdb_connector"), count($cached_movies));
                              wp_nonce_field("delete_cache", "delete_cache_nonce");
                           ?>

                           <input type="button" value="<?php echo $value; ?>" id="delete-cache" class="button" title="<?php _e("Delete all cache files generated by IMDb Connector", "imdb_connector"); ?>"/>

                           <img src="<?php echo imdb_connector_get_url(); ?>images/loading.gif" alt="<?php _e("Loading...", "imdb_connector"); ?>" id="delete-cache-loading-icon" hidden="hidden"/>
                           <span class="message success" hidden="hidden"><?php echo sprintf(__("%s files successfully deleted.", "imdb_connector"), '<span id="deleted-files-number">0</span>'); ?></span>
                        </p>

                        <p class="description"><?php _e("Allows IMDb Connector to cache movie details locally and covers for faster access (recommended).", "imdb_connector"); ?></p>
                        <?php if($invalid_cache_path) { ?>
                           <div id="invalid-directory">
                              <p class="message error">
                                 <?php
                                    _e("Caching has been disabled because the following directory does not exist<br />and could not be created:", "imdb_connector");
                                 ?>
                              </p>
                              <code><?php echo imdb_connector_get_cache_url(); ?></code>
                           </div>
                        <?php } ?>
                     </td>
                  </tr>
                  <tr>
                     <th>
                        <label for="cache-location-local">
                           <i class="fa fa-hdd-o"></i>
                           <?php _e("Cache location", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <input type="radio" name="cache_location" id="cache-location-local" class="first" value="local"<?php imdb_connector_check_setting("cache_location", "local"); ?> />
                        <label for="cache-location-local"><?php _e("As files on the server", "imdb_connector"); ?></label>
                        <input type="radio" name="cache_location" id="cache-location-database" value="database"<?php imdb_connector_check_setting("cache_location", "database"); ?> />
                        <label for="cache-location-database"><?php _e("In MySQL database (recommended)", "imdb_connector"); ?></label>

                        <p class="description">
                           <?php
                              echo sprintf(__('Defines where IMDb Connector stores the cached information. Movie posters are always stored in the plugin\'s <a href="%s" target="_blank">cache directory</a>.', "imdb_conector"), imdb_connector_get_cache_url());
                           ?>
                        </p>
                     </td>
                  </tr>
                  <tr id="row-database-table">
                     <th>
                        <label for="database-table">
                           <i class="fa fa-database"></i>
                           <?php _e("Database table", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <label for="database-table">
                           <?php
                              global $wpdb;
                              echo $wpdb->prefix;
                           ?>
                        </label>
                        <input type="text" id="database-table" name="database_table" value="<?php echo imdb_connector_get_setting("database_table"); ?>"/>
                        <input type="checkbox" name="create_database_table" id="create-database-table" <?php imdb_connector_check_setting("create_database_table", "on"); ?> />
                        <label for="create-database-table"><?php _e("Create if it doesn't exist", "imdb_connector"); ?></label>

                        <p class="description"><?php _e("The name of the MySQL table the movie details are being stored in.", "imdb_connector"); ?></p>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row">
                        <label for="plot-type">
                           <i class="fa fa-book"></i>
                           <?php _e("Plot type", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <select name="plot_type" id="plot-type">
                           <?php
                              $options = array(
                                 array(
                                    "label" => __("Short plot", "imdb_connector"),
                                    "value" => "plot_short"
                                 ),
                                 array(
                                    "label" => __("Full plot", "imdb_connector"),
                                    "value" => "plot_full"
                                 )
                              );

                              foreach($options as $option) {
                                 $selected = "";
                                 if(imdb_connector_get_setting("plot_type") == $option["value"]) {
                                    $selected = " selected";
                                 }
                                 echo '<option value="' . $option["value"] . '"' . $selected . '>' . $option["label"] . '</option>';
                              }
                           ?>
                        </select>

                        <p class="description">
                           <?php _e("Determines whether the <code>plot</code> variable should store the short or the full plot provided.", "imdb_connector"); ?>
                        </p>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row">
                        <label for="allow_shortcodes_on">
                           <i class="fa fa-code"></i>
                           <?php _e("Shortcodes", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <input type="radio" name="allow_shortcodes" id="allow_shortcodes_on" class="first" value="on"<?php imdb_connector_check_setting("allow_shortcodes", "on"); ?> />
                        <label for="allow_shortcodes_on"><?php _e("On", "imdb_connector"); ?></label>
                        <input type="radio" name="allow_shortcodes" id="allow_shortcodes_off" value="off"<?php imdb_connector_check_setting("allow_shortcodes", "off"); ?> />
                        <label for="allow_shortcodes_off"><?php _e("Off", "imdb_connector"); ?></label>

                        <p class="description">
                           <?php echo sprintf(__('Provides <a href="%s" target="_blank">shortcodes</a> to easily insert movie details into your posts or pages.<br />For a full list of available parameters, please see the <a href="http://www.koljanolte.com/wordpress/plugins/imdb-connector/#shortcodes" target="_blank">official documentation</a>.', "imdb_connector"), "http://codex.wordpress.org/Shortcode_API"); ?>
                        </p>

                        <div id="toggle-examples-buttons">
                           <button type="button" id="show-examples" class="button">
                              <i class="fa fa-caret-down"></i>
                              <?php _e("Show examples", "imdb_connector"); ?>
                           </button>

                           <button id="hide-examples" class="button">
                              <i class="fa fa-caret-up"></i>
                              <?php _e("Hide examples", "imdb_connector"); ?>
                           </button>
                        </div>
                        <ul id="shortcode-examples" hidden="hidden">
                           <?php
                              $counter    = 0;
                              $shortcodes = array(
                                 array(
                                    "shortcode"   => '[imdb_movie_detail title="The Shawshank Redemption" detail="year"]',
                                    "description" => __("Shows the release year of <em>The Shawshank Redemption</em>.", "imdb_connector")
                                 ),
                                 array(
                                    "shortcode"   => '[imdb_movie_detail title="tt0075314" detail="directors"]',
                                    "description" => __("Lists the directors of <em>Taxi Driver</em>, separated by commas.", "imdb_connector")
                                 ),
                                 array(
                                    "shortcode"   => '[imdb_movie_detail title="Pulp Fiction" detail="runtime-hours"]',
                                    "description" => __("Displays the runtime in hour of <em>Pulp Fiction</em>. You can also use <em>runtime-minutes</em>.", "imdb_connector")
                                 ),
                                 array(
                                    "shortcode"   => '[imdb_movie_detail title="Fight Club" detail="poster_image" width="320" height="470" link="http://www.google.com"]',
                                    "description" => __("Generates a poster (cover) with specified dimensions and linked to google.com.", "imdb_connector")
                                 )
                              );
                              foreach((array)$shortcodes as $shortcode) {
                                 $counter++;
                                 ?>
                                 <li class="example" id="example-<?php echo $counter; ?>">
                                    <p class="code">
                                       <code><?php echo $shortcode["shortcode"]; ?></code>
                                    </p>
                                    <p class="description"><?php echo $shortcode["description"]; ?></p>
                                 </li>
                                 <?php
                              }
                           ?>
                        </ul>
                  </tr>
                  <tr id="row-auto-delete">
                     <th>
                        <label for="auto-delete">
                           <i class="fa fa-trash"></i>
                           <?php _e("Auto delete", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <label for="auto-delete"><?php echo sprintf(__("Deletes cache every %s", "imdb_connector"), ""); ?></label>
                        <select name="auto_delete" id="auto-delete">
                           <?php
                              $options = array(
                                 array(
                                    "label" => __("never", "imdb_connector"),
                                    "value" => "off"
                                 ),
                                 array(
                                    "label" => "24 " . __("hours", "imdb_connector"),
                                    "value" => "24_hours"
                                 ),
                                 array(
                                    "label" => "7 " . __("days", "imdb_connector"),
                                    "value" => "3_days"
                                 ),
                                 array(
                                    "label" => "30 " . __("days", "imdb_connector"),
                                    "value" => "30_days"
                                 ),
                                 array(
                                    "label" => "6 " . __("months", "imdb_connector"),
                                    "value" => "6_months"
                                 )
                              );

                              foreach($options as $option) {
                                 $selected = "";
                                 if(imdb_connector_get_setting("auto_delete") == $option["value"]) {
                                    $selected = " selected";
                                 }
                                 echo '<option value="' . $option["value"] . '"' . $selected . '>' . $option["label"] . '</option>';
                              }
                           ?>
                        </select>

                        <p class="description"><?php _e("Automatically deletes all cached files (database and files) to keep information up to date.", "imdb_connector"); ?></p>
                     </td>
                  </tr>
                  <tr id="row-deactivation-actions">
                     <th>
                        <label for="deactivation-actions">
                           <i class="fa fa-power-off"></i>
                           <?php _e("Deactivation actions", "imdb_connector"); ?>
                        </label>
                     </th>
                     <td>
                        <?php
                           $checkboxes = array(
                              array(
                                 "label" => __("Delete MySQL cache", "imdb_connector"),
                                 "value" => "database"
                              ),
                              array(
                                 "label" => __("Delete cached movie detail files", "imdb_connector"),
                                 "value" => "movie_details"
                              ),
                              array(
                                 "label" => __("Delete cached poster files", "imdb_connector"),
                                 "value" => "posters"
                              ),
                              array(
                                 "label" => __("Delete plugin settings", "imdb_connector"),
                                 "value" => "settings"
                              )
                           );
                           foreach($checkboxes as $checkbox) {
                              $checked = "";
                              if(in_array($checkbox["value"], imdb_connector_get_setting("deactivation_actions"), false)) {
                                 $checked = ' checked="checked"';
                              }
                              echo '<p><input type="checkbox" name="deactivation_actions[]" id="deactivation-action-' . $checkbox["value"] . '" value="' . $checkbox["value"] . '" ' . $checked . ' /><label for="deactivation-action-' . $checkbox["value"] . '">' . $checkbox["label"] . '<label></p>';
                           }
                        ?>
                        <p class="description"><?php _e("Actions being executed when you deactivate IMDb Connector.", "imdb_connector"); ?></p>
                     </td>
                  </tr>
               </tbody>
            </table>
            <div class="submit-area">
               <?php wp_nonce_field("save_settings", "save_settings_nonce"); ?>

               <input type="hidden" name="saved" value="true"/>

               <button type="submit" class="button-primary">
                  <i class="fa fa-floppy-o"></i>
                  <?php _e("Save Changes", "imdb_connector"); ?>
               </button>

               <a href="<?php echo wp_nonce_url(get_admin_url() . "options-general.php?page=imdb-connector&action=reset_settings", "reset_settings", "nonce"); ?>" class="button" id="reset-button">
                  <i class="fa fa-trash"></i>
                  <?php _e("Reset to Default Settings", "imdb_connector"); ?>
               </a>
            </div>
            <p>
               <small>
                  <i class="fa fa-bug"></i>
                  <?php _e('Found an error? Help making IMDb Connector better by <a href="http://www.wordpress.org/support/plugin/imdb-connector#postform" target="_blank">quickly reporting the bug</a>.', "imdb_connector"); ?></small>
            </p>
         </form>
         <span id="reset-settings-label" hidden><?php _e("Do you really want to reset all settings wit the default values?", "imdb_connector"); ?></span>
      </div>
      <?php
   }