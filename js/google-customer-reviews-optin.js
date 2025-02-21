(function (Drupal, drupalSettings) {
  'use strict';

  window.renderOptIn = function() {
    window.gapi.load('surveyoptin', function() {
      window.gapi.surveyoptin.render(drupalSettings.googleCustomerReviews);
    });
  };

  Drupal.behaviors.googleCustomerReviews = {
    attach: function (context) {
      // Call renderOptIn if Google API is loaded
      if (window.gapi && context === document) {
        window.renderOptIn();
      }
    }
  };

})(Drupal, drupalSettings);