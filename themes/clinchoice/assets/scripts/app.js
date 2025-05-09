(function ($) {
  var page = $("body").attr("id");

  $(window).on("load", function () {
    init();
    banner();
    blockLinks();
    map();
    modal();
    modalGated();
    navDesktop();
    navMobile();
    sectorsMobile();
    search();
    spotlight();
    swiperExpertise1();
    swiperExpertise2();
    swiperPost();
    swiperTestimonials();
    privacyLink();
    scrollStatus();
    //statsCounter();
    swapOrder();

    if ($(".has-gated").length) {
      $(".has-gated").on("mousemove", function (event) {
        setTimeout(function () {
          modalGated();
        }, 100);
      });
    }

    $("body").hover(function () {
      setTimeout(function () {
        $('.cky-notice-des a[href="#"], .cky-btn-revisit a').addClass("cky-banner-element");
      }, 500);
    });

    var swiper1 = new Swiper(".mySwiper1", {
      slidesPerView: 3,
      slidesPerGroup: 3,
      spaceBetween: 30,
      navigation: {
        nextEl: ".swiper-button-next1",
        prevEl: ".swiper-button-prev1",
      },
      breakpoints: {
        1140: {
          slidesPerView: 4,
        },
      },
    });
    var swiper2 = new Swiper(".mySwiper2", {
      slidesPerView: 3,
      slidesPerGroup: 3,
      spaceBetween: 30,
      navigation: {
        nextEl: ".swiper-button-next2",
        prevEl: ".swiper-button-prev2",
      },
      breakpoints: {
        1140: {
          slidesPerView: 4,
        },
      },
    });
  });

  $(window).resize(function () {
    setTimeout(function () {
      banner();
      blockLinks();
      map();
      sectorsMobile();
    }, 500);
  });

  function init() {
    $("style").not("#wp-custom-css").remove();
    $("main").addClass("active");
  }

  function banner() {
    if ($(window).width() > 1024) {
      if ($("#banner").length) {
        var bannerHeight = $("#banner").height();
        var bannerPaddingTop = $("#banner").css("padding-top");
        var bannerPaddingBottom = $("#banner").css("padding-bottom");
        $("#banner .bg").css({ minHeight: parseInt($("#banner").height() + 200) });
      }
    } else {
      $("#banner .bg").css("min-height", "42.5vw");
    }
  }

  function blockLinks() {
    if ($(".block-links").length && ($("body").attr("id") != "home" || $(window).width() > 900)) {
      $(".block-links > div").each(function () {
        $(this).click(function () {
          window.location = $("a", this).attr("href");
        });
      });
    }
  }

  function modalGated() {
    $('a[href="javascript:;"]').click(function () {
      var gated_title = $(".title", this).text();
      var gated_category = $(".category", this).text();
      var gated_url = $(this).data("file");
      openModal(gated_title, gated_category, gated_url, "");
      return false;
    });
  }

  function map() {
    if ($("#map").length) {
      var targetHTML = $("aside ul:first-child li:first-child .contact").html();
      $("#map article").html(targetHTML);
      $("#map .region").hover(
        function () {
          var target = $(this).data("target");
          var status = $(this).attr("class").replace("region ", "");
          if (status != "active") {
            $('.pins svg[data-target="' + target + '"]')
              .addClass("active")
              .siblings()
              .removeClass("active");
          }
        },
        function () {
          $(".pins svg").removeClass("active");
        }
      );
      $("#map .region, .locations .tab").click(function () {
        var target = $(this).data("target");
        $(".details #" + target)
          .addClass("active")
          .siblings()
          .removeClass("active");
        $('.region[data-target="' + target + '"]')
          .addClass("active")
          .siblings()
          .removeClass("active");
        $('.tab[data-target="' + target + '"]')
          .addClass("active")
          .siblings()
          .removeClass("active");
        $("#map aside #" + target)
          .siblings()
          .removeClass("active")
          .find("ul")
          .fadeOut(100)
          .find(".contact")
          .removeClass("active");
        $("#map aside ul li:not(:first-child) h3").removeClass("active");
        setTimeout(function () {
          console.log("#map aside #" + target + " ul");
          $("#map aside #" + target + " ul")
            .fadeIn(100)
            .find("li:first-child .contact")
            .addClass("active");
          $("aside .tab[data-target='" + target + "']").addClass("active");
        }, 200);
        var targetCountry = $("#map aside #" + target + " ul li:first-child");
        targetCountry.find(".title").addClass("active");
        var targetHTML = targetCountry.find(".contact").html();
        $("#map article").html(targetHTML);
      });
      $("#map .region").click(function () {
        $("html,body").animate(
          {
            scrollTop: parseInt($("#locations").offset().top - 180),
          },
          200
        );
      });
      $("#map .title").click(function () {
        var target = $(this).data("target");
        var targetHTML = $("." + target).html();
        $(this).addClass("active");
        $("#map .title").not(this).removeClass("active");
        $("#map article").html(targetHTML);
      });
      if ($(window).width() > 640) {
        $("#map article").insertAfter("aside");
      } else {
        $("#map aside > div").each(function () {
          var regionx = $(this).attr("id");
          $(this).prepend($('.tab[data-target="' + regionx + '"]'));
        });
        $("#map aside > div.active").append($("#map article"));
        $("aside .tab").click(function () {
          $(this).parent().addClass("active").siblings().removeClass("active");
          $("article").appendTo($(this).parent());
        });
      }
    }
  }

  function modal() {
    if ($(".modal").length) {
      $(".modal-link").on("click", function () {
        var bio_title = $(".name", this).text();
        var bio_role = $(".role", this).text();
        var bio_details = $(".bio", this).html();
        var bio_image = $("img", this).attr("src");
        openModal(bio_title, bio_role, bio_details, bio_image);
        return false;
      });
      $(".home-link").on("click", function () {
        var name = $(".name", this).text();
        var description = $(".desription", this).text();
        var slug = $(".slug", this).text();
        var color_code = $(".color_code", this).text();
        homeModal(name, description, slug, color_code);
        return false;
      });
      $(".modal .close").on("click", function () {
        closeModal();
      });
    }
  }

  function navDesktop() {
    function hideSubnav() {
      $("header").removeClass("expanded");
      $(".mega").removeClass("active");
    }
    function showSubnav(target) {
      if ($(target).attr("id") == "solutions-nav") {
        $("#solutions-nav .links > ul:last-child").prepend($("#solutions-nav .post-marketing-real-world-evidence"));
      }
      target.addClass("active");
      $("header").addClass("expanded");
      $(".mega").not(target).removeClass("active");
    }
    $(".main-menu li:not(.has-buttons)").hover(function () {
      if ($(window).width() > 1161) {
        var target = $(this).attr("class").split(" ")[0];
        showSubnav($("#" + target));
      }
    });
    $("header").mouseleave(function () {
      if ($(window).width() > 1161) {
        hideSubnav();
      }
    });
    $(".logo,li.has-buttons,.utility-menu").mouseenter(function () {
      if ($(window).width() > 1160) {
        hideSubnav();
      }
    });
  }

  function navMobile() {
    function hideSubnav() {}
    function showSubnav(target) {
      $("#primary #menu").removeClass("active");
      target.addClass("active");
      // $("header").addClass("expanded");
      // $(".mega").not(target).removeClass("active");
    }

    $(".mobile-open").click(function () {
      $("body,html").addClass("locked");
      $("body,html").removeClass("modal-open");
      $("header").addClass("expanded");
      $("#primary #menu").addClass("active");
      $(".mobile-close").addClass("active");
      $(".mega").removeClass("active");
    });
    $(".mobile-close").click(function () {
      $("body,html").removeClass("locked");
      $("header").removeClass("expanded");
      $("#primary #menu").removeClass("active");
      $(".mobile-close").removeClass("active");
    });

    $("#primary #menu li:not(.has-buttons) a").click(function () {
      if ($(window).width() < 1160) {
        var target = $(this).parent().attr("class").split(" ")[0];
        console.log(target);
        showSubnav($("#" + target));
        return false;
      }
    });

    $(".mega .back").click(function () {
      $(".mega").removeClass("active");
      $("#primary #menu").addClass("active");
    });
  }

  function openModal(a, b, c, d) {
    $("body,html").addClass("locked modal-open");
    $(".modal").addClass("active");
    if ($("#leadership").length) {
      $("main").css({ "z-index": 99999 });
      $(".modal h2").text(a);
      $(".modal h3").text(b);
      $(".modal .details").html(c);
      $(".modal img").attr("src", d);
    }
    if ($(".has-gated").length) {
      $("#input_4_7").val(a);
      $("#input_4_8").val(b);
      $("#input_4_9").val(c);
      console.log(a);
      console.log(b);
      console.log(c);
    }
    $(".modal").on("click", function (event) {
      var clicked = event.target.classList[0];
      if (clicked == "modal") {
        closeModal();
      }
    });
  }
  function homeModal(a, b, c, d) {
    $("body,html").addClass("locked modal-open");
    $(".modal").addClass("active");
    if ($("#slider").length) {
      $("main").css({ "z-index": 99999 });
      $(".modal h3").text(a);
      $(".modal p").text(b);
      $(".modal a").attr('href', c);

      const colorClasses = ['purple-color', 'bluegreen-color', 'dark-color', 'teal-color', 'orange-color','blue-color', 'dark', 'purple', 'bluegreen', 'teal', 'orange','blue'];
        $(".modal h3").removeClass(colorClasses.join(' ')).addClass(d + '-color');
        $(".modal a").removeClass(colorClasses.join(' ')).addClass(d); 
    }
   
    $(".modal").on("click", function (event) {
      var clicked = event.target.classList[0];
      if (clicked == "modal") {
        closeModal();
        const colorClasses = ['purple-color', 'bluegreen-color', 'dark-color', 'teal-color', 'orange-color'];
            $(".modal h3").removeClass(colorClasses.join(' '));
            $(".modal a").removeClass(colorClasses.join(' '));
      }
    });
  }

  function closeModal() {
    var page = $("body").attr("id");
    if (page != "insights") {
      $("body,html").removeClass("locked modal-open");
      $(".modal").removeClass("active");
      $("main").css({ "z-index": 1 });
    } else {
      window.location.reload();
    }
  }

  function search() {
    if ($(".search-icon").length) {
      $(".search-icon").click(function () {
        $(this).toggleClass("active");
        $("#search-form").toggleClass("active");
      });
      $("section").click(function () {
        $(".search-icon").removeClass("active");
        $("#search-form").removeClass("active");
      });
    }
  }

  function sectorsMobile() {
    if ($("#home #hero").length) {
      $("#home #sectors-home").append('<div id="sector-description"><div class="content"></div><div class="close"></div></div>');
      $("#home #sectors-home .wp-block-column").click(function () {
        var color = $(this).attr("class").split(" ")[1];
        $("#sector-description").addClass("active").find(".content").removeClass("purple blue teal orange").addClass(color).html($(this).html());
        var newHeight = $("#sector-description").height();
        $("#sector-description .close").css({ bottom: newHeight - 25 });
      });
    }
    $(".close").click(function () {
      $("#sector-description").removeClass("active");
    });
  }

  function spotlight() {
    if ($("#spotlight").length) {
      var bg = $("img", $("#spotlight")).attr("src");
      $(".image", $("#spotlight")).css("background-image", "url(" + bg + ")");
    }
  }

  function swiperPost() {
    if ($(".post-slider").length) {
      var swiperPost = new Swiper(".post-slider .swiper", {
        loop: false,
        slidesPerView: 1,
        spaceBetween: 15,
        pagination: {
          clickable: true,
          el: ".swiper-pagination",
          type: "progressbar",
        },
        navigation: {
          nextEl: ".swiper-button-next-posts",
          prevEl: ".swiper-button-prev-posts",
        },
        breakpoints: {
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        },
      });
    }
  }

  function swiperExpertise1() {
    if ($("#therapeautic-areas").length) {
      var swiperExpertise = new Swiper("#therapeautic-areas .swiper", {
        slideClass: "wp-block-column",
        slidesPerView: 3,
        slidesPerGroup: 4,
        spaceBetween: 25,
        navigation: {
          nextEl: ".swiper-button-next-1",
          prevEl: ".swiper-button-prev-1",
        },
      });
    }
  }
  function swiperExpertise2() {
    if ($("#specialties").length) {
      var swiperExpertise = new Swiper("#specialties .swiper", {
        slideClass: "wp-block-column",
        slidesPerView: 4,
        slidesPerGroup: 4,
        spaceBetween: 30,
        navigation: {
          nextEl: ".swiper-button-next-2",
          prevEl: ".swiper-button-prev-2",
        },
      });
    }
  }

  function swiperTestimonials() {
    if ($("#testimonials .swiper").length) {
      var swiperPost = new Swiper("#testimonials .swiper", {
        slidesPerView: 1,
        pagination: {
          clickable: true,
          el: ".swiper-pagination",
        },
        breakpoints: {
          768: {},
          1024: {},
        },
      });
    }
  }

  function privacyLink() {
    if ($(".privacy-link").length) {
      $(".privacy-link a").click(function () {
        var link = $(this).attr("href");
        window.open(link, "_blank");
        return false;
      });
    }
  }

  function scrollStatus() {
    var startToggle = 0;
    if ($(".home").length) {
      startToggle = 150;
    }
    $(window).scrollTop() > startToggle ? $("header").addClass("scrolled") : $("header").removeClass("scrolled");
  }

/*
  function statsCounter() {
    if ($("body").attr("id") == "home") {
      const counterUp = window.counterUp.default,
        IO = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              const el = entry.target;
              entry.isIntersecting &&
                !el.classList.contains("is-visible") &&
                (counterUp(el, {
                  duration: 1500,
                  delay: 5,
                }),
                el.classList.add("is-visible"));
            });
          },
          {
            threshold: 1,
          }
        );
      IO.observe(document.querySelector(".count"));
      for (var num = document.getElementsByClassName("count"), i = 0; i < num.length; i++) IO.observe(num[i]);
    }
  }
*/

  function swapOrder() {
    if ($(".below-content").length) {
      $("footer").before($(".below-content"));
      $(".below-content").addClass("active");
    }
    if ($("body").attr("id") == "careers") {
      if ($(window).width() <= 640) {
        $(".v3 .text").prepend($(".v3 .headline h2"));
      }
    }
  }

  $(window).on("scroll", function () {
    scrollStatus();
  });
})(jQuery);
