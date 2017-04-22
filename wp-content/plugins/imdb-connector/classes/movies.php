<?php
   /**
    * Kolja Nolte
    * kolja.nolte@gmail.com
    * http://www.koljanolte.com
    * Created on 2015-10-23 00:58 UTC+7
    */

   /** Prevents this file from being called directly */
   if(!function_exists("add_action")) {
      return;
   }

   /**
    * Class IMDb_Connector_Movies
    */
   class IMDb_Connector_Movies {
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
      public function get_movie($id_or_title, array $options = array()) {
         /** Define default function options */
         $default_options = array(
            "format"         => "array",
            "allow_caching"  => imdb_connector_get_setting("allow_caching"),
            "cache_location" => imdb_connector_get_setting("cache_location")
         );
         /** Use default option value if option is not set */
         foreach($default_options as $option_name => $default_value) {
            if(!array_key_exists($option_name, $options) || !$options[$option_name]) {
               $options[$option_name] = $default_value;
            }
         }

         /** Define variables */
         $api_url = "http://www.omdbapi.com/?";
         $type    = "t";

         /** Check whether $id_or_title is an IMDb ID */
         if(substr($id_or_title, 0, 2) === "tt") {
            $type = "i";
         }

         /** Sanitize $id_or_title to be URL friendly */
         $id_or_title_url = imdb_connector_sanitize_url_title($id_or_title);

         /** Build request API URL */
         $api_url .= $type . "=" . $id_or_title_url;

         /** Determine whether to use the short or the full plot */
         if(imdb_connector_get_setting("plot_type") === "plot_full") {
            $api_url .= "&plot=full";
         }

         $movie_details = (array)array();
         $found         = true;

         /** When caching feature has been activated */
         if(imdb_connector_get_setting("allow_caching") !== "off") {
            $cache_class = new IMDb_Connector_Cache();

            $file_name            = substr(md5($id_or_title), 0, 8);
            $cache_directory_path = $cache_class->get_cache_path();
            $cache_directory_url  = $cache_class->get_cache_url();
            $cache_file_path      = $cache_directory_path . "/" . $file_name . ".tmp";

            if($options["cache_location"] === "local") {
               /** Display error message if the directory doesn't exist and can't be created automatically */
               if(!@mkdir($cache_directory_path) && !is_dir($cache_directory_path)) {
                  return false;
               }
               /** Display error message if the directory exists but isn't writable */
               elseif(!is_writable($cache_directory_path)) {
                  return false;
               }
               /** Get details from cached file if it exists */
               if(file_exists($cache_file_path)) {
                  $handle        = fopen($cache_file_path, "r");
                  $data          = fread($handle, 999);
                  $movie_details = json_decode($data, true);
               }
               /** Get movie details online and create cache file */
               else {
                  $handle = fopen($cache_file_path, "a");
                  $data   = wp_remote_get($api_url);
                  if(is_wp_error($data)) {
                     return false;
                  }

                  $data          = $data["body"];
                  $data          = imdb_connector_fix_json_bug($data);
                  $movie_details = json_decode($data, true);
                  $movie_details = imdb_connector_sanitize_movie_details($movie_details);

                  fwrite($handle, json_encode($movie_details));
                  fclose($handle);
               }
            }
            elseif($options["cache_location"] === "database") {
               global $wpdb;
               $table = $wpdb->prefix . imdb_connector_get_setting("database_table");
               imdb_connector_install(false, false);
               $query = "SELECT * FROM $table ";
               if($type === "i") {
                  $query .= "WHERE imdbid = '" . $id_or_title . "'";
               }
               else {
                  $query .= "WHERE title = '$id_or_title'";
               }
               $movie_details = (array)$wpdb->get_row($query, "ARRAY_A");
               /** Read row and convert serialized strings back to array */
               if($movie_details) {
                  foreach($movie_details as $movie_detail => $value) {
                     if(is_serialized($value)) {
                        $movie_details[$movie_detail] = unserialize($value);
                     }
                  }
               }
               /** Movie doesn't exist in the database, so we add it */
               elseif(imdb_connector_get_setting("create_database_table") === "on") {
                  $movie_details = (array)imdb_connector_process_json($api_url);

                  if(!is_array($movie_details) || !isset($movie_details["title"])) {
                     return false;
                  }

                  $formats = array();
                  $data    = array(
                     "title"      => $movie_details["title"],
                     "imdbid"     => $movie_details["imdbid"],
                     "year"       => $movie_details["year"],
                     "released"   => $movie_details["released"],
                     "runtime"    => serialize($movie_details["runtime"]),
                     "genres"     => serialize($movie_details["genres"]),
                     "writers"    => serialize($movie_details["writers"]),
                     "directors"  => serialize($movie_details["directors"]),
                     "actors"     => serialize($movie_details["actors"]),
                     "languages"  => serialize($movie_details["languages"]),
                     "countries"  => serialize($movie_details["countries"]),
                     "rated"      => $movie_details["rated"],
                     "poster"     => $movie_details["poster"],
                     "awards"     => $movie_details["awards"],
                     "plot"       => $movie_details["plot"],
                     "metascore"  => $movie_details["metascore"],
                     "imdbrating" => $movie_details["imdbrating"],
                     "imdbvotes"  => $movie_details["imdbvotes"],
                     "type"       => $movie_details["type"]
                  );

                  foreach((array)$data as $key => $value) {
                     $format = "%s";
                     if(is_int($value)) {
                        $format = "%d";
                     }
                     elseif(is_float($value)) {
                        $format = "%f";
                     }
                     $formats[] = $format;
                  }

                  $wpdb->insert($table, $data, $formats);
               }
            }
            /** Create movie poster if it doesn't exist yet */
            $poster_path = $cache_directory_path . "/" . $file_name . ".jpg";
            if(array_key_exists("title", $movie_details) && imdb_connector_get_setting("allow_caching") !== "on_no_poster") {
               $movie_details = (array)$movie_details;
               if($movie_details["poster"] !== "N/A" && !file_exists($poster_path)) {
                  if(!is_dir(dirname($poster_path))) {
                     wp_mkdir_p(dirname($poster_path));
                  }
                  $handle = fopen($poster_path, "a");
                  fwrite($handle, file_get_contents($movie_details["poster"]));
                  fclose($handle);
               }
               /** Change poster URL to cache file */
               $movie_details["poster"] = $cache_directory_url . "/" . $file_name . ".jpg";
            }
         }
         /** Get online movie details if cache is deactivated */
         else {
            /** Fetch JSON data */
            $data = wp_remote_get($api_url);

            /** Stop if downloading JSON fails */
            if(is_wp_error($data)) {
               return false;
            }

            /** Specify JSON data and turn it into a proper movie details array */
            $data          = $data["body"];
            $data          = imdb_connector_fix_json_bug($data);
            $movie_details = json_decode($data, true);
            $movie_details = imdb_connector_sanitize_movie_details($movie_details);

            /** Quick check if the array contains the necessary keys */
            if(!array_key_exists("title", $movie_details)) {
               $found = false;
            }
         }

         /** Display error message in case the movie could not be found */
         if(!$found) {
            return false;
         }

         $movie_details = apply_filters("imdb_connector_movie_details", $movie_details);

         /** Convert movie details into object if set */
         if($options["format"] === "object") {
            $movie_details = json_decode(json_encode($movie_details));
         }

         return $movie_details;
      }

      /**
       * Returns - if available - a certain movie detail.
       *
       * @param        $id_or_title
       * @param string $detail
       *
       * @since 0.1
       *
       * @return string|array
       */
      public function get_movie_detail($id_or_title, $detail) {
         $movie = imdb_connector_get_movie($id_or_title);
         if(!$movie) {
            return "";
         }

         $deprecated = array(
            "genre",
            "country",
            "language",
            "director",
            "writer"
         );

         if(in_array($detail, $deprecated, false)) {
            $new_detail = $detail . "s";
            if($detail === "country") {
               $new_detail = "countries";
            }
            _deprecated_argument("get_imdb_connector_movie_detail", "0.4", "Use <strong>$new_detail</strong> instead.");
            $detail = $new_detail;
         }
         elseif(!array_key_exists($detail, $movie)) {
            return "";
         }

         return $movie[$detail];
      }

      /**
       * @param array $titles_or_ids
       *
       * @since 0.2
       *
       * @return array|bool
       */
      public function get_movies(array $titles_or_ids) {
         $movies    = array();
         $not_found = array();
         foreach($titles_or_ids as $title_or_id) {
            $movie = imdb_connector_get_movie($title_or_id);
            if(!$movie) {
               $not_found[] = $title_or_id;
               continue;
            }
            $movies[] = $movie;
         }
         /** Display error message if one or more movies could not be found */
         if(count($not_found) >= 1) {
            echo " " . implode(", ", $not_found);
         }

         return (array)$movies;
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
      public function search_movie($id_or_title) {
         $api_url = "http://www.omdbapi.com/?s=" . imdb_connector_sanitize_url_title($id_or_title);
         $results = file_get_contents($api_url);
         $results = (array)json_decode($results, true);
         if(array_key_exists("Response", $results) && $results["Response"] === "False") {
            return false;
         }
         $results = imdb_connector_sanitize_movie_details($results);

         return (array)$results["search"];
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
      public function search_movies(array $ids_or_titles) {
         $results = array();
         foreach($ids_or_titles as $id_or_title) {
            $result = $this->search_movie($id_or_title);
            if(!$result) {
               continue;
            }
            $results[] = $result;
         }

         return (array)$results;
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
      public function has_movie($id_or_title) {
         if(!$this->get_movie($id_or_title)) {
            return false;
         }

         return (boolean)true;
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
      public function get_cached_movies($cache_location = "all", $type = "array") {
         $movies = array();
         $movie  = "";

         if($cache_location === "all" || $cache_location === "local") {
            foreach(glob(imdb_connector_get_cache_path() . "/*.tmp") as $file) {
               $movie    = json_decode(file_get_contents($file), true);
               $movies[] = $movie;
            }
         }
         if($cache_location === "all" || $cache_location === "database") {
            global $wpdb;
            $table           = $wpdb->prefix . imdb_connector_get_setting("database_table");
            $selected_movies = $wpdb->get_results("SELECT * FROM $table", "ARRAY_A");
            if(!count($selected_movies)) {
               return $movies;
            }

            foreach((array)$selected_movies as $movie_details) {
               $movie = array();
               foreach($movie_details as $movie_detail => $value) {
                  if(is_serialized($value)) {
                     $value = unserialize($value);
                  }
                  $movie[$movie_detail] = $value;
               }
               $movies[] = $movie;
            }
         }
         /** Convert array to stdClass object if set */
         if($type === "object") {
            $movies = json_decode(json_encode($movies));
         }

         return (array)$movies;
      }
   }