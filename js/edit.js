jQuery(function(){
  var textarea = document.getElementById('edit');
  if ( textarea ) {
	  var editor = new CodeMirror(CodeMirror.replace(textarea), {
	    height: "400px",
	    content: textarea.value,
	    parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
	    stylesheet: "../codemirror/css/jscolors.css",
	    path: "../codemirror/js/",
		tabMode: "indent"
	  });
  }

  jQuery("#editform").submit(function(){
	var start = (new Date).getTime();
	var save = jQuery("<span>Saving... <img src='../js/loading.gif'/></span>").prependTo(this);

	jQuery.ajax({
		url: ".",
		type: "POST",
		data: { edit: editor.getCode(), action: "do_edit" },
		success: function(){
			var end = (new Date).getTime();
			setTimeout(function(){
				save.remove();
			}, end - start > 500 ? 1 : 500 - (end - start) );
		}
	});
	
	return false;
  });
});