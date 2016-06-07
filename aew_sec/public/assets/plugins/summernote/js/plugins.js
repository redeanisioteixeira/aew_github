var summernote_btns = [
	["insert", ["emojiList", "link", "picture", "specialChar"/*, "fontIcon"*//*, "hr"*//*, "video"*/]],
	["font", ["bold", "italic", "underline", "strikethrough", "superscript", "clear"/*, "subscript"*/]],
	/*["fontname", ["fontname"]],*/
	["fontsize", ["fontsize"]],
	["color", ["color"]],
	["style", ["style"]],
	["para", ["paragraph"/*, "ul"*//*, "ol"*/]],
    /*["height", ["height"]],*/
	["view", ["codeview"/*, "fullscreen"*/, "undo", "redo"]]
];

var summernote_sms_buttons = [
	["insert", ["emojiList", "picture", "specialChar"]],
	["font", ["bold", "italic", "underline"]],
	["view", ["codeview"]]
];

$(function () {
	// Activate popovers and tooltips
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"]').tooltip();
  
	// Activate Summernote editors:
	$(".summernote").summernote({
		height: 200,
		toolbar: summernote_btns,
		//disableResizeImage: true
	});
	
	if ($(document).width() > 400) {
		summernote_sms_buttons = summernote_btns;
	}
	
	$(".sms-editor").summernote({
		height: 130,
		//modules: $.extend($.summernote.options.modules, {"emojiList": emojiList}),
		toolbar: summernote_sms_buttons
		/*onEnter: function(e) {
			e.preventDefault();
			getfilter();
			var msg = $("#mbody").val() + "";
			if (msg.substr(msg.length - 11) == "<p><br></p>") {
				$("#mbody").val(msg.substr(0, msg.length - 11));
			}
			document.getElementById("post-message").submit();
		}*/
	});
	$(".note-toolbar button").attr("type", "button");
	
	// Activate checkboxes and radio buttons
	$("input").not(".switch input").iCheck({
		checkboxClass: "icheckbox",
		radioClass: "iradio",
		increaseArea: "20%"
	});
	
	// Activate combo boxes
	$(".combobox").combobox();
	
	// Activate error checking on forms
	$("input,select,textarea").jqBootstrapValidation();
	
	// Add close buttons to alerts
	$(".alert-dismissible").prepend("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>");
	$(".alert-dismissible").addClass("fade in out");
});

// jQuery plugins to make adding tooltips and popovers easier
(function($) {
 
    $.fn.addTooltip = function(options) {
        var settings = $.extend({
            location: "left",
            title: "tooltip"
        }, options);
		
		return this.attr("data-toggle", "tooltip").attr("data-placement", settings.location).attr("title", settings.title).tooltip();
    };
	
	$.fn.addPopover = function(options) {
        var settings = $.extend({
            location: "left",
			title: "Popover Title",
            text: "Popover contents go here."
        }, options);
		
		if (settings.title != "") {
			this.attr("title", settings.title);
		}
		if (settings.text != "") {
			this.attr("data-content", settings.text);
		}
		this.attr("data-container", "body");
        return this.attr("data-toggle", "popover").attr("data-placement", settings.location).attr("role", "button").attr("data-trigger", "focus").popover();
    };
}(jQuery));