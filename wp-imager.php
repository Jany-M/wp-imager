<?php 

/**
 *	WP Imager
 *
 *	Description			Script for WordPress that provides resizing, output customization and image caching. Can be used inside or outside the loop. If used inside a loop, there is no need to use $exturl, as the script will automatically retrieve an image from the post, following a certain priority pattern: featured image if found, otherwise take one random image from the post. If used outside the loop for any image you want, then $exturl is obviously required.
 *	Released			29.01.2014
 *	Version				1.1
 *	License				GPL V3 - http://choosealicense.com/licenses/gpl-v3/
 *  External libs		TimThumb - http://code.google.com/p/timthumb/
 *
 *	Author:				Jany Martelli
 *	Author's Website:	http://www.shambix.com/
 *  Script url:			https://github.com/Jany-M/WP-Imager
 *  
 *  @Requirements
 *  The plugin needs the cache_img folder to reside in the root of your website.
 *  Inside the cache_img folder you must create a cache folder, that is writable (try to chmod it to 777 in case script cant write to it)
 *  Inside the cache_img folder you must place the TimThumb script tt.php (and the TimThum config file if you need it, but it's not required)
 *  This script isnt "wp plugin-ready" at the moment, so you must place it in your template, and then include it in your functions.php (eg. include('wp_imager.php');)
 *
 *  @Params
 *	$width		int		Size of width (no px) - 100	(default)
 *	$height		int		Size of height (no px) - 100 (default)
 *	$crop		int		Type of cropping to perform - 1 (default)
						0 =	Resize to Fit exactly specified dimensions (no cropping) 	
						1 =	Crop and resize to best fit the dimensions (default)
						2 =	Resize proportionally to fit entire image into specified dimensions, and add borders if required
						3 =	Resize proportionally adjusting size of scaled image so there are no borders gaps

 *	$class		string	class name/names to append to image - NULL (default)
 *	$link		bool	Wraps the image in HTML <a href="">img</a>, pointing to the image's post, with title attribute filled with post's title for better SEO. Wont' work with $exturl - false (default)
 *	$exturl		string	URL of some external image (eg. http://www.anothersite.com/image.jpg)
  *	$nohtml		bool	When false,images are wrapped already in their HTML tag <img src="" />, with alt attribute filled with post's title for better SEO. If true, only the image urlis returned - false (default)
 * 
 *  @Defaults
 *	Function always returns to avoid yet another parameter, so simply echo it in your code.
 *  For now, cropping is always done in the middle, zooming in the center.
 *  Processed IMG's quality is always 100, but this is set through the .htaccess
 *	Caching is done in a cache_img folder, in the root of your website
 *  Pretty img urls are disabled by default. To enable it change $htaccess to true and adapt the .htaccess provided with the script.
 *
**/

function wp_imager($width=null, $height=null, $crop=null, $class='', $link=false, $exturl=null, $nohtml=false) {
	global $post;

	// Prepping stuff
	$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
	$thumb2part = str_replace(get_bloginfo('url'), '', $thumbnail[0]);
	$attachments = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'rand', 'numberposts' => 1) );
	
	// Defaults
	$htaccess = false; // switch to true if you want pretty img urls -> edit htaccess as well though
	if(!isset($width) || is_null($width) || $width == '') $width = '100';
	if(!isset($height) || is_null($height) || $width == '') $height = '100';
	if(!isset($crop) || is_null($crop) || $crop == '') $crop = '1';
	if($class !== '') $printclass = 'class="'.$class.'" ';
	$exturl = str_replace(get_bloginfo('url').'/', '', $exturl);

	// External image URL
	if ($exturl) {
		if ($nohtml) {
			$output = ''.get_bloginfo('url').'/cache_img/tt.php?src='.$exturl.'&w='.$width.'&h='.$height.'&zc='.$crop.'&q=100';			
		} else {
			$output = '<img src="'.get_bloginfo('url').'/cache_img/tt.php?src='.$exturl.'&w='.$width.'&h='.$height.'&zc='.$crop.'&q=100" '.$printclass.'/>';
		}
		return $output;
	
	// WP featured img
	} elseif (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
		if ($nohtml) {
			if ($htaccess) {
				$output = ''.get_bloginfo('url').'/cache_img/r/'.$width.'x'.$height.'-'.$crop.'/i'.$thumb2part.'';
			} else {
				$output = ''.get_bloginfo('url').'/cache_img/tt.php?src='.$thumb2part.'&w='.$width.'&h='.$height.'&zc='.$crop.'&q=100';
			}
		} else {
			if($link) $output .= '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">';
			if ($htaccess) {
				$output .= '<img src="'.get_bloginfo('url').'/cache_img/r/'.$width.'x'.$height.'-'.$crop.'/i'.$thumb2part.'" alt="'.$post->post_title.'" '.$printclass.' />';
			} else {
				$output .= '<img src="'.get_bloginfo('url').'/cache_img/tt.php?src='.$thumb2part.'&w='.$width.'&h='.$height.'&zc='.$crop.'&q=100" alt="'.$post->post_title.'" '.$printclass.' />';
			}
			if($link !== '') $output .= '</a>';
		}
		return $output;
	
	// WP post attachments
	} elseif ($attachments == true) {
		foreach($attachments as $id => $attachment) {
			$img = wp_get_attachment_image_src($id, 'full');
			$img_url = parse_url($img[0], PHP_URL_PATH);
			$img2part = str_replace(get_bloginfo('url'), '', $img_url);
			if ($nohtml) {
				if ($htaccess) {
					$output = ''.get_bloginfo('url').'/cache_img/r/'.$width.'x'.$height.'-'.$crop.'/i'.$img2part.'';
				} else {
					$output = ''.get_bloginfo('url').'/cache_img/tt.php?src='.$img2part.'$w='.$width.'&h='.$height.'&zc='.$crop.'&q=100';
				}
			} else {
				if($link) $output .= '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">';
				if ($htaccess) {
					$output .='<img src="'.get_bloginfo('url').'/cache_img/r/'.$width.'x'.$height.'-'.$crop.'/i'.$img2part.'" alt="'.$post->post_title.'" '.$printclass.' />';
				} else {
					$output .='<img src="'.get_bloginfo('url').'/cache_img/tt.php?src='.$img2part.'$w='.$width.'&h='.$height.'&zc='.$crop.'&q=100" alt="'.$post->post_title.'" '.$printclass.' />';
				}
				if($link) $output .= '</a>';
			}
			return $output;
			break;
		}
	} 	
}
?>