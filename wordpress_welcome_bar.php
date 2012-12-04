<?php
/**
 * @package Simple_Welcome_Bar
 * @version 1.0
 */
/*
Plugin Name: Simple Welcome Bar
Plugin URI: http://wordpress.org/extend/plugins/simple-welcome-bar/
Description: Dropdown Bar for first time visitors and special promotions.
Author: Robert Lane
Version: 1.0
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
    wp_enqueue_script('hello-bar-cookie', plugin_dir_url(__file__).'js/wordpresswelcomebar.js', array('jquery'), null);
}

public function admin_scripts() {
    wp_enqueue_style('farbtastic');
    wp_enqueue_script('farbtastic');
}

//  --  Page Layout  --  //

public function modCSS() {
	$options = get_option('welcomebar');
	?>
	
<style type='text/css'>

#welcomebar {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 30px;
	background-color: <?php echo $options["bg_color"];?>;
	border-bottom:4px solid #fff;
	margin: 0;
	margin-bottom: -10px;	
	padding: 10px;
	z-index: <?php echo $options["z_index"];?>;
}
#welcomebar p {
	text-align: center;	
	color: <?php echo $options["font_color"];?>;
}
a.wb-cta  {
	background: #ffe684; /* Old browsers */
	background: -moz-linear-gradient(top,  #ffe684 0%, #ffcc00 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffe684), color-stop(100%,#ffcc00)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #ffe684 0%,#ffcc00 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #ffe684 0%,#ffcc00 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #ffe684 0%,#ffcc00 100%); /* IE10+ */
	background: linear-gradient(top,  #ffe684 0%,#ffcc00 100%); /* W3C */
	border: 1px solid #f0c620;
	border-radius: 3px;
	-o-border-radius: 3px;
	-ms-border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	padding: 10px 20px;
	font-family: Impact, "Arial Black", sans-serif;
	font-size: .9em;	
	text-transform: uppercase;
	font-weight: 100;
	letter-spacing: 1px;
	color: #815e0b;
	text-decoration: none;
	text-shadow: 0px 1px 0px #f3c422;
	-o-text-shadow: 0px 1px 0px #f3c422;
	-ms-text-shadow: 0px 1px 0px #f3c422;
	-moz-text-shadow: 0px 1px 0px #f3c422;
	-webkit-text-shadow: 0px 1px 0px #f3c422;
	box-shadow: 0px 1px 0px #e2e3e4;
	-o-box-shadow: 0px 1px 0px #e2e3e4;
	-ms-box-shadow: 0px 1px 0px #e2e3e4;
	-moz-box-shadow: 0px 1px 0px #e2e3e4;
	-webkit-box-shadow: 0px 1px 0px #e2e3e4;
	margin-left: 5px;
}
a.wb-cta:hover {
	background: #8dc63f; /* Old browsers */
	background: -moz-linear-gradient(top,  #8dc63f 0%, #75b443 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#8dc63f), color-stop(100%,#75b443)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #8dc63f 0%,#75b443 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #8dc63f 0%,#75b443 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #8dc63f 0%,#75b443 100%); /* IE10+ */
	background: linear-gradient(top,  #8dc63f 0%,#75b443 100%); /* W3C */
	border: 1px solid #75b443;
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
	color: #fff;
	text-decoration: none;
	text-shadow: 0px 1px 0px #70b039;
	-o-text-shadow: 0px 1px 0px #70b039;
	-ms-text-shadow: 0px 1px 0px #70b039;
	-moz-text-shadow: 0px 1px 0px #70b039;
	-webkit-text-shadow: 0px 1px 0px #70b039;
}
a.anchor-relative {
	position: relative;
	width: 1.2em;
	height: 1.2em;
}

/*
@-moz-document url-prefix() {
    #hello-bar img {
		position: absolute;
		top: -15px;
		left: 130px;		
	}
}
*/

.wb-close {
	font: Impact , "Arial", sans-serif;
	font-size: .7em;
	width: 15px;
	height: 15px;
	
	background: #aeaeae; /* Old browsers */
	background: -moz-linear-gradient(top,  #aeaeae 0%, #959595 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#aeaeae), color-stop(100%,#959595)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #aeaeae 0%,#959595 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #aeaeae 0%,#959595 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #aeaeae 0%,#959595 100%); /* IE10+ */
	background: linear-gradient(top,  #aeaeae 0%,#959595 100%); /* W3C */
	
	border: 1px solid black;
	border-radius:15px;
	color: black;
	padding: 5px;
	position: fixed;
	top:10;
	right:5px;
}
.wb-close:hover {
	color:#555;
}

.wb-spacer {
	top: 0;
	height: 30px;
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
    $layout = '<div id="welcomebar">\
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
			
			<h3>Display Options</h3>
			<p>These settings modify the appearance.</p>
			    
			<table class="form-table">
       			<tr>
					<th scope="row">Font Color</th>
					<td>
						<input type="text" size="15" id="font_color" name="welcomebar[font_color]" value="<?php echo $admin_options['font_color']; ?>" />
						<br /><div id="font_colorpicker"></div></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of your message font. Key word not accepted (orange, red, etc.)</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is #5b5a59</em></span>
					</td>
				</tr>

        		<tr>
					<th scope="row">Background Color</th>
					<td>
						<input type="text" size="15" id="bg_color" name="welcomebar[bg_color]" value="<?php echo $admin_options['bg_color']; ?>" />
						<br /><div id="bg_colorpicker"></div>
						<br /><span style="color:#666666;margin-left:2px;">Color of your message font. Key word not accepted (orange, red, etc.)</span>
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
			</table>
				
			<h3>Advanced Options</h3>
			<p>These settings modify the core of the plugin.</p>

			<table class="form-table">
				<tr>
					<th scope="row">Z-Index Fix</th>
					<td>
						<input type="text" size="64" name="welcomebar[z_index]" value="<?php echo $admin_options['z_index']; ?>" />
						<br /><span style="color:#666666;margin-left:2px;">Setting this value higher may correct placement errors with some themes.</span>
						<br /><span style="color:#666666;margin-left:2px;"><em>*The default is 10000</em></span>
					</td>
				</tr>
				
			</table>
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
      jQuery(document).ready(function() {
        jQuery('#font_colorpicker').farbtastic('#font_color');
        jQuery('#bg_colorpicker').farbtastic('#bg_color');
        jQuery('#font_colorpicker').hide();
        jQuery('#bg_colorpicker').hide();
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
