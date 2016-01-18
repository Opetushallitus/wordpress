/*jslint browser: true*/
/*jshint strict: true */
/*global $, jQuery, alert*/
$(document).ready(function () {
    'use strict';
    $('#tabs li a').click(function (e) {
        e.preventDefault();
        var t = $(this).attr('id');
        $('#tabs li a').removeClass('active');
        $(this).addClass('active');
        $('.tab-container').hide();
        $('#' + t + 'C').show();
    });
});
