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
		
		window.location.href = 'http://opintopolku.fi/app/#/haku/' + searchWord;
	});

	$('.appbasket-count').html(basketCount);
	$('#search-field-frontpage').focus();
	
	/* Sub navi -slideToggle*/
	$('nav.sidenav ul.children').each( function(){
		//$(this).prev('a').addClass('collapsed');
		$(this).prev('a').after('<a class="expand-link" href="#"><img src="' + templateDir + '/img/down-arrow-06526b.png" alt="+" /></a>')
		
	});
	
	$('a.expand-link').click( function(e){
		e.preventDefault();
		$(this).prev('a').toggleClass('expanded');
	});

	/*expand current tree by default*/
	$('nav.sidenav li.current_page_ancestor a, nav.sidenav li.current_page_item a').addClass('expanded');
});