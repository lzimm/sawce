<?php
	
	ini_set("mbstring.func_overload", 7);
	ini_set("session.cookie_domain", ".sawce.net");
	
	error_reporting(E_ALL ^ E_NOTICE);
	
	$GLOBALS['cfg']['secret'] = 'spreadsawce';
    
    $GLOBALS['cfg']['activation_period'] = 48;
	
	$GLOBALS['cfg']['dbhost'] = 'localhost';
	$GLOBALS['cfg']['dbuser'] = '';
	$GLOBALS['cfg']['dbpass'] = '';
	$GLOBALS['cfg']['dbname'] = 'sawce';
	
	$GLOBALS['cfg']['credit_pending'] = 60*60*24*60;
	
	$GLOBALS['cfg']['rates'] = array(
									'direct' => 0.3,
									'commission' => array(
										'artist' => 0.3,
										'seller' => 0.3
									),
									'tax' => 0.13
								);
	$GLOBALS['cfg']['fees'] = array(
									'transaction' => 0.35
								);
	
	$GLOBALS['public_pages'] = array('base', 'about', 'users', 'get', 'jar', 'work', 'spread', 'music', 'people', 'embed', 'tags');
	$GLOBALS['secure_pages'] = array('checkout', 'users');
	
	$GLOBALS['cfg']['basedir'] = $_SERVER['DOCUMENT_ROOT'] . '/';

	define("HTTP_LINK_BASE", "http://stage.sawce.net/");
	define("HTTPS_LINK_BASE", "https://secure.sawce.net/");
	define("AMAZON_SONG_BASE", "http://sawcesongs.s3.amazonaws.com/");
	
    define("PSIGATE_TRANSPORT_URL", "https://dev.psigate.com:7989/Messenger/XMLMessenger"); //testing
    // define("PSIGATE_TRANSPORT_URL", "https://secure.psigate.com:7934/Messenger/XMLMessenger"); //production
    define("PSIGATE_STORE_ID", "");
    define("PSIGATE_PASS_PHRASE", "");
    
    define('PP_API_USERNAME', '');
    define('PP_API_PASSWORD', '');
    define('PP_API_SIGNATURE', '');
    define('PP_API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');
    define('PP_USE_PROXY',FALSE);
    define('PP_PROXY_HOST', '127.0.0.1');
    define('PP_PROXY_PORT', '808');
    define('PP_PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
    define('PP_VERSION', '3.0');
    
	define("CTYPE_HTML", "html");
	define("CTYPE_HTML_AUTH", "html_auth");
	define("CTYPE_XML", "xml");
	define("CTYPE_AJAX", "ajax");
	define("CTYPE_AJAT", "ajat");
	define("CTYPE_AJAL", "ajal");
	define("CTYPE_AJAL_AUTH", "ajal_auth");
	define("CTYPE_AJ", "aj");
	define("CTYPE_SPREAD", "spread");
	define("CTYPE_GRAPH", "graph");
	define("CTYPE_FB", "fb");
	define("CTYPE_FB_AUTH", "fb_auth");
	
	define("UTYPE_FAN", "fan");
	define("UTYPE_ARTIST", "artist");
	define("UTYPE_ADMIN", "admin");
	
    define("FNOTE_USER_NAME", "Used for your URL, etc");
    define("FNOTE_DISPLAY_NAME", "How your name/band name will appear");
	define("FNOTE_SONG_COMMISSION", "How much of the sale to share with fans who promote you");
    
	define("ERR_INVALID_USER", "User does not exist");
	define("ERR_INUSE_USERNAME", "Username is already in use");
	define("ERR_INUSE_EMAIL", "Email is already in use");
	define("ERR_UNPURCHASED_SONG", "This song has not been purchased");
	define("ERR_UNRECORDED_LOYALTY", "Failed to record royalty");
	define("ERR_PASSWORD_MISMATCH", "Passwords do not match");
	define("ERR_NO_LOGIN", "You must be logged in before proceeding");
	define("ERR_DB_ERROR", "A database error has occured");
	define("ERR_LOGIN_FAILED", "Login failed");
    define("ERR_VERIFICATION_FAILED", "Verification failed");  
	define("ERR_VALIDATION", "There were problems validating your request");
	define("ERR_ARTIST_ONLY", "You must be an Artist to view this page");
	define("ERR_FILE_MOVE", "Error moving file");
	define("ERR_USER_PERMISSION", "You do not have proper permission to access this");
	define("ERR_ARTIST_PERMISSION", "You do not have proper permission to access this");
	define("ERR_ALBUM_PERMISSION", "You do not have proper permission to access this");
	define("ERR_SONG_PERMISSION", "You do not have proper permission to access this");
	define("ERR_MESSAGE_PERMISSION", "You do not have proper permission to access this");
	define("ERR_NO_KEY", "No key");
	define("ERR_NO_SONG_SELECTED", "No song selected");
	define("ERR_NO_ALBUM_SELECTED", "No album selected");
	define("ERR_NO_ARTIST_SELECTED", "No artist selected");
	define("ERR_NO_USER_SELECTED", "No user selected");
	define("ERR_NO_MESSAGE_SELECTED", "No message selected");
	define("ERR_NO_TAG_SELECTED", "No tag selected");
	define("ERR_STRUCT_NO_DEF_ALBUM", "No default album");
	define("ERR_INSUFFICIENT_FUNDS", "Not enough funds");
	define("ERR_SONG_REPURCHASE", "Song has already been purchased");
	define("ERR_SONG_RECART", "Song has already been added to cart");
	define("ERR_NO_USER_WITH_EMAIL", "No users found with this email");
	define("ERR_NO_AMOUNT_SPECIFIED", "No amount specified for checkout");
	define("ERR_BILLED_FAILURE", "There was an error processing your request");
	define("ERR_PP_CURL", "There was an error processing your request");
	define("ERR_PP_FAILURE", "There was an error processing your request");
	define("ERR_BILLED_INCOMPLETE", "Your account has been charged though there was an error updating our records and will be looked into shortly");
	define("ERR_PP_INCOMPLETE", "Your account has been withdrawn from though there was an error updating our records and will be looked into shortly");
	define("ERR_IMG_TYPE", "Unsupported Image Type");
	define("ERR_NOT_MP3", "MP3 Required");
    define("ERR_INVALID_ID3", "ID3 information does not match Song information");
    define("ERR_INVALID_SESSION", "Invalid Session ID");
    define("ERR_FILE_FAILURE_MISSING", "There was a failure locating the file you requested");

	define("ERR_ZIP_OPEN", "Could not open zip file");
	define("ERR_ZIP_EXTRACT", "Could not extract zip file");
	
	define("ERRTPL_MISSING_FIELD", "Missing required field: %s");
	define("ERRTPL_SONGS_NOT_PURCHASED", "Failed to purchase %s songs");
	define("ERRTPL_BILLED_FAILURE", "There was an error processing your request: %s");
	
	define("MSG_USER_REGISTER", "");
	define("MSG_USER_LOGIN", "");
    define("MSG_USER_VERIFIED", "");
	define("MSG_USER_LOGOUT", "");
	//define("MSG_USER_REGISTER", "You have successfully registered");
	//define("MSG_USER_LOGIN", "You have successfully logged in");
	//define("MSG_USER_LOGOUT", "You have successfully logged out");
	define("MSG_USER_EDIT", "Changes are successful");
	define("MSG_GROUP_JOIN", "You have requested to join a group and are now awaiting confirmation");
	define("MSG_GROUP_LEAVE", "You have left a group");
	define("MSG_STATUS_UPDATE", "Your status has been updated");
	define("MSG_SONG_UPLOAD", "Your song has been uploaded");
	define("MSG_ALBUM_CREATED", "Your album has been created");
	define("MSG_ALBUM_DELETED", "Your album has been deleted");
	define("MSG_SONG_DELETED", "Your song has been deleted");
	define("MSG_ALBUM_EDIT", "Changes are successful");
	define("MSG_SONG_EDIT", "Changes are successful");
	define("MSG_SONG_PURCHASED", "Song has been purchased");
	define("MSG_SONG_CART", "Song has been added to cart");
	define("MSG_EMAILED_PASSWORD", "Your password has been emailed to you");
	define("MSG_MESSAGE_SENT", "Your message has been sent");
	define("MSG_BILLED_SUCCESS", "You have been successfully billed and your balance has been updated");
	define("MSG_PP_SUCCESS", "You have been successfully withdrawn funds");
	define("MSG_SONG_GRANTED", "You have successfully given a song");
    define("MSG_MESSAGES_SENT", "Messages successfully sent");
	
	define("MSG_ZIP_COMPLETE", "Extraction complete");
    
    define("LBMSG_PROFILE_SPREAD", "Welcome to Sawce, a music distribution platform built to map and leverage social influence. Great artists can give songs to fans in hopes that they'll spread them, while great fans can become something by spreading music to all their friends.");
    define("LBMSG_ALBUM_SPREAD", "Welcome to Sawce, a music distribution platform built to map and leverage social influence. Great artists can give songs to fans in hopes that they'll spread them, while great fans can become something by spreading music to all their friends."); 
    define("LBMSG_ARTIST_SPREAD", "Welcome to Sawce, a music distribution platform built to map and leverage social influence. Great artists can give songs to fans in hopes that they'll spread them, while great fans can become something by spreading music to all their friends.");
    define("LBMSG_SONG_SPREAD", "Welcome to Sawce, a music distribution platform built to map and leverage social influence. Great artists can give songs to fans in hopes that they'll spread them, while great fans can become something by spreading music to all their friends.");
    define("LBMSG_ALBUM_EXTRACTING", "Extracting songs. This may take several minutes depending on server load."); 
    
    define("STPMSG_ALBUM_CREATED", "Your album has been created. Now you can add songs to it below by either uploading them one at a time, or by uploading a zip file of multiple mp3s all at once");
    define("STPMSG_SONG_UPLOAD", "Your song has been uploaded. Now you can define things such as the song price and amount to share with fans that help spread your music below"); 
    define("STPMSG_SONGS_EXTRACT", "Your song have been uploaded. Now you can define things such as the song price and amount to share with fans that help spread your music by selecting whichever ones you want to edit");
    
    define("STPMSG_USER_CREATE", "Thank you for registering. Sawce enables you to enjoy great music and help artists promote the songs you love");
    define("STPMSG_ARTIST_CREATE", "Thank you for registering. The first thing you should do as an artist is upload your songs so that you can distribute them and get fans involved in helping you promote them");
    
    define("STPMSG_SONG_BUY", "Thank you for purchasing this song. If you really love it, you can really get involved and help promote it, becoming an important part of the social influence map");
    define("STPMSG_PACK_BUY", "Thank you for purchasing these songs. If you really love them, you can get involved and help promote them, becoming an important part of the social influence map");
	
    define("STPMSG_SAWCE_ADD", "After adding songs to your Sawce mix, you can promote them by embedding Sawce widgets acorss the Internet");
    
	define("MSGTPL_MESSAGES_SENT", "Successfully sent %s messages");
	define("MSGTPL_SONGS_PURCHASED", "Purchased %s songs");
    
    define("EMLTPL_VERIFICATION_KEY", "Please verify your account by entering your key: %s at " . HTTP_LINK_BASE . "users/verify"); 
	
	define("HTML_LABEL_AGREE_TERMS", " I Agree to all <a href='".HTTP_LINK_BASE."about/terms'>Terms and Conditions</a>");
	
?>
