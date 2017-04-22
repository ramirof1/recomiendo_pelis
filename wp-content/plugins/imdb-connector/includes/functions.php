<?php
   /**
    * Kolja Nolte
    * kolja.nolte@gmail.com
    * http://www.koljanolte.com
    * Created on 2015-08-17 02:18 UTC+7
    */

   /** Prevents this file from being called directly */
   if(!function_exists("add_action")) {
      return;
   }

   /**
    * Returns the URL to the plugin directory.
    *
    * @since 0.2
    *
    * @return string
    */
   function imdb_connector_get_url() {
      return (string)plugin_dir_url(dirname(__FILE__));
   }

   /**
    * Returns the absolute path to the plugin directory.
    *
    * @return string
    *
    * @since 0.2
    */
   function imdb_connector_get_path() {
      return (string)plugin_dir_path(dirname(__FILE__));
   }

   /**
    * Returns the absolute path to the plugin's cache directory.
    *
    * @since 0.2
    *
    * @return string
    */
   function imdb_connector_get_cache_path() {
      $class = new IMDb_Connector_Cache();

      return (string)$class->get_cache_path();
   }

   /**
    * Returns the URL of the plugin's cache directory.
    *
    * @since 0.2
    *
    * @return string
    */
   function imdb_connector_get_cache_url() {
      $class = new IMDb_Connector_Cache();

      return (string)$class->get_cache_url();
   }

   /**
    * Converts a URL into an absolute path.
    *
    * @param $url
    *
    * @since 0.3
    *
    * @return string
    */
   function imdb_connector_url_to_path($url) {
      $path = "";
      if(strstr($url, home_url())) {
         $path = str_replace(home_url(), ABSPATH, $url);
      }

      return (string)$path;
   }

   /**
    * Converts an absolute path into an URL.
    *
    * @param $path
    *
    * @since 0.3
    *
    * @return string
    */
   function imdb_connector_path_to_url($path) {
      $url     = home_url() . "/";
      $abspath = ABSPATH;
      $url     = str_replace($abspath, $url, $path);

      return (string)$url;
   }

   /**
    * Merges options with default options.
    *
    * @param array $user_options
    * @param array $default_options
    * @param bool  $allow_empty
    *
    * @since 0.2
    *
    * @return array
    */
   function imdb_connector_merge_options(array $user_options = array(), array $default_options = array(), $allow_empty = true) {
      $merged_options = array();

      foreach($default_options as $default_option_name => $default_option_value) {
         /** Check if the user has set the current option  */
         if(isset($user_options[$default_option_name])) {
            $user_option_value = $user_options[$default_option_name];
            /** Use default option value if $allow_empty is off */
            $merged_option_value = $user_option_value;
            if(!$allow_empty && !$user_option_value) {
               $merged_option_value = $default_option_value;
            }
         }
         /** Use default option value if user option is not set  */
         else {
            if(!isset($default_option_value)) {
               $default_option_value = "";
            }
            $merged_option_value = $default_option_value;
         }
         /** Add current option and its value to the output array */
         $merged_options[$default_option_name] = $merged_option_value;
      }

      return (array)$merged_options;
   }

   /**
    * Returns given attributes as HTML string.
    *
    * @param array $attributes
    * @param bool  $allow_empty
    *
    * @since 0.3
    *
    * @return string
    */
   function imdb_connector_get_html_attributes(array &$attributes, $allow_empty = false) {
      $html_attributes = "";

      foreach((array)$attributes as $attribute => $value) {
         if(!$allow_empty && !$value) {
            continue;
         }
         $html_attributes .= " " . $attribute . '="' . $value . '"';
      }

      return (string)$html_attributes;
   }

   /**
    * Sanitizes the movie details.
    *
    * @since 0.3
    *
    * @param $movie_details
    *
    * @return array
    */
   function imdb_connector_sanitize_movie_details($movie_details) {
      $sanitized_movie_details = array();
      $is_object               = false;

      /** Convert JSON to array */
      if(!is_array($movie_details)) {
         $is_object     = true;
         $movie_details = json_decode($movie_details, true);
      }

      foreach($movie_details as $movie_detail => $value) {
         /** Convert detail identifiers to lowercase */
         $movie_detail = trim(strtolower($movie_detail));

         /** Rename fields that contain more than one value */
         if($movie_detail === "genre") {
            $movie_detail = "genres";
         }
         elseif($movie_detail === "director") {
            $movie_detail = "directors";
         }
         elseif($movie_detail === "country") {
            $movie_detail = "countries";
         }
         elseif($movie_detail === "writer") {
            $movie_detail = "writers";
         }
         elseif($movie_detail === "language") {
            $movie_detail = "languages";
         }
         /** Escape "dangerous" characters */
         /** Convert keys with multiple values into an array */
         $to_array = array(
            "genres",
            "directors",
            "countries",
            "writers",
            "actors",
            "languages"
         );
         /** Split multiple values into arrays */
         if(in_array($movie_detail, $to_array, false)) {
            $value = explode(", ", trim($value));
         }

         /** Format release date */
         if($movie_detail === "released" && $value !== "N/A" && phpversion() > 5.2) {
            $value = new DateTime($value);
            $value = $value->format("Y-m-d");
         }

         /** Create runtime */
         if($movie_detail === "runtime") {
            $minutes   = preg_replace("'[^0-9]'", "", $value);
            $timestamp = 0;
            if($value !== "N/A") {
               $timestamp = mktime(0, $minutes);
            }
            $value = array(
               "timestamp" => $timestamp,
               "minutes"   => $minutes,
               "hours"     => date("G:i", $timestamp)
            );
         }
         /** Remove everything but numbers from imdbvotes */
         if($movie_detail === "imdbvotes") {
            $value = preg_replace("'[^0-9]'", "", $value);
         }
         $sanitized_movie_details[$movie_detail] = $value;
      }

      $movie_details = $sanitized_movie_details;

      /** Convert array back to JSON */
      if($is_object) {
         $movie_details = json_encode($movie_details);
      }

      return (array)$movie_details;
   }

   /**
    * Retrieves all movies cached by IMDb Connector.
    *
    * @param string $cache_location
    * @param string $type
    *
    * @since 0.4
    *
    * @return array
    */
   function imdb_connector_get_cached_movies($cache_location = "all", $type = "array") {
      $class = new IMDb_Connector_Movies();

      return (array)$class->get_cached_movies($cache_location, $type);
   }

   /**
    * Fixes a bug in JSON caused by faulty formatting on omdbapi.com.
    *
    * @param $json
    *
    * @since 1.3.1
    *
    * @return mixed
    */
   function imdb_connector_fix_json_bug($json) {
      return str_replace('"movie,', '"movie",', $json);
   }

   /**
    * @param           $api_url
    * @param bool|true $decode
    *
    * @param string    $type
    *
    * @since 1.3.1
    *
    * @return array|bool
    */
   function imdb_connector_process_json($api_url, $decode = true, $type = "array") {
      $data = wp_remote_get($api_url);
      if(is_wp_error($data)) {
         return false;
      }

      $array = true;
      if($type !== "array") {
         $array = false;
      }

      $data = $data["body"];
      $data = str_replace('"movie,', '"movie",', $data);

      if($decode) {
         $data = json_decode($data, $array);
         $data = imdb_connector_sanitize_movie_details($data);
      }

      return $data;
   }

   /**
    * Sanitizes the given title for the API URL.
    *
    * @param $title
    *
    * @since 0.2
    *
    * @return string
    */
   function imdb_connector_sanitize_url_title($title) {
      /** Sanitizes wptexturized() characters */
      if(strstr($title, "%26%238217%3B")) {
         $title = urlencode($title);
         $title = str_replace("%26%238217%3B", "'", $title);
         $title = urldecode($title);
      }
      /** Transform characters to URL characters */
      $title = rawurlencode($title);

      return (string)$title;
   }

   /**
    * Returns a movie including all details provided by
    * the unofficial API at omdbapi.com.
    *
    * @param       $id_or_title
    * @param array $options
    *
    * @since 0.1
    *
    * @return array
    */
   function imdb_connector_get_movie($id_or_title, array $options = array()) {
      $class = new IMDb_Connector_Movies();

      return $class->get_movie($id_or_title, $options);
   }

   /**
    * Searches for movies that contain the set title or ID.
    *
    * @param $id_or_title
    *
    * @since 0.2
    *
    * @return array
    */
   function imdb_connector_search_movie($id_or_title) {
      $class = new IMDb_Connector_Movies();

      return $class->search_movie($id_or_title);
   }

   /**
    * Searches for movies that contain the set titles or IDs.
    *
    * @param array $ids_or_titles
    *
    * @since    0.2
    *
    * @return array
    */
   function imdb_connector_search_movies(array $ids_or_titles) {
      $class = new IMDb_Connector_Movies();

      return $class->search_movies($ids_or_titles);
   }

   /**
    * Returns if the set query returns valid movie details.
    *
    * @param $id_or_title
    *
    * @since 0.1
    *
    * @return bool
    */
   function imdb_connector_has_movie($id_or_title) {
      if(!imdb_connector_get_movie($id_or_title)) {
         return false;
      }

      return (boolean)true;
   }

   /**
    * @param array $titles_or_ids
    *
    * @since 0.2
    *
    * @return array|bool
    */
   function imdb_connector_get_movies(array $titles_or_ids) {
      $class = new IMDb_Connector_Movies();

      return $class->get_movies($titles_or_ids);
   }

   /**
    * Returns - if available - a certain movie detail.
    *
    * @param        $id_or_title
    * @param string $detail
    *
    * @since 0.1
    *
    * @return string
    */
   function imdb_connector_get_movie_detail($id_or_title, $detail) {
      $class = new IMDb_Connector_Movies();

      return (string)$class->get_movie_detail($id_or_title, $detail);
   }

   /**
    * @param $attributes
    *
    * @return string
    */
   function imdb_connector_shortcode_movie_detail($attributes) {
      if(!isset($attributes["title"], $attributes["detail"])) {
         return "";
      }

      $attribute_title  = $attributes["title"];
      $attribute_detail = $attributes["detail"];

      $movie_details = imdb_connector_get_movie($attribute_title);
      if(!$movie_details) {
         return "";
      }

      $output = "";

      /** Return poster as HTML image */
      if($attribute_detail === "poster_image") {
         $img_default_attributes = array(
            "src"    => $movie_details["poster"],
            "width"  => 0,
            "height" => 0,
            "alt"    => "",
            "class"  => ""
         );

         $img_attributes = imdb_connector_merge_options($attributes, $img_default_attributes);
         $img_attributes = imdb_connector_get_html_attributes($img_attributes);
         $img            = "<img $img_attributes />";

         $output = $img;

         if(isset($attributes["href"]) || (isset($attributes["linked"]) && $attributes["linked"] === "true")) {
            $a_default_attributes = array(
               "href"   => "http://www.imdb.com/title/" . $movie_details["imdbid"] . "/",
               "target" => "_blank"
            );

            $a_attributes = imdb_connector_merge_options($attributes, $a_default_attributes, false);
            $a_attributes = imdb_connector_get_html_attributes($a_attributes);
            $output       = "<a $a_attributes>$output</a>";
         }
      }
      /** Return poster URL */
      elseif($attribute_detail === "poster_url") {
         $output = $movie_details["poster"];
      }
      /** Return runtime */
      elseif(strstr($attribute_detail, "runtime")) {
         if($attribute_detail === "runtime") {
            $output = $movie_details["runtime"]["hours"];
            if(isset($attributes["format"])) {
               $output = date($attributes["format"], $movie_details["runtime"]["timestamp"]);
            }
         }
         elseif($attribute_detail === "runtime-minutes") {
            $output = $movie_details["runtime"]["minutes"];
         }
      }
      /** Return defined movie detail (if exists) */
      elseif(array_key_exists($attribute_detail, $movie_details)) {
         $output = $movie_details[$attribute_detail];
         if(is_array($output)) {
            $output = implode(", ", $output);
         }
      }

      return (string)$output;
   }