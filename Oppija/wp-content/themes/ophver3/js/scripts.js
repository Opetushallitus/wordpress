var $ = jQuery;

/**
 *  Resolves prefix for cookie names in different environments
 */
var CookiePrefixResolver = (function() {
    return {
        getPrefix: function(currentHost) {
            var prefix = 'test';
            var prodHosts = ['opintopolku.fi', 'studieinfo.fi', 'studyinfo.fi'];

            var result = '';
            if (currentHost) {
                result = prefix;
                currentHost = currentHost.toLowerCase(); // domain names are case insensitive
                for (var host in prodHosts) {
                    if (currentHost.indexOf( prodHosts[host] ) >= 0 && currentHost.length == prodHosts[host].length) {
                        result = '';
                    }
                }
            }

            return result;
        }
    };
}());

var BasketCookie = (function() {
    var prefix = CookiePrefixResolver.getPrefix(window.location.host);
    var key = prefix + 'basket';

    return {
        getBasketCount: function() {
            return jQuery.cookie(key) ? JSON.parse(jQuery.cookie(key)).length : 0;
        }
    };
}());

var LanguageCookie = (function() {
    var prefix = CookiePrefixResolver.getPrefix(window.location.host);
    var key = prefix + 'i18next';

    return {
        setLanguage: function(language) {
            jQuery.cookie(key, language, {useLocalStorage: false, path: '/'});
        }
    }
}());

jQuery(document).ready(function() {
    var lang = jQuery('html').attr('lang').substr(0,2);
    LanguageCookie.setLanguage(lang);

    jQuery('.search-group form').on('submit', function(event) {
        event.preventDefault();
        var searchWord = jQuery('input[name="search-field"]').val();
        
        window.location.href = jQuery(this).attr("action") + searchWord;
    });

    var basketCount = BasketCookie.getBasketCount();
    jQuery('.appbasket-count').html(basketCount);
    
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