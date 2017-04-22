<?php
   /**
    * Kolja Nolte
    * kolja.nolte@gmail.com
    * http://www.koljanolte.com
    * Created on 2015-08-17 02:27 UTC+7
    */

   /**
    * This file contains functions that turn all get_imdb_connector_* functions
    * into a deprecated status. Even though they still work, you should use the
    * alternative functions starting with imdb_connector_get_* instead since this
    * structure distinguishes it from native WordPress function or other plugins
    * more clearly.
    */

   /** Prevents this file from being called directly */
   if(!function_exists("add_action")) {
      return;
   }

   /**
    * @deprecated
    *
    * @return string
    */
   function get_imdb_connector_url() {
      _deprecated_function("get_imdb_connector_url", "1.3.2", "imdb_connector_get_url()");

      return imdb_connector_get_url();
   }

   /**
    * @deprecated
    */
   function the_imdb_connector_url() {
      _deprecated_function("the_imdb_connector_url", "1.3.2", "imdb_connector_the_url()");

      echo imdb_connector_get_url();
   }

   /**
    * @deprecated
    *
    * @return string
    */
   function get_imdb_connector_path() {
      _deprecated_function("get_imdb_connector_path", "1.3.2", "imdb_connector_get_path()");

      return imdb_connector_get_path();
   }

   /**
    * @deprecated
    */
   function the_imdb_connector_path() {
      _deprecated_function("the_imdb_connector_path", "1.3.2", "imdb_connector_the_path()");

      echo imdb_connector_get_path();
   }

   /**
    * @deprecated
    *
    * @return mixed
    */
   function get_imdb_connector_cache_path() {
      _deprecated_function("get_imdb_connector_cache_path", "1.3.2", "imdb_connector_get_cache_path()");

      return imdb_connector_get_cache_path();
   }

   /**
    * @deprecated
    */
   function the_imdb_connector_cache_path() {
      _deprecated_function("the_imdb_connector_cache_path", "1.3.2", "imdb_connector_the_cache_path()");

      echo imdb_connector_get_cache_path();
   }

   /**
    * @deprecated
    */
   function the_imdb_connector_cache_url() {
      _deprecated_function("the_imdb_connector_cache_url", "1.3.2", "imdb_connector_the_cache_url()");

      echo imdb_connector_get_cache_url();
   }

   /**
    * @param string $cache_location
    * @param string $type
    *
    * @deprecated
    *
    * @return array|mixed
    */
   function get_imdb_connector_cached_movies($cache_location = "all", $type = "array") {
      _deprecated_function("get_imdb_connector_cached_movies", "1.3.2", "imdb_connector_get_cached_movies(\$cache_location, \$type)");

      return imdb_connector_get_cached_movies($cache_location, $type);
   }

   /**
    * @param string $cache_location
    *
    * @since 1.3.2
    *
    * @deprecated
    *
    * @return bool
    */
   function delete_imdb_connector_cache($cache_location = "all") {
      _deprecated_function("delete_imdb_connector_cache", "1.3.2", "IMDb_Connector_Cache class");

      $class = new IMDb_Connector_Cache();

      return $class->delete_cache($cache_location);
   }

   /**
    * @param       $id_or_title
    * @param array $options
    *
    * @deprecated
    *
    * @return array
    */
   function get_imdb_connector_movie($id_or_title, array $options = array()) {
      _deprecated_function("imdb_connector_get_movie", "1.3.2", "get_imdb_connector_movie(\$id_or_title, array \$options)");

      return imdb_connector_get_movie($id_or_title, $options);
   }

   /**
    * @param $id_or_title
    *
    * @deprecated
    *
    * @return array
    */
   function search_imdb_connector_movie($id_or_title) {
      _deprecated_function("search_imdb_connector_movie", "1.3.2", "imdb_connector_search_movie(\$id_or_title)");

      return imdb_connector_search_movie($id_or_title);
   }

   /**
    * @param array $ids_or_titles
    *
    * @deprecated
    *
    * @return array
    */
   function search_imdb_connector_movies(array $ids_or_titles) {
      _deprecated_function("search_imdb_connector_movies", "1.3.2", "imdb_connector_search_movies(array \$ids_or_titles)");

      return imdb_connector_search_movies($ids_or_titles);
   }

   /**
    * @param $id_or_title
    *
    * @deprecated
    *
    * @return bool
    */
   function has_imdb_connector_movie($id_or_title) {
      _deprecated_function("has_imdb_connector_movie", "1.3.2", "imdb_connector_has_movie(\$id_or_title)");

      return imdb_connector_has_movie($id_or_title);
   }

   /**
    * @param array $ids_or_titles
    *
    * @deprecated
    *
    * @return array|bool
    */
   function get_imdb_connector_movies(array $ids_or_titles) {
      _deprecated_function("get_imdb_connector_movies", "1.3.2", "imdb_connector_get_movies(array \$ids_or_titles)");

      return imdb_connector_get_movies($ids_or_titles);
   }

   /**
    * @param $id_or_title
    * @param $detail
    *
    * @return bool|string
    */
   function get_imdb_connector_movie_detail($id_or_title, $detail) {
      _deprecated_function("get_imdb_connector_movies", "1.3.2", "imdb_connector_get_movie_detail(\$id_or_title)");

      return imdb_connector_get_movie_detail($id_or_title, $detail);
   }

   /**
    * @deprecated
    *
    * @return array
    */
   function get_imdb_connector_default_settings() {
      _deprecated_function("get_imdb_connector_default_settings", "1.3.2", "imdb_connector_get_default_settings()");

      return imdb_connector_get_default_settings();
   }

   /**
    * @param $setting
    *
    * @deprecated
    *
    * @return bool|string
    */
   function get_imdb_connector_default_setting($setting) {
      _deprecated_function("get_imdb_connector_default_setting", "1.3.2", "imdb_connector_get_default_setting(\$setting)");

      return imdb_connector_get_default_setting($setting);
   }

   /**
    * @deprecated
    *
    * @return array
    */
   function get_imdb_connector_settings() {
      _deprecated_function("get_imdb_connector_settings", "1.3.2", "imdb_connector_get_settings()");

      return imdb_connector_get_settings();
   }

   /**
    * @param $setting
    *
    * @deprecated
    *
    * @return array|mixed|string|void
    */
   function get_imdb_connector_setting($setting) {
      _deprecated_function("get_imdb_connector_setting", "1.3.2", "imdb_connector_get_setting(\$setting)");

      return imdb_connector_get_setting($setting);
   }