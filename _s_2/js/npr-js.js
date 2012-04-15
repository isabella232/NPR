function log(x) {
	console.log(x);
}

function reloadMasonry(data) {
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