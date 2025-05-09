(function ($) {
  $(window).load(function () {
    $(".is-root-container > section").each(function () {
      var itemIndex = $(this).index();
      var itemLabel = $(".block-editor-list-view-leaf .block-editor-list-view-block-select-button__title").eq(itemIndex).text();
      console.log(itemLabel);
      $('> div:first-child',this).before('<div id="sf-label">' + itemLabel + "</div>");
    });
  });
})(jQuery);
