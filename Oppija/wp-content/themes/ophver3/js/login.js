// Login URLs to header

var lang = jQuery('html').attr('lang').substr(0,2);
var ajaxUrl = '/omattiedot/secure/';
var loginUrl = '/Shibboleth.sso/Login' + lang.toUpperCase();
var logoutUrl = '/Shibboleth.sso/Logout';

jQuery.ajax({
    dataType: 'json',
    url: ajaxUrl,
})
.done(function(data) {
    jQuery('.primarylinks > ul').prepend('<li><a href="'+logoutUrl+'?return='+oph_login.site_url+'"><span>'+oph_login.logout+'</span></a></li>');
    jQuery('.primarylinks > ul').prepend('<li><span>'+oph_login.greeting+', '+data['kutsumanimi']+'!</a></li>');
})
.fail(function(data) {
    jQuery('.primarylinks > ul').prepend('<li><a href="'+loginUrl+'"><span>'+oph_login.login+'</span></a></li>');
});