<?php
/**
 * Plugin Name: Grisha's GPlus Gallery Shortcode
 * Plugin URI: http://google.com/+GrigoryMetlenko
 * Description: Use shortcode to add Google Photos gallery to post or page. [gplus-gallery user="#" album="#"]
 * Version: 4.3.1.2
 * Author: Grigory Metlenko
 * Author URI: http://google.com/+GrigoryMetlenko
 * License: GPL2
*/

/* [gplus-gallery] shortcode */
add_shortcode('gplus-gallery','wpgrisha_gplus_gallery_shortcode');
function wpgrisha_gplus_gallery_shortcode($atts){
	if ((is_int($atts['user']) || ctype_digit($atts['user'])) && (int)$atts['user'] > 0 ) {
		if ((is_int($atts['album']) || ctype_digit($atts['album'])) && (int)$atts['album'] > 0 ) { 
			$wpgrishaGplusgallery = "https://picasaweb.google.com/data/feed/api/user/".$atts['user']."/albumid/".$atts['album']."?kind=photo&imgmax=1280u&thumbsize=150c";
			if ($wpgrishaGplusgallery) 
			{	$sxmlget = wp_remote_retrieve_body(wp_remote_get($wpgrishaGplusgallery));
				$sxml = simplexml_load_string($sxmlget);
				if ($sxml) {
					$grishahtml = "<div class=\"gplus-gallery\" id=\"gplus-gallery-".$atts['album']."\">";
					foreach ($sxml->entry as $entry) {
						$gphoto = $entry->children('http://schemas.google.com/photos/2007');	
						$media = $entry->children('http://search.yahoo.com/mrss/');
						$mediaa = $media->group->content[0];
						$summary = $entry->summary;
						$fullurl = $mediaa->attributes()->{'url'};
						$thumbnail = $media->group->thumbnail[0];
						$thumburl = $thumbnail->attributes()->{'url'};
						$grishahtml .= "<a href=\"".$fullurl."\" data-fancybox-group=\"gal-".$atts['album']."\" data-rel=\"fancybox\" title=\"$summary\"><img class=\"alignleft\" src=\"".$thumburl."\" alt=\"\" width=\"150\" height=\"150\" /></a>";
					}
					$grishahtml .= '</div>';
				}
				else $grishahtml = "Error: xml feed empty";
			}
			else {$grishahtml = "[Gallery Error - try reloading the page]";}
		}
		else {$grishahtml = "[Gallery Error - album should be a number]";}
	}
	else {$grishahtml = "[Gallery Error - user should be a number]";}
	return $grishahtml;
}
?>