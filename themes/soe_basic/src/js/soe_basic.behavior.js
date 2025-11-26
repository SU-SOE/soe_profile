(function ($, Drupal) {
  Drupal.behaviors.randomNodeImageBorder = {
    attach(context) {
      const colors = ['turquoise', 'orange', 'pink'];
      const randomColor = colors[Math.floor(Math.random() * colors.length)];

      // Adds random border color to individual spotlight images on news spotlight node pages
      $(".node-page--stanford-news .su-news-spotlight-header .image .field-media-image", context).each(function () {
        // Only add class if it hasn't been added yet
        if (!$(this).data('border-processed')) {
          $(this).addClass(`border-color--${randomColor}`).data('border-processed', true);
        }
      });
    }
  };
})(jQuery, Drupal);