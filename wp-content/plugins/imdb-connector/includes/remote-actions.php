<?php
   /**
    * Kolja Nolte
    * kolja.nolte@gmail.com
    * http://www.koljanolte.com
    * Created on 2015-08-17 02:27 UTC+7
    */

   /**
    * This file handles all the requests by jQuery/AJAX.
    *
    * @since 0.2
    */

   /** Stop script if file is called directly or with no valid request */
   if(!isset($_GET["nonce"], $_GET["action"])) {
      return false;
   }

   /** Include WordPress */
   $path = dirname(__FILE__) . "/../../../../wp-load.php";
   if(!file_exists($path)) {
      return;
   }

   require($path);

   /** "Delete cache" function */
   if($_GET["action"] === "delete_cache" && isset($_GET["nonce"]) && wp_verify_nonce($_GET["nonce"], "delete_cache")) {
      $deleted_files = count(imdb_connector_get_cached_movies());
      $class         = new IMDb_Connector_Cache();

      $class->delete_cache();

      echo $deleted_files;
   }