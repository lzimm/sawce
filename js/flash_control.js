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