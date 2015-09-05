<?php 

/**
 *	WP Imager
 *
 *	Description			Script for WordPress that provides resizing, output customization and image caching. Supports Jetpack Photon. Can be used inside or outside the loop.
 *	First Release		29.01.2014
 *	Version				2.5
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
 *	$crop		int		Type of cropping to perform - 3 (default)
 *						0 =	Resize to Fit exactly specified dimensions (no cropping)
 *	 					1 = Crop and resize to best fit the dimensions
 *						2 =	Resize proportionally to fit entire image into specified dimensions, and add borders if required
 *						3 =	Resize proportionally adjusting size of scaled image so there are no borders gaps
 *
 *	$class		string	class name/names to append to image - NULL (default)
 *	$link		bool	Wraps the image in HTML <a href="">img</a>, pointing to the image's post, with title attribute filled with post's title for better SEO. Wont' work with $exturl - false (default)
 *	$exturl		string	URL of some external image (eg. http://www.anothersite.com/image.jpg)
 *	$nohtml		bool	When false,images are wrapped already in their HTML tag <img src="" />, with alt attribute filled with post's title for better SEO. If true, only the image urlis returned - false (default)
 *	$post_id	int 	If empty, will retrieve current loop post->ID, or you can add your own post ID value
 *	$bg_color	int		In case of different cropping type (eg. with borders) or transparent png, you can add your own color (eg. 000000) - ffffff (default)
 * 
 *  @Defaults
 *	Function always returns to avoid yet another parameter, so simply echo it in your code.
 *	Caching is done in a cache_img folder, in the root of your website, therefore this script requires your .htaccess to follow certain rules OR IT WONT WORK, that's why there is a .htaccess_sample file for you to use/adapt.
 *
**/

function wp_imager($width=null, $height=null, $crop, $class, $link=false, $exturl=null, $nohtml=false, $post_id=null, $bg_color=null) {
	
	$cache = 'cache_img';

	// Get Post ID
	global $post;
	if ($post_id == '') {
		$the_id = $post->ID;
		$the_content = $post->post_content;
		$the_title = $post->post_title;
	} else {
		$the_id = $post_id;
		$the_content = get_post_field('post_content', $post_id);
		$the_title = get_the_title($post_id);
	}

	// Is Photon on and working? 
	// https://developer.wordpress.com/docs/photon/api/
	if(class_exists('Jetpack') && Jetpack::is_module_active('photon') ) { // method as of WP/Jetpack versions after 05/22/13
		$photon = true;
		remove_filter('image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize'));
	} else {
		$photon = false;
	}
	if($photon && !function_exists('jetpack_photon_url' )) echo 'There is something wrong with your Jetpack / Photon module, or your server configuration - Make sure that your website is publicly reachable.';

	// Fix for site url lang edit (WPML)
	if (function_exists('icl_object_id')) {
		global $sitepress;
		$deflang = $sitepress->get_default_language();
		if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== $deflang) {
			$lang = ICL_LANGUAGE_CODE;
			$genurl = str_replace('/'.$lang.'/', '', get_bloginfo('url'));
			$exturl = str_replace(get_bloginfo('url').'/'.$lang.'/', '', $exturl);
		}
	} else {
		$genurl = get_bloginfo('url');
		$exturl = str_replace(get_bloginfo('url').'/', '', $exturl);
	}
	$siteurl = $genurl.'/'.$cache;

	// Defaults
	require_once (ABSPATH . $cache.'/tt-conf.php'); // Please adjust here your preferred TimThumb defaults

	$htaccess = true;

	if($width == '') {
		$width = DEFAULT_WIDTH;
	} else {
		$width_tt = '&w='.$width;
	}
	if($height == '') {
		$height = DEFAULT_HEIGHT;
	} else {
		$height_tt = '&h='.$height;
	}
	if($crop == '') {
		$crop = DEFAULT_ZC;
	} else {
		$crop_tt = '&zc='.$crop;
	}
	if($bg_color == '') {
		$bg_color = DEFAULT_CC;
	} else {
		$bg_color_tt = '&cc='.$bg_color.'&ct=0';
	}

	if($class !== '') $printclass = 'class="'.$class.'" ';
	$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'full');

	// Get attachs
	$attachments = get_children( array('post_parent' => $the_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'rand', 'numberposts' => 1) );

	// External image URL
	if ($exturl) {
		if ($nohtml) {
			$output = $siteurl.'/tt.php?src='.$exturl.$width_tt.$height_tt.$crop_tt.$bg_color_tt;			
		} else {
			$output = '<img src="'.$siteurl.'/tt.php?src='.$exturl.$width_tt.$height_tt.$crop_tt.$bg_color_tt.'" '.$printclass.'/>';
		}
		return $output;
	
	// WP featured img
	} elseif (function_exists('has_post_thumbnail') && has_post_thumbnail($the_id)) {
		// Fix for site url lang edit (WPML)
		if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== $deflang) {
			$thumb2part = str_replace(get_bloginfo('url').'/'.$lang.'/', '', $thumbnail[0]);
			$thumb2part = str_replace($genurl.'/', '', $thumb2part);		
		} else {
			$thumb2part = str_replace(get_bloginfo('url'), '', $thumbnail[0]);
		}
		// Fix for Photon
		if($photon) {
			$thumb2part = str_replace('http://','', $thumbnail[0]);
		}

		if ($nohtml) {
			if($photon) {
					$output = 'http://i1.wp.com/'.$thumb2part.'?resize='.$width.','.$height.'&amp;quality=100&amp;strip=all';
			} elseif ($htaccess) {
				$output = $siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$thumb2part;
			} else {
				$output = $siteurl.'/tt.php?src='.$thumb2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt;
			}
		} else {
			if($link) $output .= '<a href="'.get_permalink($the_id).'" title="'.$the_title.'">';
			if($photon) {
				$output .= '<img src="http://i1.wp.com/'.$thumb2part.'?resize='.$width.','.$height.'&amp;quality=100&amp;strip=all" alt="'.$the_title.'" '.$printclass.' />';
			} elseif ($htaccess) {
				$output .= '<img src="'.$siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$thumb2part.'" alt="'.$the_title.'" '.$printclass.' />';
			} else {
				$output .= '<img src="'.$siteurl.'/tt.php?src='.$thumb2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt.'" alt="'.$the_title.'" '.$printclass.' />';
			}
			if($link !== '') $output .= '</a>';
		}
		return $output;
	
	// WP post attachments
	} elseif ($attachments == true) {
		foreach($attachments as $id => $attachment) {
			$img = wp_get_attachment_image_src($id, 'full');
			$img_url = parse_url($img[0], PHP_URL_PATH);
			// Fix for site url lang edit (WPML)
			if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== $deflang) {
				$img2part = str_replace(get_bloginfo('url').'/'.$lang.'/', '', $img_url);
			} else {
				$img2part = str_replace(get_bloginfo('url'), '', $img_url);
			}
			// Fix for Photon
			if($photon) {
				$img2part = str_replace('http://','', $img_url);
			}
				
			if ($nohtml) {
				if($photon) {
					$output = 'http://i1.wp.com/'.$img2part.'?resize='.$width.','.$height.'&amp;quality=100&amp;strip=all';
				} elseif ($htaccess) {
					$output = $siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$img2part;
				} else {
					$output = ''.$siteurl.'/tt.php?src='.$img2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt;
				}
			} else {
				if($link) $output .= '<a href="'.get_permalink($the_id).'" title="'.$the_title.'">';
				if($photon) {
					$output .='<img src="http://i1.wp.com/'.$img2part.'?resize='.$width.','.$height.'&amp;quality=100&amp;strip=all" alt="'.$the_title.'" '.$printclass.' />';
				} elseif ($htaccess) {
					$output .='<img src="'.$siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$img2part.'" alt="'.$the_title.'" '.$printclass.' />';
				} else {
					$output .='<img src="'.$siteurl.'/tt.php?src='.$img2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt.'" alt="'.$the_title.'" '.$printclass.' />';
				}
				if($link) $output .= '</a>';
			}
			return $output;
			break;
		}

	// Post contains some image, not attached to post (external or added through some file manager)
	} else {
		$img_url = '';
  		ob_start();
  		ob_end_clean();
  		$search = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $the_content, $matches);
  		$img_url = $matches[1][0];
  		if(!empty($img_url)) {
		    //$first_img = "/path/to/default.png";
		    //return $first_img;

		    // Fix for site url lang edit (WPML)
			if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== $deflang) {
				$img2part = str_replace(get_bloginfo('url').'/'.$lang.'/', '', $img_url);
			} else {
				$img2part = str_replace(get_bloginfo('url'), '', $img_url);
			}

		    if ($nohtml) {
				if ($htaccess) {
					$output = $siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$img2part;
				} else {
					$output = ''.$siteurl.'/tt.php?src='.$img2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt;
				}
			} else {
				if($link) $output .= '<a href="'.get_permalink($the_id).'" title="'.$the_title.'">';
				if ($htaccess) {
					$output .='<img src="'.$siteurl.'/r/'.$width.'x'.$height.'-'.$crop.'/b/'.$bg_color.'/i/'.$img2part.'" alt="'.$the_title.'" '.$printclass.' />';
				} else {
					$output .='<img src="'.$siteurl.'/tt.php?src='.$img2part.$width_tt.$height_tt.$crop_tt.$bg_color_tt.'" alt="'.$the_title.'" '.$printclass.' />';
				}
				if($link) $output .= '</a>';
			}
			return $output;

		}
	}

	// Since Photon keeps changing automatically all urls, we had to disable it, let's reactivate it now
	if ( $photon ) {
		add_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ), 10, 3 ); 
	}
}
?>