function $fl(movie) {	
	var transports = [
		function() { return window.document[movie]; },
		function() { return document.embeds[movie]; },
		function() { return document.getElementById[movie]; }
	];
	
	var obj;
	
	for(var i = 0; i < transports.length; i++) {
		try {	
			return transports[i]();
		} catch (ex) {
		}
	}

	return false;
}

function set_track(artist, song) {
	$fl('sw_player').setTrack(artist, song);
	$('#play_'+song).hide();
	$('#stop_'+song).show();
}

function stop_track(song) {
	$fl('sw_player').stopTrack();
	$('#play_'+song).show();
	$('#stop_'+song).hide();
}

var songs = new Array();
var baseurl = '';
var sawce_user = '';

function sw_buy(id) {
	var button = document.getElementById('buy_' + id);
	button.className = 'disabled';
	
	var tmp_songs = new Array();
	for (i = 0; i < songs.length; i++) {
		if (songs[i] != id) {
			tmp_songs.push(songs[i]);
		} else {
			button.className = '';
		}
	}
	
	if (button.className) {
		tmp_songs.push(id);
	}
	
	songs = tmp_songs;
		
	var checkout = document.getElementById('sw_checkout');
	
	if (!baseurl) {
		baseurl = checkout.href.substr(0, checkout.href.lastIndexOf('/'));
		sawce_user = checkout.href.substr(checkout.href.lastIndexOf('/') + 1);
	}
	
	checkout.href = baseurl + '/';
	for (i = 0; i < songs.length; i++)
		if (i < (songs.length - 1)) {
			checkout.href += songs[i] + ',';
		} else {
			checkout.href += songs[i];
		}
	
	checkout.href += '/via/' + sawce_user;
	
	if (songs.length) {
		checkout.className = '';
		checkout.style.display = 'block';
	} else {
		checkout.className = 'disabled';
		checkout.style.display = 'none';
	}
	
	return false;
}