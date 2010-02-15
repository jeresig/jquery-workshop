jQuery(document).ready(function(){
	jQuery("pre").chili().html(function(i, html){
		html = html.replace(/\$\.(<span class="method">([\w\$]+)<\/span>)\(/g, function( all, span, name ) {
			name = name === "$" ? "jQuery" : name;
			return "$.<a href='http://api.jquery.com/jQuery." + name + "'>" + span + "</a>(";
		});
		
		html = html.replace(/(<span class="method">([\w\$]+)<\/span>)\(/g, function( all, span, name ) {
			name = name === "$" ? "jQuery" : name;
			return "<a href='http://api.jquery.com/" + name + "'>" + span + "</a>(";
		});
		
		return html;
	});
	
	function analyze(){
		var slide = jQuery(this).parents("div.slide");
		var text = slide.find("pre").text();
		text = text
			.replace(/&lt;/g, "<").replace(/&gt;/g, ">")
			.replace(/&nbsp;/g, " ").replace(/\s/g, " ")
			.replace(/(\$\("|appendTo\(")/g, "$1#" + slide.attr("id") + " ");
		eval( text );
	}
	
	var animating;
	
	jQuery.fn.goto = function(){
		var self = this;
		
		if ( self.length ) {
			animating = true;
			
			jQuery(document.body)
				.animate({scrollTop: self.offset().top}, "slow", function(){
					animating = false;
					window.location.hash = "#" + self.find("h1").attr("id");
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
	
	var height = jQuery(window).height();
	
	jQuery("div.slide").height( height );
		
	if ( window.location.hash ) {
		document.body.scrollTop =
			jQuery(window.location.hash).parent().offset().top;
	}
	
	setInterval(function(){
		if ( animating ) {
			return;
		}
		
		var start = document.body.scrollTop,
			end = start + height,
			hash = window.location.hash;
		
		jQuery("h1").each(function(){
			var top = jQuery(this).parent().offset().top,
				bottom = top + height,
				id = "#" + this.id;

			if ( hash !== id && (top > start && top < end || bottom > start && bottom < end) ) {
				window.location.hash = id;
				return false;
			}
		});
	}, 200);
});