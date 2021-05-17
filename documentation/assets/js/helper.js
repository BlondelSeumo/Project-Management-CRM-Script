$(document).ready(function () {
    $.ajaxSetup({cache: false});

    //set custom scrollbar
    setPageScrollable();
    setMenuScrollable();
    $(window).resize(function () {
        setPageScrollable();
        setMenuScrollable();
    });

    //expand or collaps sidebar menu items
    $("#sidebar-menu a").click(function () {
        var $target = $(this).parent();
        if ($target.hasClass('main')) {
            if ($target.hasClass("expand")) { //nested list
                if ($target.hasClass('open')) {
                    $target.removeClass('open');
                } else {
                    $("#sidebar-menu >.expand").removeClass('open');
                    $target.addClass('open');
                }
            } else { //main list
                $("#sidebar-menu >.expand").removeClass('open');
            }

            $("#sidebar-menu li").removeClass("active");
            $target.addClass("active");
        }
    });

    //the top offset will be changed while scrolling
    //so, we've to store it first
    var sectionsData = [];
    setTimeout(function () {
        $('section').each(function () {
            sectionsData.push({
                "name": "#" + $(this).attr("id"),
                "top": $(this).offset().top,
                "height": $(this).height()
            });
        });
    }, 500);

    //add active class on scrolling window
    $("#scrollable-page").scroll(function () {
        var scrollPosition = $("#scrollable-page").scrollTop() + 70;

        for (i = 0; i < sectionsData.length; i++) {
            var section = sectionsData[i];

            if ((section.top <= scrollPosition) && ((section.top + section.height) >= scrollPosition)) {
                $("#sidebar-menu li").removeClass('active open');

                var $parentList = $("#sidebar-menu").find("a[href='" + section.name + "']").parent("li");
                if ($parentList.hasClass("main")) { //main list
                    $parentList.addClass("active");
                } else { //nested list
                    $parentList.closest("li.expand").addClass("active open");
                }
            }
        }

    });

    //expand nested list from hash link
    var target = window.location.hash,
            $selector = $("#sidebar-menu").find("a[href='" + target + "']");

    if (!($selector).hasClass("main")) {
        ($selector).closest("li.main").addClass("open active");
    }

});

//set scrollbar on page
setPageScrollable = function () {

    if ($(window).width() <= 640) {
        $('html').css({"overflow": "initial"});
        $('body').css({"overflow": "initial"});
    } else {
        initScrollbar('.scrollable-page', {
            setHeight: $(window).height() - 45
        });
    }

};

//set scrollbar on left menu
setMenuScrollable = function () {
    initScrollbar('#sidebar-scroll', {
        setHeight: $(window).height() - 45
    });
};

initScrollbar = function (selector, options) {
    if (!options) {
        options = {};
    }

    if (!$(selector).length)
        return false;

    if (selector && selector.selector) {
        //it's a jquery element
        //add a id with the elment and then apply scrollbar
        var id = getRandomAlphabet(8);
        selector.attr("id", id);
        selector = "#" + id;
    }

    var defaults = {
        wheelPropagation: true
    }, settings = $.extend({}, defaults, options);


    if (options.setHeight) {
        $(selector).css({"height": settings.setHeight + "px", position: "relative"});
    }

    var ps = new PerfectScrollbar(selector);

};