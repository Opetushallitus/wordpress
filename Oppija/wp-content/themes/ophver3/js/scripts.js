var key = 'basket';
var basketCount = jQuery.cookie(key) ? JSON.parse(jQuery.cookie(key)).length - 1 : 0;

var LanguageCookie = (function() {
    var key = 'i18next';

    return {
        setLanguage: function(language) {
            jQuery.cookie(key, language, {useLocalStorage: false, path: '/'});
        }
    }
}());

jQuery(document).ready(function() {
    var lang = jQuery('html').attr('lang').substr(0,2);
    LanguageCookie.setLanguage(lang);

    jQuery('.search form').on('submit', function(event) {
        event.preventDefault();
        var searchWord = jQuery('input[name="search-field"]').val();
        
        window.location.href = jQuery(this).attr("action") + searchWord;
    });

    jQuery('.appbasket-count').html(basketCount);
    jQuery('#search-field-frontpage').focus();
    
    /* Sub navi -slideToggle */
    jQuery('nav.sidenav ul.children').each( function(){
        //jQuery(this).prev('a').addClass('collapsed');
        jQuery(this).prev('a').addClass('expandable');
        jQuery(this).prev('a').prepend('<span class="expand-icon"></span>');
        
    });
    
    jQuery('nav.sidenav > ul > li a:not([class])').each( function () {
       jQuery(this).prepend('<span class="no-icon"></span>'); 
    });
    
    jQuery('.expand-icon').on('click', function(e){
        e.preventDefault();
        jQuery(this).parent('a').toggleClass('expanded');
    });

    /* expand only the first level of subnavigation */
    jQuery('nav.sidenav > ul > li > a, li.current_page_item > a, li.current_page_parent > a, li.current_page_ancestor > a').addClass('expanded');
    
    /* expand all subnavigation items (DISABLED) */
    //jQuery('nav.sidenav li.current_page_ancestor a, nav.sidenav li.current_page_item a').addClass('expanded');
});