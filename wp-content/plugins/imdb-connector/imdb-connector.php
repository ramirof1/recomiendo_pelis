<?php
   /**
    * Plugin name:  IMDb Connector
    * Plugin URI:   http://www.koljanolte.com/wordpress/plugins/imdb-connector/
    * Description:  A simple plugin that allows you to easily retrieve movie details from IMDb.com.
    * Version:      1.5.0
    * Author:       Kolja Nolte
    * Author URI:   http://www.koljanolte.com
    * License:      GPLv2 or later
    * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
    * Text Domain:  imdb_connector
    * Domain Path:  /languages
    */

   /** Prevents this file from being called directly */
   if(!function_exists("add_action")) {
      return;
   }

   /** Include plugin files */
   $include_directories = array(
      "classes",
      "includes",
      "widgets"
   );

   $ignore_files = array(
      "remote-actions.php"
   );

   /** Loop through the set directories */
   foreach((array)$include_directories as $include_directory) {
      $include_directory = plugin_dir_path(__FILE__) . $include_directory;
      $include_directory = realpath($include_directory);

      /** Skip directory if it's not a valid directory */
      if(!is_dir($include_directory)) {
         continue;
      }

      /** Gather all .php files within the current directory */
      $include_files = glob($include_directory . "/*.php");
      foreach((array)$include_files as $include_file) {
         /** Skip file if file is not valid */
         if(!is_file($include_file) || in_array(basename($include_file), $ignore_files, false)) {
            continue;
         }

         /** Include current file */
         include_once($include_file);
      }
   }

   /** Execute function when activating plugin */
   register_activation_hook(__FILE__, "imdb_connector_install");

   /** Execute function when deactivating plugin */
   register_deactivation_hook(__FILE__, "imdb_connector_uninstall");