jQuery(document).ready(function(){
	jQuery("pre").chili();
	
	function analyze(){
		var slide = jQuery(this).parents("div.slide");
		var text = slide.find("pre").text();
		text = text
			.replace(/&lt;/g, "<").replace(/&gt;/g, ">")
			.replace(/&nbsp;/g, " ").replace(/\s/g, " ")
			.replace(/$\("/g, "$(\"#" + slide.attr("id") + " ");
		eval( text );
	}
	
	jQuery.fn.goto = function(){
		var self = this;
		
		if ( self.length ) {
			jQuery(document.body)
				.animate({scrollTop: self.offset().top}, "slow", function(){
					window.location.hash = self.find("h1").attr("id");
				});
		}
		
		return this;
	};
	
	jQuery("button.run").click(analyze);
	jQuery("pre.run").each(analyze);
	
	jQuery("h1")
		.attr("id", function(i){ return i; })
		.click(function(){
			jQuery(this).parent().next().goto();
		});
	
	jQuery("div.slide").height(function(i, height){ return height; });
		
	if ( window.location.hash ) {
		jQuery(window.location.hash).parent().goto();
	}
});