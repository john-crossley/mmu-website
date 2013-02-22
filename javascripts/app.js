// Make sure the document is ready before any
// JavaScript is executed
$(document).ready( function($) {

	// Place everything in a self invoking annonymous function
	// this stops everything being in the global namespace.
	( function() {

		"use strict"; // Enables better JavaScript coding.

		$(window).scroll( function() {
			var top = $("a[href=#scroll_top]");
			if ( $(window).scrollTop() + $(window).height() > ($(document).height() -$('#building_this_site').height()-100) ) {
				top.fadeIn();
			} else {
				top.fadeOut();
			}
		});

		$("a[href=#scroll_top]").on('click', function() {
			$("html, body").animate({
				scrollTop: $("body").offset().top
			}, 500);
			return false;
		});


		// Show my latest tweet.
		$.getJSON('https://api.twitter.com/1/statuses/user_timeline/jonnothebonno.json?callback=?', function(data) {
			$('.tweet').append(data[0].text).fadeIn();
		});

		$("#blog_information ul li a").on("click", function() {
			var id = $(this).attr("href");

			$("html, body").animate({
				scrollTop: $(id).offset().top
			}, 1000);

			return false;
		});

		/**
		 * This jQuery method is triggerd when a user submits the form
		 * on my website, it just does some dirty validation nothing too
		 * special.
		 */
		$('#contact_form').on('submit', function() {

			// Fade out all span elements and make them = to ""
			$('small.error').text("");

			// Get the fields by the names.
			var name = $('input[name=name]'),
					email = $('input[name=email]'),
					website = $('input[name=website]'),
					message = $('textarea[name=message]'),
					human = $('input[name=human]'),
					error = false;

			// Check to see the required fields have values.
			if ( name.val() === "" )	{
				name.parent().append($('<small>', {
					'text': "Please enter your name",
					'class': "error"
				}));
				error = true;
			} else error = false;

			if ( email.val() === "" ) {
				email.parent().append($('<small>', {
					'text': "Please enter your email address",
					'class': "error"
				}));
				error = true;
			} else error = false;

			if ( message.val() === "" ) {
				message.parent().append($('<small>', {
					'text': "Please enter a message",
					'class': "error"
				}));
				error = true;
			} else error = false;

			if ( human.val() != 4 ) {
				human.parent().append($('<small>', {
					'text': "Go away bot! Please try again",
					'class': "error"
				}));
				error = true;
			} else error = false;

			if ( error ) return false;
		});

	})();

});