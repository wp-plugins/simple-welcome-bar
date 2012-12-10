<?php
/**
 * @package Simple_Welcome_Bar
 * @version 1.1
 */
/*
Plugin Name: Simple Welcome Bar
Plugin URI: http://wordpress.org/extend/plugins/simple-welcome-bar/
Description: Dropdown Bar for first time visitors and special promotions.
Author: Robert Lane
Version: 1.1
Author URI: http://profiles.wordpress.org/robertlane
*/

class welcomebar {

// --  Call the actions using class-function default function  --  //

public function welcomebar() {
    add_action( 'wp_enqueue_scripts', array( $this, 'js_scripts' ) );
    add_action( 'admin_print_scripts-settings_page_welcomebar', array( $this, 'admin_scripts' ) );
    add_action( 'wp_head', array( $this, 'modCSS' ) );
    add_action( 'wp_footer', array( $this, 'html_layout' ) );
    add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    add_action( 'admin_init', array( $this, 'welcomebar_init' ) );
    register_activation_hook(__FILE__, array( $this, 'add_defaults') );
    add_filter( 'plugin_action_links', array( $this, 'welcomebar_plugin_action_links' ) , 10, 2 );
}

function js_scripts() {
	$options = get_option('welcomebar');
	?>
	<script>var swbCookieExpire = <?php echo $options['cookie_expire'];?></script>
	<?php
    wp_enqueue_script('welcome-bar-cookie', plugin_dir_url(__file__).'js/wordpresswelcomebar.js', array('jquery'), null);
}

public function admin_scripts() {
	wp_enqueue_style('simple-welcome-bar-admin-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/base/jquery-ui.css', false );
	wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('farbtastic');
    wp_enqueue_script('farbtastic');
}

//  --  Page Layout  --  //

public function modCSS() {
	$options = get_option('welcomebar');
	?>
	
<style type='text/css'>

.welcomebar {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: <?php echo $options["height"];?>;
	background-color: <?php echo $options["bg_color"];?>;
	border-bottom:4px solid #fff;
	margin: 0;
	margin-bottom: -10px;	
	padding: 10px;
	z-index: <?php echo $options["z_index"];?>;
}
.welcomebar p {
	text-align: center;	
	font-family: <?php echo $options["font_family"];?>;
	color: <?php echo $options["font_color"];?>;
}
a.wb-cta  {
	background: <?php echo $options["color_button_background"];?>;
	border: 1px solid <?php echo $options["color_button_border"];?>;
	border-radius: 3px;
	-o-border-radius: 3px;
	-ms-border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	padding: 4px 14px;
	font-family: Impact, "Arial Black", sans-serif;
	font-size: .9em;	
	text-transform: uppercase;
	font-weight: 100;
	letter-spacing: 1px;
	color: <?php echo $options["color_button_text"];?>;
	text-decoration: none;
	text-shadow: 0px 1px 0px rgba(0,0,0,.1);
	-o-text-shadow: 0px 1px 0px rgba(0,0,0,.1);
	-ms-text-shadow: 0px 1px 0px rgba(0,0,0,.1);
	-moz-text-shadow: 0px 1px 0px rgba(0,0,0,.1);
	-webkit-text-shadow: 0px 1px 0px rgba(0,0,0,.1);
	box-shadow: 0px 1px 0px rgba(0, 0, 0, .08);
	-o-box-shadow: 0px 1px 0px rgba(0, 0, 0, .08);
	-ms-box-shadow: 0px 1px 0px rgba(0, 0, 0, .08);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, .08);
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, .08);
	margin-left: 5px;
}
a.wb-cta:hover {
	background: <?php echo $options["color_button_background_hover"];?>; 
	border: 1px solid <?php echo $options["color_button_border_hover"];?>;
	border-radius: 3px;
	-o-border-radius: 3px;
	-ms-border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	font-family: Impact, "Arial Black", sans-serif;
	font-size: .9em;	
	text-transform: uppercase;
	font-weight: 100;
	letter-spacing: 1px;
	color: <?php echo $options["color_button_text_hover"];?>;
	text-decoration: none;
}
a.anchor-relative {
	position: relative;
	width: 1.2em;
	height: 1.2em;
}

a.wb-close {
	font-family: Helvetica , "Arial", sans-serif;
	font-size: .7em;
	
	box-sizing: border-box;
	width: 17px;
	height: 18px;
	
	background: rgba(0, 0, 0, .10);
	
	border: 1px solid rgba(0, 0, 0, .15);
	border-radius:4px;
	color: rgba(0, 0, 0, .30);
	position: fixed;
	top:10;
	right:5px;
	
	
}
a.wb-close:hover {
	color:rgba(0,0,0,.6);
	text-decoration: none;
}

.wb-spacer {
	top: 0;
	height: <?php echo $options["height"];?>;
}
</style>
<?php
	
}

public function html_layout() {
    $options = get_option('welcomebar');
    
    $showButton = '';

    if ($options['button'] && $options["button"] != ''){
		$showButton = '<a href="'.$options['link'].'"title="'.$options['link'].'" class="wb-cta">'.$options['button'].'</a>';
	}
    
    // Backslashes are for javascript compatability
    $layout = '<div id="welcomebar" class="welcomebar">\
		<p>'.$options['message'].'\
		'.$showButton.'\
		<a href="#" title="Close this." class="wb-close" id="wb-close">X</a>\
		</p></div>\
		</div><div class="wb-spacer"></div>';

    $output = '
<script type=\'text/javascript\'>
var layout = \''.$layout.'\';
jQuery(document).ready(jQuery(\'body\').prepend(layout));
</script>
	';
echo $output;
}

function admin_form() {
	?>
	<div class="wrap">
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Simple Welcome Bar Options Page</h2>
		<!-- Small Brief Dexcripton -->
		<p>Below are the settings to customize your Simple Welcome Bar</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('welcomebar_options'); ?>
			<?php $admin_options = get_option('welcomebar'); ?>

			<div id="tabs">
			<ul>
        		<li><a href="#tabs-1">Main Options</a></li>
        		<li><a href="#tabs-2">Main Bar Style</a></li>
        		<li><a href="#tabs-3">Button Style</a></li>
        		<li><a href="#tabs-4">Advanced Options</a>
    		</ul>
    		<div id="tabs-1">
			<table class="form-table">
				<!-- Text Area Control -->
				<tr>
					<th scope="row">Message</th>
					<td>
						<input type="text" size="64" name="welcomebar[message]" value="<?php echo $admin_options['message']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">A simple one line message to grab user attention.</span>
						
					</td>
				</tr>

		        <tr>
					<th scope="row">Button Text</th>
					<td>
						<input type="text" size="20" name="welcomebar[button]" value="<?php echo $admin_options['button']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">The text that appears on the button.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*Leave blank for no button (display only message)</em></span>
					</td>
				</tr>

        		<tr>
					<th scope="row">Link Address</th>
					<td>
						<input type="text" size="64" name="welcomebar[link]" value="<?php echo $admin_options['link']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">The Destination Link. Include http:// or https:// for external links.</span>
						<br /><span style="color:#666666;margin-left:2px;">For on site pages, leave out your main url. ex. /about</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is # (none)</em></span>
					</td>
				</tr>
			</table>
			</div>
			
			<div id="tabs-2">
			<h3>Welcome Bar Settings</h3>
			<p>These settings modify the appearance of the Welcome Bar.</p>
			    
			<table class="form-table">
       			<tr>
					<th scope="row">Text Color</th>
					<td>
						<input type="text" size="15" id="font_color" name="welcomebar[font_color]" value="<?php echo $admin_options['font_color']; ?>" />
						<br /><div id="font_colorpicker"></div></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of your message font. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #5b5a59</em></span>
					</td>
				</tr>

        		<tr>
					<th scope="row">Bar Background Color</th>
					<td>
						<input type="text" size="15" id="bg_color" name="welcomebar[bg_color]" value="<?php echo $admin_options['bg_color']; ?>" />
						<br /><div id="bg_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of your message font. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #ededed</em></span>
					</td>
				</tr>
				
				<tr>
					<th scope="row">Font Family</th>
					<td>
						<select name="welcomebar[font_family]">
							<option value='inherited' <?php selected('inherited', $admin_options['font_family']); ?>>Inherited</option>
							<option value='' disabled='disabled' <?php selected('', $admin_options['font_family']); ?>>------</option>
							<option value='Arial, Sans-serif' <?php selected('Arial, Sans-serif', $admin_options['font_family']); ?>>Arial</option>
							<option value='Verdana, Sans-serif' <?php selected('Verdana, Sans-serif', $admin_options['font_family']); ?>>Verdana</option>
							<option value='Georgia, Serif' <?php selected('Georgia, Serif', $admin_options['font_family']); ?>>Georgia</option>
							<option value='"Times New Roman", Times, Serif' <?php selected('"Times New Roman", Times, Serif', $admin_options['font_family']); ?>>Time New Roman</option>
							<option value='"Courier New", Monospace' <?php selected('"Courier New", Monospace', $admin_options['font_family']); ?>>Courier New</option>
							<option value='"Lucida Console", Monospace' <?php selected('"Lucida Console", Monospace', $admin_options['font_family']); ?>>Lucida Console</option>
						</select>
						<br />
						<span style="color:#666666;margin-left:2px;">Font Choices. Inherited will try to match your theme.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is Georgia</em></span>
					</td>
				</tr>
				
				<tr>
					<th scope="row">Bar Height</th>
					<td>
						<input type="text" size="64" name="welcomebar[height]" value="<?php echo $admin_options['height']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">Set the height of the Bar.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is 30px</em></span>
					</td>
				</tr>
				
			</table>
			</div>
			
			<div id="tabs-3">
			<h3>Button Settings</h3>
			<p>These settings modify the appearance of the button on rest and on hover.</p>
			    
			<table class="form-table">
       			<tr>
					<th scope="row">Button Background Color</th>
					<td>
						<input type="text" size="15" id="color_button_background" name="welcomebar[color_button_background]" value="<?php echo $admin_options['color_button_background']; ?>" />
						<br /><div id="button_background_colorpicker"></div></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of the button. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #FC0</em></span>
					</td>
				</tr>

        		<tr>
					<th scope="row">Button Border Color</th>
					<td>
						<input type="text" size="15" id="color_button_border" name="welcomebar[color_button_border]" value="<?php echo $admin_options['color_button_border']; ?>" />
						<br /><div id="button_border_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">The color of the border. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #D9AD00</em></span>
					</td>
				</tr>
				
        		<tr>
					<th scope="row">Button Text Color</th>
					<td>
						<input type="text" size="15" id="color_button_text" name="welcomebar[color_button_text]" value="<?php echo $admin_options['color_button_text']; ?>" />
						<br /><div id="button_text_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">The color of the text. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #916706</em></span>
					</td>
				</tr>
				
       			<tr>
					<th scope="row">Button Background Color on Hover</th>
					<td>
						<input type="text" size="15" id="color_button_background_hover" name="welcomebar[color_button_background_hover]" value="<?php echo $admin_options['color_button_background_hover']; ?>" />
						<br /><div id="button_background_hover_colorpicker"></div></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of the button on hover. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #8DC63F</em></span>
					</td>
				</tr>

        		<tr>
					<th scope="row">Button Border Color on Hover</th>
					<td>
						<input type="text" size="15" id="color_button_border_hover" name="welcomebar[color_button_border_hover]" value="<?php echo $admin_options['color_button_border_hover']; ?>" />
						<br /><div id="button_border_hover_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">The color of the border on hover. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #75B443</em></span>
					</td>
				</tr>
				
        		<tr>
					<th scope="row">Button Text Color on Hover</th>
					<td>
						<input type="text" size="15" id="color_button_text_hover" name="welcomebar[color_button_text_hover]" value="<?php echo $admin_options['color_button_text_hover']; ?>" />
						<br /><div id="button_text_hover_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">The color of the text on hover. Keyword not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #FFF</em></span>
					</td>
				</tr>				
			</table>
			</div>
			
			<div id="tabs-4">
			<h3>Advanced Options</h3>
			<p>These settings modify other settings for the plugin.</p>

			<table class="form-table">
				<tr>
					<th scope="row">Cookie Expire</th>
					<td>
						<input type="text" size="64" name="welcomebar[cookie_expire]" value="<?php echo $admin_options['cookie_expire']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">Change when the cookie will expire and the bar re-appears.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is 604800000 (7 days in nano seconds)</em></span>
					</td>
				</tr>
				<tr>
					<th scope="row">Z-Index Fix</th>
					<td>
						<input type="text" size="64" name="welcomebar[z_index]" value="<?php echo $admin_options['z_index']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">Setting this value higher may correct placement errors with some themes.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is 10000</em></span>
					</td>
				</tr>
				
			</table>
			</div>
			
			</div><!-- end of tabs -->
			<p class="submit">
			  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	  <!-- Scripts that only matter on this page -->
    <script type="text/javascript">
      jQuery('#font_color').focus(function() {
        jQuery('#font_colorpicker').slideDown('slow');
      });
      jQuery('#font_color').blur(function() {
        jQuery('#font_colorpicker').slideUp('slow');
      });
      jQuery('#bg_color').focus(function() {
        jQuery('#bg_colorpicker').slideDown('slow');
      });
      jQuery('#bg_color').blur(function() {
        jQuery('#bg_colorpicker').slideUp('slow');
      });
	  jQuery('#color_button_border').focus(function() {
        jQuery('#button_border_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_border').blur(function() {
        jQuery('#button_border_colorpicker').slideUp('slow');
      });
	  jQuery('#color_button_border_hover').focus(function() {
        jQuery('#button_border_hover_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_border_hover').blur(function() {
        jQuery('#button_border_hover_colorpicker').slideUp('slow');
      });	  
	  jQuery('#color_button_background').focus(function() {
        jQuery('#button_background_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_background').blur(function() {
        jQuery('#button_background_colorpicker').slideUp('slow');
      });	  
	  jQuery('#color_button_background_hover').focus(function() {
        jQuery('#button_background_hover_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_background_hover').blur(function() {
        jQuery('#button_background_hover_colorpicker').slideUp('slow');
      });	  	  
	  jQuery('#color_button_text').focus(function() {
        jQuery('#button_text_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_text').blur(function() {
        jQuery('#button_text_colorpicker').slideUp('slow');
      });	  	  
	  jQuery('#color_button_text_hover').focus(function() {
        jQuery('#button_text_hover_colorpicker').slideDown('slow');
      });
      jQuery('#color_button_text_hover').blur(function() {
        jQuery('#button_text_hover_colorpicker').slideUp('slow');
      });	  	  
      jQuery(document).ready(function() {
        jQuery('#font_colorpicker').farbtastic('#font_color');
		jQuery('#bg_colorpicker').farbtastic('#bg_color');
		
		
        jQuery('#button_background_colorpicker').farbtastic('#color_button_background');
		jQuery('#button_background_hover_colorpicker').farbtastic('#color_button_background_hover');
		jQuery('#button_border_colorpicker').farbtastic('#color_button_border');
		jQuery('#button_border_hover_colorpicker').farbtastic('#color_button_border_hover');
		jQuery('#button_text_colorpicker').farbtastic('#color_button_text');
		jQuery('#button_text_hover_colorpicker').farbtastic('#color_button_text_hover');
		
		
        jQuery('#font_colorpicker').hide();
        jQuery('#bg_colorpicker').hide();
		jQuery('#button_background_colorpicker').hide();
		jQuery('#button_background_hover_colorpicker').hide();
		jQuery('#button_border_colorpicker').hide();
		jQuery('#button_border_hover_colorpicker').hide();
		jQuery('#button_text_colorpicker').hide();
		jQuery('#button_text_hover_colorpicker').hide();

		jQuery( '#tabs' ).tabs();
      });
    </script>

  </div>
<?php
}


static function add_defaults() {
    $defaults = array(
        "message" => "Here is a sample message.",
        "button" => "GO",
        "link" => "#",
        "font_color" => "#5b5a59",
        "bg_color" => "#ededed",
	    "font_family" => "Georgia, Serif",
		"z_index" => "10000",
		"color_button_background" => "#ffcc00",
		"color_button_border" => "#d9ad00",
		"color_button_background_hover" => "#8dc63f",
		"color_button_text" => "#916706",
		"color_button_border_hover" => "#75b443",
		"color_button_text_hover" => "#ffffff",
		"height" => "30px",
		"cookie_expire" => "604800000",
);
    add_option( 'welcomebar' , $defaults, '', 'yes');
}

function add_options_page() {
  add_submenu_page('options-general.php', 'Simple Welcome Bar Options Page', 'Simple Welcome Bar', 'manage_options', 'welcomebar', array($this, 'admin_form'));
}

function welcomebar_init(){
	register_setting( 'welcomebar_options', 'welcomebar');
}

// Add Settings Shortcut
function welcomebar_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="'.get_admin_url().'options-general.php?page=welcomebar">'.__('Settings').'</a>';
		array_push( $links, $settings_link );
	}

	return $links;
}


//Ends Class
}

// Create an instance of the class.
$wp_welcomebar = new welcomebar;

?>
