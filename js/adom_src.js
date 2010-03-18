/*--------------------------------------------------------------------------- 
 *	ADOM: Asynchronous Document Object Model
 *	v. 0.1
 *	(c) 2007 Lewis Zimmerman (lzimm.com)
 *--------------------------------------------------------------------------*/

if (typeof DOMParser == "undefined") {
   DOMParser = function () {}

   DOMParser.prototype.parseFromString = function (str, contentType) {
      if (typeof ActiveXObject != "undefined") {
         var d = new ActiveXObject("MSXML.DomDocument");
         d.loadXML(str);
         return d;
      } else if (typeof XMLHttpRequest != "undefined") {
         var req = new XMLHttpRequest;
         req.open("GET", "data:" + (contentType || "application/xml") +
                         ";charset=utf-8," + encodeURIComponent(str), false);
         if (req.overrideMimeType) {
            req.overrideMimeType(contentType);
         }
         req.send(null);
         return req.responseXML;
      }
   }
}

$ = function(name) {
	var elem = null;
	if (typeof name == 'string') {
		if (document.getElementById(name)) {
			elem = document.getElementById(name);
			
			if (!elem.adom) {
				for (prop in ADOM)
					elem[prop] = ADOM[prop];
				
				elem.enable_element();
			}
		} else {
			elem = name;
		}
	} else {
		if (name == null)
			name = new Object();
			
		elem = name;
		
		if (!elem.adom) {
			for (prop in ADOM)
					elem[prop] = ADOM[prop];
			
			elem.enable_ajax();
		}
	}
	
	if (elem && typeof elem != 'string') {
		return elem;
	} else return false;
}

ADOM = {
	enable_element : function() {
		if (!this.adom_element)
			for (prop in ADOM_Element)
				this[prop] = ADOM_Element[prop];
	},
	
	enable_ajax : function() {
		if (!this.adom_ajax)
			for (prop in ADOM_Ajax)
				this[prop] = ADOM_Ajax[prop];
	},
	
	adom : true
}

ADOM_Element = {
	show : function() {
		this.style.display = 'block';
	},
	
	hide : function() {
		this.style.display = 'none';
	},
	
	adom_element : true
}
	
ADOM_Ajax = {
	Create : function(elem) {			
		for (property in ADOM_Ajax) {
			elem[property] = ADOM_Ajax[property]
		}
		
		return elem;
	},

	Init : function(params, catcher) {
		if (this.params) {
			for (param in params) {
				this.params[param] = params[param];
			}
		} else {
			this.params = params;
		}
	},

	Request : function(params, catcher) {
		if (this.params) {
			for (param in params) {
				this.params[param] = params[param];
			}
		} else {
			this.params = params;
		}
		
		if (!this.params.uri)
			throw new Error('Unspecified URI for Request.');
			
		if (!this.params.method) 		this.params.method = 'GET';
		if (!this.params.vars) 		this.params.vars = '';
		if (!this.params.constant) 	this.params.constant = '';
		
		var constants = '';
		if (this.params.constant) {
			constants = ((this.params.vars)?'&':'') + this.params.constant;
		}
		
		this.req = ADOM_Ajax._transport();
		
		var uri = this.params.uri;
		var vars = this.params.vars + constants;
		if (this.params.method == 'GET') {
			uri = this.params.uri + "?" + vars;
			vars = '';
		}
			
		this.req.open(this.params.method, uri, true);

		if (catcher) {
			this.req.onreadystatechange = catcher;
		} else {
			this.req.onreadystatechange = this._detatch(this._callback);
		}

		if (this.params.before)
			this.params.before();

		this.req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
		this.req.send(vars);
		
		if (this.params.after)
			this.params.after();
	},

	Update : function(params) {	
		this.Request(params, this._detatch(this._updatecallback));
	},
	
	_detatch : function(func) {
		var _this = this;
		return function() {
			func.apply(_this);
		}
	},

	_transport : function() {
		var transports = [
			function() {return new XMLHttpRequest()},
			function() {return new ActiveXObject('Msxml2.XMLHTTP')},
			function() {return new ActiveXObject('Microsoft.XMLHTTP')}
		];
		
		for(var i = 0; i < transports.length; i++) {
			try {
				return transports[i]();
			} catch (ex) {
			}
		}
	
		return false;
	},
	
	_callback : function() {
		try {
			if (this.req.status == 200) {
				if (this.req.readyState == 1) {
					if (this.params.onLoading)
						this.params.onLoading(this.req);
				}
				
				if (this.req.readyState == 2) {
					if (this.params.onLoaded)
						this.params.onLoaded(this.req);
				}
	
				if (this.req.readyState == 4) {
					var res = new Object();
					res.text = this.req.responseText;
					res.xml = (new DOMParser).parseFromString(res.text,"text/xml");
					res.responseText = res.text;
					res.responseXML = res.xml;
					
					if (this.params.onComplete)
						this.params.onComplete(res, this.req, this.params.callbackvars);
				}
			} else {
				if (this.params.onError)
					this.params.onError(this.req);
			}
		} catch (ex) {
		}
	},

	_updatecallback : function() {
		try {
			if (this.req.status == 200) {
				if (this.req.readyState == 1) {
					if (this.params.onLoading)
						this.params.onLoading(this.req);
				}
				
				if (this.req.readyState == 2) {
					if (this.params.onLoaded)
						this.params.onLoaded(this.req);
				}
	
				if (this.req.readyState == 4) {
					if (this.params.formatOutput) {
						var res = new Object();
						res.text = this.req.responseText;
						res.xml = (new DOMParser).parseFromString(res.text,"text/xml");
						res.responseText = res.text;
						res.responseXML = res.xml;
						
						this.innerHTML = this.params.formatOutput(res, this.req, this.params.callbackvars)
					} else {
						this.innerHTML = this.req.responseText;
					}
				}
			} else {
				if (this.params.onError)
					this.params.onError(this.req);
			}
		} catch (ex) {
		}
	},
	
	adom_ajax : true
}
