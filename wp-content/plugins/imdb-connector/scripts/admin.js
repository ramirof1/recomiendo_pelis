jQuery(document).ready(
   function() {
      /** Defining global variables */
      var updatedSelector      = jQuery(".updated");
      var deleteCacheContainer = jQuery("#delete-cache-container");
      var deleteCache          = jQuery("#delete-cache");
      var showExamples         = jQuery("#show-examples");
      var hideExamples         = jQuery("#hide-examples");
      var examplesShown;

      /** Only apply scripts if we're on the IMDb Connector settings page */
      if(jQuery("#imdb-connector-settings").length < 1) {
         return false;
      }

      jQuery("#reset-button").click(
         function() {
            if(!confirm(jQuery("#reset-settings-label").text())) {
               return false;
            }
         }
      );

      /**
       * Fade out updated message after 5 seconds.
       */
      if(updatedSelector.length >= 1) {
         setTimeout(
            function() {
               updatedSelector.fadeOut("slow");
            }, 5000
         );
      }

      /**
       * Delete cache function.
       */
      deleteCache.click(
         function() {
            var loadingIcon = jQuery("#delete-cache-loading-icon");
            var messages    = deleteCacheContainer.find(".message").fadeOut();
            var fileUrl     = jQuery("#remote-actions-url").attr("value");
            var oldValue    = deleteCache.attr("value");
            var newValue    = oldValue.replace(/[0-9]/, "0");

            deleteCache.hide();
            messages.hide();
            loadingIcon.fadeIn();

            /** Calling AJAX and process deletion of cached files */
            jQuery.ajax(
               {
                  type: "get",
                  url: fileUrl + "?action=delete_cache" + "&nonce=" + jQuery("#delete_cache_nonce").attr("value"),
                  success: function(response) {
                     deleteCache.show();
                     loadingIcon.hide();
                     deleteCacheContainer.find(".message.success").fadeIn();
                     jQuery("#deleted-files-number").text(response);
                     deleteCache.attr("value", newValue);
                  }
               }
            );

            /** Fade out message after 10 seconds */
            setTimeout(
               function() {
                  deleteCacheContainer.find(".message").fadeOut();
               }, 10000
            );
         }
      );

      /**
       * Shows/hides shortcode examples.
       */
      jQuery("#toggle-examples-buttons").find("button").click(
         function() {
            var shortcodeExamples = jQuery("#shortcode-examples");

            if(examplesShown) {
               shortcodeExamples.slideUp();
               showExamples.show();
               hideExamples.hide();
               examplesShown = false;
            }
            else {
               shortcodeExamples.slideDown();
               showExamples.hide();
               hideExamples.show();
               examplesShown = true;
            }
            return false;
         }
      );

      return true;
   }
);