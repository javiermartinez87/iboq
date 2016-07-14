app_analisis.controller('menu_controller', function($element) {
    var enlaces = $($element).find('a');
    $.each(enlaces, function(index, elemnt) {
        var url_enlace = $(elemnt).prop('href');
        if (url_enlace !== '' && window.location.href.indexOf(url_enlace) !== -1 && !$(elemnt).hasClass('dropdown-toggle')) {
            $($(elemnt).parent()).addClass('active');
            var drop_down = $($($(elemnt).parent()).parent());
            if (drop_down.hasClass('dropdown-menu')) {
                $(drop_down.parent()).addClass('active');
            }
        }
    });

});

