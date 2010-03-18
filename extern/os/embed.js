var r=[function(){ return new XMLHttpRequest(); },
		function(){ return new ActiveXObject("Msxml2.XMLHTTP"); },
		function(){ return new ActiveXObject("Microsoft.XMLHTTP"); }];
		   
for(var i in r) { try { var v=r[i],q=v(); } catch(e) { continue; } }
q.onreadystatechange=function(){ if(q.readyState==4 && q.status==200) handleSawce(q.responseText); };

function sw_init(id, container) { q.open('POST','http://os.sawce.net/handler.php?id=' + id + '&container=' + container); q.send(null); }
function getr(id) { return document.getElementById(id); }
function handleSawce(text) { getr("sawce_container").innerHTML = text; }
function swfu_try(a, b) { q.send('email=' + getr('email').value + '&pass=' + getr('pass').value); return false; }