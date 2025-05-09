(function ($) {
  $(window).on("load", function () {
    if ($("#regulatory-intelligence").length) {
      $('select[data-filter="country_region"]').change(function () {
        var target = $(this).val();
        if (target != "Country/Region") {
          $(".col-country:not([data-val=" + target + "])")
            .parent()
            .addClass("blocked");
          $(".col-country[data-val=" + target + "]")
            .parent()
            .removeClass("blocked");
        } else {
          $(".col-country").parent().removeClass("blocked");
        }
        $("select").not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
      $('select[data-filter="health_authority"]').change(function () {
        var target = $('select[data-filter="health_authority"]').val();
        if (target == "Health Authority") {
          $(".col-health_authority").parent().removeClass('blocked');
        } else {
          $(".col-health_authority:not([data-val='" + target + "'])")
            .parent()
            .addClass("blocked");
          $(".col-health_authority[data-val='" + target + "']")
            .parent()
            .removeClass("blocked");
        }
        $('select').not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
      $('select[data-filter="year"]').change(function () {
        var target = $(this).find(":selected").data("val");
        $(".col-date").each(function () {
          console.log(target);
          var fullDate = $(this).data("val");
          var year = String(fullDate).substr(0, 4);
          if (year == target) {
            $(this).parent().removeClass('blocked');
          } else {
            $(this).parent().addClass("blocked");
          }
        });
        $('select').not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
      $('select[data-filter="month"]').change(function () {
        var target = $(this).find(":selected").data("val");
        $(".col-date").each(function () {
          var fullDate = $(this).data("val");
          var month = String(fullDate).slice(4, 6);
          console.log(month);
          if (month == target) {
            $(this).parent().removeClass('blocked');
          } else {
            $(this).parent().addClass("blocked");
          }
        });
        $('select').not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
      $('select[data-filter="product_type"]').change(function () {
        var target = this.value;
        if (target == "Product Type") {
          $(".col-product_type").parent().removeClass('blocked');
        } else {
          $(".col-product_type:not([data-val='" + target + "'])")
            .parent()
            .addClass("blocked");
          $(".col-product_type[data-val='" + target + "']")
            .parent()
            .removeClass("blocked");
        }
        $('select').not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
      $('select[data-filter="domain"]').change(function () {
        var target = this.value;
        if (target == "Domain") {
          console.log(1);
          $(".col-domain").parent().removeClass('blocked');
        } else {
          $(".col-domain:not([data-val='" + target + "'])")
            .parent()
            .addClass("blocked");
          $(".col-domain[data-val='" + target + "']")
            .parent()
            .removeClass("blocked");
        }
        $('select').not($(this)).find('option[data-val="__all"]').prop("selected", true);
      });
    }
  });
})(jQuery);
