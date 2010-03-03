$(function(){
	var people, possibility = 0.02, speed = 10000,
		width = 600, height = 400,
		phase = 2000, maxhits = 1, numPeople = 0,
		smTimer, lgTimer;
	
	$.getJSON("people.json", function( json ) {
		people = json;
	});
	
	function addUser( person, size ) {
		var diff = (Math.random() * 100), d = size === "lg" ? 150 : 20;
		
		jQuery("<div class='user'><img/></div>")
			.find("img").attr( "src", person.src ).end()
			.addClass( size )
			.appendTo("#game")
			.css({ "top": ((height - d) * Math.random()), "left": width + diff })
			.data({ "hits": 0, "maxhits": size === "lg" ? maxhits * 5 : maxhits, user: person.name })
			.animate({ left: -1 * d }, size === "lg" ? speed * 2 : speed + ((diff / 600) * speed), "linear", function(){
				$(this).trigger("escape");
			});
		
		numPeople++;
		
		if ( numPeople % 20 === 0 ) {
			possibility += 0.005;
			if ( possibility >= 0.1 ) {
				possibility = 0.1;
			}

			speed -= 200;
			if ( speed < 5000 ) {
				speed = 5000;
			}
		}
	}
	
	$("#game").bind("start", function(){
		possibility = 0.02;
		speed = 10000;
		
		smTimer = setInterval(function(){
			for ( var i = 0; i < people.length; i++ ) {
				if ( Math.random() < possibility ) {
					addUser( people[i], "small" );
				}
			}
		}, phase);
		
		lgTimer = setInterval(function(){
			addUser( people[ Math.round(Math.random() * people.length) ], "lg" );
		}, phase * 5);
		
		$(this).data("running", true);
	});
	
	$("#game").bind("stop", function(){
		$(".user").stop().fadeOut( 5000, function(){
			$(this).remove();
		});
		
		clearInterval( smTimer );
		clearInterval( lgTimer );
		
		$(this).data("running", false);
	})
	
	$("#game").delegate(".user", "mousedown", function(){
		$(this).trigger("hit");
	});
	
	// Prevent selection
	$("#game").bind("mousedown", function(e){
		e.preventDefault();
	});
	
	// Prevent accidental selection
	$("#game").attr('unselectable', 'on').css('MozUserSelect', 'none');
});