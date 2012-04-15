function log(x) {
	console.log(x);
}

function reloadMasonry(data) {
	console.log("Reloading masonry data "+data);
	$('#container').masonry({
		// options
		itemSelector : ".item-wrapper",
		singleMode : true,
		isAnimated : true
	});
	$("#container").append(data);
	//.masonry( 'appended', data , true);

	$.when($("#container").masonry('reload')).then($(".item-wrapper").animate({
		opacity : 1
	}, 500));
	addToBodyClass("black");
}

/**
 * add a class to page to help indentify via ajax
 */
function addToBodyClass(theClass) {
	log("addToBody");
	$("body").removeClass();
	//remove all
	$("body").addClass(theClass);
}


jQuery(document).ready(function() {
	jQuery("body").append("<div id='ajaxloaderdiv'></div>");
	//create the ajax loader
	$(".menu-item a").click(function(event) {
		event.preventDefault();
		alert("clicked");
		stripCurrentClasses();
		jQuery(this).parent().addClass("current_page_item ");
		loadFromInto(jQuery(this), "#main");
	});
});
function showAjaxLoader() {
	jQuery("#ajaxloaderdiv").show();
}

function hideAjaxLoader() {
	jQuery("#ajaxloaderdiv").hide();
}


jQuery("#ajaxloaderdiv").click(function() {
	hideAjaxLoader();
});
function removeCurrentClass() {
	var elems = new Array("#artist-tax li a", "#genre-tax li a", "#category-tax li a");
	var i = 0;
	for( i = 0; i < elems.length; i++)
	jQuery(elems[i]).each(function() {
		jQuery(this).removeClass("current");
	});
}

/**
 * comment scripts
 */
$("#commentform").submit(function(event) {
	event.preventDefault();
	alert("Clicked submit");
});
/**
 * function for loading the href off an element into the passed target div
 */
function loadFromInto(fromElem, intoElem) {
	console.log("Load from into called...");
	var from = jQuery(fromElem);
	var into = jQuery(intoElem);
	var href = from.attr("href");
	var title = from.attr("title");
	addToBodyClass(title);
	//set body class to the menu item name
	showAjaxLoader();
	location.hash = title;
	into.load(href, null, hideAjaxLoader);
}



/**
 * make the top nav appear to change
 */
function setCurrentMenu(text) {
	stripCurrentClasses();
	$(".menu-item a").each(function() {
		if($(this).text() == text)
			$(this).addClass("current_page_item");
		else
			$(this).removeClass("current_page_item");
	});
}

function stripCurrentClasses() {
	jQuery(".menu li").each(function() {
		jQuery(this).removeClass("current_page_item");
		jQuery(this).removeClass("current-menu-item ");
	});
}