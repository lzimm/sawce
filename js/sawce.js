$(document).ready(function() {
        //$('.editor_msg').slideUp(1000);
    });

function sw_update() {
	sw_hidemsg();
	
	$('#sw_ajax').animate({
		opacity: 0
 	}, 'fast');
	
	var data = $('#sw_ajax :input').serialize();
	var host = sw_replace_action('ajax', $('#sw_ajax').attr('action'));
	$.post(host, data, sw_refresh);
	
	return false;
}

function sw_replace_action(type, action) {
	var pos = 0;
	for (i = 0; i < action.length; i++) {
		if ((action.charAt(i) == '/') && (action.charAt(i+1) != '/') && ((i == 0) || (action.charAt(i-1) != '/'))) {
			pos = i;
			break;
		}
	}

	return action.substring(0, pos + 1) + type + ':' + action.substring(pos + 1, action.length);
}

function sw_refresh(data, set) {
	$('#sw_msg').html(data.getElementsByTagName('sw_msg')[0].childNodes[0].nodeValue);
	$('#sw_ajax').html(data.getElementsByTagName('sw_ajax')[0].childNodes[0].nodeValue);
	
	if (data.getElementsByTagName('sw_append') && data.getElementsByTagName('sw_append').length) {
        var append = $(data.getElementsByTagName('sw_append')[0].childNodes[0].nodeValue);
		append.hide();
		append.appendTo('#'+data.getElementsByTagName('sw_append')[0].getAttribute('to'));
		append.show();
	} else {
		$('#sw_body').html(data.getElementsByTagName('sw_body')[0].childNodes[0].nodeValue);
	}
	
	sw_showmsg();
	
	$('#sw_ajax').animate({
		opacity: 1
 	}, 'fast');
}

function sw_hidemsg() {
	if (sw_msg) {
		$('#sw_msg').animate({
			opacity: 0
	 	}, 'fast');
		
		sw_msg = false;
	}
}

function sw_showmsg() {
	if (!sw_msg) {
		$('#sw_msg').animate({
			opacity: 1
	 	}, 'fast');
		
		sw_msg = true;
	}
}

var sw_msg = true;
//$(document).click(sw_hidemsg);

function file_name(id) {
	$('#'+id+'_name').html($('#'+id).attr('value'));
}

function swfu_try(button, onComplete) {
    var inputs = $(button.form).find('.required');
    var failed = false;
    for (var i = 0; i < inputs.length; i++) {                    
        if (!$(inputs[i]).val()) {
            $(inputs[i]).addClass('error');
            failed = true;
        } else {
            $(inputs[i]).removeClass('error');
        }
    }

    var inputs = $(button.form).find('.check_required');
    for (var i = 0; i < inputs.length; i++) {
		if (!$(inputs[i]).find(':checkbox').attr('checked')) {
            $(inputs[i]).addClass('check_error');
            failed = true;
        } else {
            $(inputs[i]).removeClass('check_error');
        }
    }

    var inputs = $(button.form).find('.file_required');
    for (var i = 0; i < inputs.length; i++) {
		
		id = $(inputs[i]).find('.swf_file_input').attr('id');
		id = id.replace("div_", "swfu_");
		
		if (eval(id + '.enabled')) {
			if (!eval(id + '.queued')) {
	            $(inputs[i]).addClass('file_error');
	            failed = true;
	        } else {
	            $(inputs[i]).removeClass('file_error');
	        }
		} else {
			id = $(inputs[i]).find('.swf_file_input').attr('id') + "_degraded";
			if (!$('#' + id).find(':file').val()) {
	            $(inputs[i]).addClass('file_error');
	            failed = true;
	        } else {
	            $(inputs[i]).removeClass('file_error');
	        }
		}
    }

    if (failed) {
		alert("You're missing required fields. Please check and try again.");
        return false;
    }
    
	var files = $(button.form).find('.swf_file_input');
	
	var swfued = false;
	for (var i = 0; i < files.length; i++) {
		try {
			var swfu = eval((files[i].id).replace('div_','swfu_'));
			swfu.startUpload(onComplete, button.form);
			
			var inputs = $(button.form).find(':text');
			
			for (var x = 0; x < inputs.length; x++) {
				var input = $(inputs[i]);
				input.css('display','none');
				input.after('<div class="input_text">' + input.val() + '</div>');
			}
			
			$(button).after("<div class='uploading'>Uploading...</div>");
			$(button).hide();
			
			swfued = true;
		} catch (e) {
			
		}
	}
	
	if (swfued) {
		return false;
	} else {
		return onComplete();
	}
}

var cur_song = false;

function play_track(artist, song, secret) {
	if (cur_song) {
		stop_track(cur_song);
	}
	
	cur_song = song;
		
	$('#stop_'+song).css('display', 'block');
	$('#play_'+song).css('display', 'none');

	$('.stop_'+song).css('display', 'block');
	$('.play_'+song).css('display', 'none');

	if (secret) {
		$fl('sw_player').setTrack(artist, secret);
	} else {
		$fl('sw_player').setTrack(artist, song);
	}
}

function stop_track(song) {     
    $('#play_'+song).css('display', 'block');
	$('#stop_'+song).css('display', 'none');

	$('.play_'+song).css('display', 'block');
	$('.stop_'+song).css('display', 'none');

	$fl('sw_player').stopTrack();
}

function light_box(url) {
	$("#lb_content").html('Loading')
	
	open_lb();

	$.get(url, sw_lightbox);
	
	return false;
}

function sw_lightbox(data, set) {	
	$('#lb_msg').html(data.getElementsByTagName('lb_msg')[0].childNodes[0].nodeValue);
	$('#lb_content').html(data.getElementsByTagName('lb_html')[0].childNodes[0].nodeValue);

	if (data.getElementsByTagName('lb_script')) {
		for (var i = 0; i < data.getElementsByTagName('lb_script').length; i++) {
			eval(data.getElementsByTagName('lb_script')[i].childNodes[0].nodeValue);
		}
	}
	
	open_lb();
}

function sw_lbmsg(msg) {
    $('#lb_content').html(msg);
    
    $('body').click(close_light_box);

	open_lb();
}

function close_light_box() {
	$("#lb_msg").slideUp('fast', function() { $("#lb_msg").html(''); $("#lb_msg").slideDown(); })
	//$("#lb_content").html('');
	
	close_lb();
}

function cart_show() {
	$('#checkout_btn').css('display','block');
}

function logout_show() {
	$('#login').css('display','none');
	$('#logout').css('display','block');
}

function lb_submit(form) {
	var data = $('#'+form+' :input').serialize();
	var host = sw_replace_action('ajal', $('#'+form).attr('action'));
	$.post(host, data, sw_lightbox);
	
	return false;
}

function lb_href() {
	return light_box(sw_replace_action('ajal', this.href));
}

function lb_links() {
	$('a.lbox').click(lb_href);
}

function ipe(field) {
	var host = sw_replace_action('ajax', $('#form_' + field).attr('action'));
	var data = $('#form_' + field + ' :input').serialize();
	$.post(host, data, function(data) { inplace_update(data, $('#form_' + field).parent())});
	
	return false;
}

function inplace_editor(url, obj) {
	$.get(url, function(data) { inplace_update(data, obj.parentNode) });
	return false;
}

function inplace_update(data, obj) {
	$(obj).html(data.getElementsByTagName('sw_body')[0].childNodes[0].nodeValue);
}

function ipe_href() {
	return inplace_editor(sw_replace_action('ajax', this.href), this);
}

function ipe_link(link) {
	return inplace_editor(sw_replace_action('ajax', link.href), link);
}

function ipe_links() {
	$('a.ipe').click(ipe_href);
}

function do_music(stage) {
	map_overlay_close();
	
	switch (stage) {
		case 'listing':
			$('#m_menu_listing').attr('className', 'm_menu_listing_sel');
			$('#m_menu_explorer').attr('className', 'm_menu_explorer');
			$('#m_bar').html('<img src="/_img/music_bar_spread.gif" />');
			$('#m_content_listing').show();
			$('#m_content_explorer').hide();
		break;
		
		case 'explorer':
			$('#m_menu_listing').attr('className', 'm_menu_listing');
			$('#m_menu_explorer').attr('className', 'm_menu_explorer_sel');
			$('#m_bar').html('<img src="/_img/music_bar_explorer.gif" />');
			$('#m_content_listing').hide();
			$('#m_content_explorer').show();
		break;
	}
	
	$('#m_menu_' . stage).blur();
	
	return false;
}

var graph_drag = false;
var graph_int_x = null;
var graph_int_y = null;
var graph_my_x = null;
var graph_my_y = null;
function graph_startdrag(e) {
	graph_drag = true;

	var evt = e || window.event;
	graph_int_x = evt.clientX;
	graph_int_y = evt.clientY;

	graph_my_x = parseInt($('#graph').css('left'));
	graph_my_y = parseInt($('#graph').css('top'));
}

function graph_stopdrag(e) {
	graph_drag = false;
}

function graph_mousemove(e) {
	if (graph_drag) {
		var evt = e || window.event;
		delt_x = graph_int_x - evt.clientX;
		delt_y = graph_int_y - evt.clientY;
		
		document.getElementById("graph").style.top = (graph_my_y - delt_y) + "px";
		document.getElementById("graph").style.left = (graph_my_x - delt_x) + "px";
	}
}

var widget_old_link = '';
var widget_link_base = '';    
var widget_swf_base = '';
function refresh_iframe() {
    var type = $('#iframe_type').val();
	var bg = $('#iframe_color').val();
	var css = $('#iframe_css').val();
    var width = $('#embed_width').val();
    var height = $('#embed_height').val();

	$('#embed_width').removeAttr('disabled');
	$('#embed_height').removeAttr('disabled');
	$('#embed_code').removeAttr('disabled');
	
	$('#embed_api').hide();
	
    if (type == 'swf') {            
        var code = '<object width="'+width+'" height="'+height+'"><param name="movie" value="'+widget_swf_base+'"></param><param name="SCALE" value="noscale" /><embed src="'+widget_swf_base+'" type="application/x-shockwave-flash" width="'+width+'" height="'+height+'" scale="noscale"></embed></object>';
        
        $('#swf_code').val(code);

        $('#embed_swf').show();
        $('#embed_frame').hide();

        $('#frame_options').hide();
        
        $('#swf_code').show();
        $('#embed_code').hide(); 
    } else if (type == 'iframe') {
        var link = widget_link_base + ';bg:' + bg.replace("#", "") + ';css:' + css.replace("http://", "");
	    
	    if (link != widget_old_link) {
		    widget_old_link = link;
            
            width = (parseInt(width) == width) ? width + 'px' : width;
            height = (parseInt(height) == height) ? height + 'px' : height;
		    
			var frame_width = $('#embed_frame > iframe').css('width');
			var frame_height = $('#embed_frame > iframe').css('height');
			
		    var iframe = '<iframe src="' + link + '" style="width: '+frame_width+'; height: '+frame_height+'; border: 3px solid #777;" hspace="0" vspace="0"></iframe>';
		    var code = '<iframe src="' + link + '" style="width: '+width+'; height: '+height+'; border: none;" hspace="0" vspace="0"></iframe>';
	    
		    $('#embed_frame').html(iframe);
		    $('#embed_code').val(code);
	    } 
        
        $('#embed_swf').hide();
        $('#embed_frame').show();
        
        $('#frame_options').show();
        
        $('#swf_code').hide();
        $('#embed_code').show();
    } else {
       	$('#embed_swf').hide();
    	$('#embed_frame').hide();
 		$('#frame_options').hide();
 		$('#swf_code').hide();
		$('#embed_code').show();
		$('#embed_code').val('N/A');
 		$('#embed_code').attr('disabled','true'); 
		$('#embed_width').val('N/A');
		$('#embed_width').attr('disabled','true'); 
		$('#embed_height').val('N/A');
		$('#embed_height').attr('disabled','true'); 
		
		$('#embed_api').show();
	}
}

function frm_notes() {
    $('input,select').focus(function() {
            $(this.parentNode).find('.form_note').show();
        }).mouseover(function() {
			$(this.parentNode).find('.form_note').show();
		}).blur(function() {
            $(this.parentNode).find('.form_note').hide();
        }).mouseout(function() {
			$(this.parentNode).find('.form_note').hide();
		});
}