jQuery(document).ready(function($) {
    function add_sticky() {
        var nav = $("#main-nav");
        var fromTop = $(document).scrollTop();
        var navTop = nav.offset().top;
        nav.toggleClass("sticky-wrapper", (fromTop > navTop));
        nav.children(".et_pb_fullwidth_menu").toggleClass("sticky", (fromTop > navTop));
    } 
    
    $(window).on("scroll", add_sticky);
    add_sticky();

    function change_top() {
        $(".hide-text .et_pb_post").each(function() {
            var img_height = $(this).children(".et_pb_image_container").height();
            var h2 = $(this).children("h2");
            var height = parseInt(((img_height-h2.height())/2)+2);
            h2.css('top', height);
            console.log(img_height + " ------ " + h2.height());
            console.log(h2);
        });
    }

    setTimeout(function () {
        change_top();
    }, 100);
    $(window).on("resize", change_top);

    $(window).on("resize", function() {
        // todo change on width
        salvattore['recreate_columns']($(".hide-text")[0]);
    });
});