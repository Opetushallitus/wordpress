var key = 'basket';
var basketCount = $.cookie(key) ? JSON.parse($.cookie(key)).length - 1 : 0;

var LanguageCookie = (function() {
    var key = 'i18next';

    return {
        setLanguage: function(language) {
            $.cookie(key, language, {useLocalStorage: false, path: '/'});
        }
    }
}());

$(document).ready(function() {
    var lang = $('html').attr('lang').substr(0,2);
    LanguageCookie.setLanguage(lang);

    $('.search form').on('submit', function(event) {
        event.preventDefault();
        var searchWord = $('input[name="search-field"]').val();
        
        window.location.href = $(this).attr("action") + searchWord;
    });

    $('.appbasket-count').html(basketCount);
    $('#search-field-frontpage').focus();
    
    /* Sub navi -slideToggle */
    $('nav.sidenav ul.children').each( function(){
        //$(this).prev('a').addClass('collapsed');
        $(this).prev('a').addClass('expandable');
        $(this).prev('a').prepend('<span class="expand-icon"></span>');
        
    });
    
    $('nav.sidenav > ul > li a:not([class])').each( function () {
       $(this).prepend('<span class="no-icon"></span>'); 
    });
    
    $('.expand-icon').on('click', function(e){
        e.preventDefault();
        $(this).parent('a').toggleClass('expanded');
    });

    /* expand only the first level of subnavigation */
    $('nav.sidenav > ul > li > a, li.current_page_item > a, li.current_page_parent > a, li.current_page_ancestor > a').addClass('expanded');
    
    /* expand all subnavigation items (DISABLED) */
    //$('nav.sidenav li.current_page_ancestor a, nav.sidenav li.current_page_item a').addClass('expanded');
});