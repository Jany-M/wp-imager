<?php

$ALLOWED_SITES = array (
	'flickr.com',
	'staticflickr.com',
	'picasa.com',
	'img.youtube.com',
	'upload.wikimedia.org',
	'photobucket.com',
	'imgur.com',
	'imageshack.us',
	'tinypic.com',
	'amazonaws.com',
	'pinterest.com',
	'google.com',
	'maps.google.com',
);

define ('FILE_CACHE_MAX_FILE_AGE', 2592000); // 1 month 
define ('ALLOW_ALL_EXTERNAL_SITES', true);
define ('BLOCK_EXTERNAL_LEECHERS', true);
define ('FILE_CACHE_SUFFIX', '.tt.txt');
define ('FILE_CACHE_PREFIX', 'tt');
define ('DEFAULT_Q', 100);
define ('DEFAULT_WIDTH', 300);
define ('DEFAULT_HEIGHT', 300);
define ('DEFAULT_ZC', 1);
define ('PNG_IS_TRANSPARENT', TRUE);
define ('MAX_WIDTH', 1500);
define ('MAX_HEIGHT', 1500);
define ('DEFAULT_CC', 'ffffff');

?>
