jQuery(document).ready(function(){
	jQuery("pre").chili();
	
	function analyze(){
		var slide = jQuery(this).parents("div.slide");
		var text = slide.find("pre").text();
		text = text
			.replace(/&lt;/g, "<").replace(/&gt;/g, ">")
			.replace(/&nbsp;/g, " ").replace(/\s/g, " ")
			.replace(/jQuery\("/g, "jQuery(\"#" + slide.attr("id") + " ");
		eval( text );
	}
	
	jQuery("button.run").click(analyze);
	jQuery("pre.run").each(analyze);
	
	jQuery("h1").click(function(){
		var nextSlide = jQuery(this).parent().next();
		
		if ( nextSlide.length ) {
			jQuery(document.body)
				.animate({scrollTop: nextSlide.offset().top}, "slow");
		}
	});
});