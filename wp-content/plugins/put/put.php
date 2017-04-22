<?php
/*
	Author: t31os_
	Description: Create jQuery UI tabs inside the post editor using shortcodes
	Domain Path: /lang
	Plugin Name: Post UI Tabs
	Plugin URI: http://wordpress.org/extend/plugins/put/
	Text Domain: post-ui-tabs
	Version: 1.1.0
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if( !function_exists( 'add_action' ) ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
	add_action( 'admin_notices', 'put_php_version_require' );
	function put_php_version_require() {
		if( current_user_can( 'manage_options' ) )
			echo '<div class="error"><p>The Post Tabs UI plugin requires at least PHP 5.</p></div>';
	}
	return;
}
global $wp_version;	         
if( version_compare( $wp_version, '3.1', '<' ) ) {
	add_action( 'admin_notices', 'put_wordpress_version_require' );
	function put_wordpress_version_require() {
		if( current_user_can( 'manage_options' ) )
			echo '<div class="error"><p>The Post Tabs UI plugin requires at least WordPress 3.1</p></div>';
	}
	return;
}

class Post_UI_Tabs {
	
	private $options;
	private $skin_dir;
	private $pagehook;
	private $plugin_slug = 'post-ui-tabs';
	private $ui_version  = '1.10.2';        // jQuery UI Version(fallback)
	private $version     = '1.1.0';         // Plugin Version
	private $cssfile     = 'jquery-ui.css'; // jQuery UI CSS filename
	private $textdom     = 'post-ui-tabs';
	private $opt_group   = 'post-ui-tabs_group';
	private $opt_name    = 'post-ui-tabs_settings';
	private $config   = array();
	private $tabs     = array();
	private $disabled = array();
	private $enabled  = array();
	private $skins    = array( 
		// Base theme has now been deprecated and thus removed
		'black-tie', 'blitzer', 'cupertino', 'dark-hive', 
		'dot-luv', 'eggplant', 'excite-bike', 'flick', 'hot-sneaks', 
		'humanity', 'le-frog', 'mint-choc', 'overcast', 'pepper-grinder', 
		'redmond', 'smoothness', 'south-street', 'start', 'sunny', 
		'swanky-purse', 'trontastic', 'ui-darkness', 'ui-lightness', 'vader' 
	);
	private $index    = 1;
	private $set      = 1;
	private $is_feed  = false;
	public $has_tabs  = false;
	
	// For testing
	public function on_plugin_locale( $current_locale ) {
		return 'TESTLOCALE';
	}
	
	public function __construct() {
		
		add_action( 'init',               array( $this, 'on_init' ) );
		add_action( 'admin_init',         array( $this, 'on_admin_init' ) );
		add_action( 'admin_menu',         array( $this, 'on_admin_menu' ) );
	}
	public function on_init() {
		
		$this->ui_version = $this->get_wp_ui_version();
		$this->config = get_option( $this->opt_name );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'on_wp_enqueue' ) );
		add_action( 'the_posts',          array( $this, 'on_the_posts' ) );	
		add_filter( 'the_content',        array( $this, 'on_the_content' ), 7 ); // Priority 7 - before wpautop
		add_action( 'wp_footer',          array( $this, 'on_wp_footer' ) );
	}
	public function on_the_posts( $posts ) {
		
		if( is_feed() )
			$this->is_feed = true;
		
		if( empty( $posts ) || $this->has_tabs )
			return $posts;
			
		if( !$this->get_plugin_option( 'on_archives' ) && !is_singular() )
			return $posts;

		foreach( $posts as $post ) {
			if( !stripos( $post->post_content, '[end_tabset]' ) )
				continue;
			$this->has_tabs = true;
		}
	
		return $posts;
	}
	public function on_the_content( $content ) {
		
		$this->has_tabs = (bool) apply_filters( 'put_decide_has_tabs', $this->has_tabs );
		
		if( !$this->has_tabs )
			return $content;
			
		global $shortcode_tags;

		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		$shortcode_tags = array();

		add_shortcode( 'tab',        array( $this, 'on_tab_shortcode' ) );
		add_shortcode( 'end_tabset', array( $this, 'on_tab_end_shortcode' ) );

		// Do the shortcode(only the tab shortcode is registered at this point)
		$content = do_shortcode( $content );
		
		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;

		return $content;
	}
	public function get_wp_ui_version() {
		
		global $wp_scripts;
		
		if( !$wp_scripts instanceof WP_Scripts )
			$wp_scripts = new WP_Scripts();
		
		$jquery_ui_core = $wp_scripts->query( 'jquery-ui-core' );
		
		if( !$jquery_ui_core instanceof _WP_Dependency )
			return $this->ui_version;
			
		if( !isset( $jquery_ui_core->ver ) )
			return $this->ui_version;
			
		return $jquery_ui_core->ver;
	}
	public function on_wp_footer() {
		
		if( !$this->has_tabs || $this->is_feed )
			return;
		
		$script_dependancies = array( 'jquery-ui-core', 'jquery-ui-tabs' );
		$script_parameters   = array( 'total_tabsets' => $this->set - 1 );
		
		if( $this->get_plugin_option( 'disable_empty' ) ) {
			foreach( $this->disabled as $set => $empty_keys )
				$script_parameters['disabled_tabs'][$set] = $empty_keys;
			foreach( $this->enabled as $set => $nonempty_keys )
				$script_parameters['enabled_tabs'][$set] = $nonempty_keys;
		}
		$script_parameters['select_active'] = $this->get_plugin_option( 'select_active' );
		
		wp_register_script( $this->plugin_slug, plugins_url( '/js/post-ui-tabs.js', __FILE__ ), $script_dependancies, $this->version, true );
		wp_localize_script( $this->plugin_slug, 'tab_settings', $script_parameters );
		wp_print_scripts( $this->plugin_slug );
	}
	public function on_wp_enqueue() {
		
		if( !$this->has_tabs || $this->is_feed )
			return;
		
		if( $this->get_plugin_option( 'disable_css' ) ) {
			do_action( 'put_enqueue_css' );
			return;
		}
		$this->skin_dir = apply_filters( 'put_theme_dir',  'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->ui_version . '/themes' );
		$this->cssfile  = apply_filters( 'put_stylesheet', $this->cssfile );
		$this->skins    = apply_filters( 'put_skins',      $this->skins );
		
		$skin = $this->get_plugin_option( 'skin' );
		
		if( !$skin )
			return;
			
		wp_enqueue_style( 'jquery-ui-tabs', $this->plugin_cssfile( $skin ), array(), $this->ui_version );
	}
	// Joins the URL components for the stylesheet uri together
	private function plugin_cssfile( $skin ) {
		
		$components = array( $this->skin_dir );
		
		if( in_array( $skin, $this->skins ) )
			$components[] = $skin;
		
		$components[] = $this->cssfile;
		
		return apply_filters( 'put_stylesheet_uri', implode( '/', $components ) );
	}
	public function on_settings_page() {
	?>
	<div class="wrap">
		<h2><?php _e( 'Post UI Tabs', $this->textdom ); ?></h2>
		<p class="description"><?php _e( 'Welcome to the Post UI Tabs plugin configuration page.', $this->textdom ) ?></p>
		<form method="post" action="options.php" >
			<table class="form-table">
				<tbody>
					<?php $this->plugin_rows(); ?>
				</tbody>
			</table>
			<p class="submit">
				<?php settings_fields( $this->opt_group ); ?>
				<input type="submit" name="submit" class="button-secondary action" value="<?php _e( 'Save Settings', $this->textdom ) ?>" />
			</p>
		</form>
		<?php $this->style_preview(); ?>
		<div class="clear"></div>
	</div>
	<?php
	}
	public function on_load_plugin_settings() {
		
		$screen = get_current_screen();
		
		if( !method_exists( $screen, 'add_help_tab' ) )
			return;
		
		$sidebar = '<ul>
			<li><a href="http://wordpress.org/extend/plugins/put/faq/">' . __( 'FAQ', $this->textdom ) . '</a></li>
			<li><a href="http://wordpress.org/extend/plugins/put/other_notes/">' . __( 'Plugin Hooks', $this->textdom ) . '</a></li>
			<li><a href="http://wordpress.org/extend/plugins/put/changelog/">' . __( 'Change Log', $this->textdom ) . '</a></li>
			<li><a href="http://wordpress.org/support/plugin/put#topic">' . __( 'Support', $this->textdom ) . '</a></li>
		</ul>';

		
		$screen->add_help_tab( array( 'title' => __( 'Overview', $this->textdom ),      'id' => 'overview', 'content' => $this->get_help_text( 'overview' )));
		$screen->add_help_tab( array( 'title' => __( 'Skins', $this->textdom ),         'id' => 'skins',    'content' => $this->get_help_text( 'skins' )));
		$screen->add_help_tab( array( 'title' => __( 'Disabled Tabs', $this->textdom ), 'id' => 'disabled', 'content' => $this->get_help_text( 'disabled' )));
		$screen->set_help_sidebar( $sidebar );
	}
	private function get_help_text( $tab ) {
		switch( $tab ) {
			case 'overview': 
				$text = array( 
					'<p><strong>' . __( 'Welcome to the Post UI Tabs plugin configuration page.', $this->textdom ) . '</strong><br /><em>'. __( 'Using this page you may configure the various settings for the plugin to suit your needs, don\'t be shy .. go ahead!', $this->textdom ) .'</em></p>',
				);
			break;
			case 'skins': 
				$text = array( 
					'<p><strong>' . __( 'Choose a UI skin', $this->textdom ) . '</strong><br /><em>' . __( 'The default skin options are provided based on the theme\'s available from the jQuery UI site. Simply click the dropdown menu shown and choose from the list of styles. A preview of your selected skin will be shown on the righthand side of the configuration area.', $this->textdom ) . '</em></p>',
					'<p><strong>' . __( 'Disable skins', $this->textdom ) . '</strong><br /><em>'. __( 'If you\' prefer to do your own styling by any means feel free to tick the disable skins option to prevent the plugin from loading any stylesheet.', $this->textdom ) .'</em></p>',
				);
			break;
			case 'disabled':
				$text = array(
					'<p><strong>' . __( 'Use disabled tabs', $this->textdom ) . '</strong><br /><em>' . __( 'Enabling this option will prevent users from being able to click tabs that do not have any content associated with them. You can find an example of a disabled tab in the preview shown to the right of the configuration options.', $this->textdom ) . '</em></p>',
				);
			break;
			default: return '';
		}
		return implode( '', $text );
	}
	public function on_tab_shortcode( $atts, $content = null ) {
		
		global $post;
		
		// Fake ID for preview
		$id = ( is_admin() ) ? 0 : $post->ID;
		$default_text = sprintf( apply_filters( 'put_tab_default_text', __( 'Tab %d', $this->textdom ) ), $this->index);

		// Remove any leading/trailing whitespace
		$content = trim( $content );
		
		$this->tabs[$id][$this->index] = shortcode_atts( array( 'name' => $default_text ), $atts );
		$this->tabs[$id][$this->index]['content'] = !empty( $content ) ? $content : '';
		
		if( empty( $this->tabs[$id][$this->index]['name'] ) )
			$this->tabs[$id][$this->index]['name'] = $default_text;
		
		$this->index++;
		
		unset( $atts, $content );
		
		return '';
	}
	public function on_tab_end_shortcode() {
		
		global $post;
		
		$id = ( is_admin() ) ? 0 : $post->ID;
		
		if( !isset( $this->tabs[$id] ) )
			return '';
		
		if( !$this->has_tabs )
			return '';
		
		if( $this->get_plugin_option( 'show_nav' ) && !is_admin() ) {
			
			$prev_text    = apply_filters( 'put_prev_text', 'Previous' ); // No translation here, not loading the text domain frontside for the sake of two strings, filters provided instead
			$next_text    = apply_filters( 'put_next_text', 'Next' );
			$prev_classes = apply_filters( 'put_nav_class', array( 'ui-icon', 'ui-icon-circle-triangle-w' ), 'prev' );
			$next_classes = apply_filters( 'put_nav_class', array( 'ui-icon', 'ui-icon-circle-triangle-e' ), 'next' );
			
			$next_classes = array_map( 'esc_attr', $next_classes );
			$prev_classes = array_map( 'esc_attr', $prev_classes );
			
			array_push( $next_classes, 'alignright', 'put-next' );
			array_push( $prev_classes, 'alignleft',  'put-prev' );
			
			$next_classes = join( ' ', $next_classes );
			$prev_classes = join( ' ', $prev_classes );
		}		
		$tab_html    = 
		$box_html    = 
		$before_html =
		$after_html  = '';
		
		if( !$this->is_feed ) {
			$before_html = "<div id='".$this->get_tab_id()."'>";
			
			if( $this->get_plugin_option( 'show_nav' ) && !is_admin() )
				$after_html .= '<div class="put-nav">'. $this->get_nav_link( $next_classes, $next_text ) . $this->get_nav_link( $prev_classes, $prev_text ) . '<br class="clear" /></div>';
			
			if( is_admin() )
				$after_html .= '<hr class="ui-widget-content" /><div class="ui-tabs-panel">' . __( '<strong>Please note:</strong><br /> The tabs may not appear on your pages as you see them here, your theme stylesheet may override some of the styling. If you would like some help making adjustments please feel free to start a support topic on the WordPress forums.', $this->textdom ) . '</div>';
			
			$after_html .= '</div>';
			$after_html .= (bool) apply_filters( 'put_trailing_linebreak', true ) ? '<br />' : '';
		}
		// Store tabs
		$tabs = $this->tabs[$id];
		
		// Unset class property ready for the next set iteration
		unset( $this->tabs[$id] );
		
		if( $this->is_feed ) {
			
			foreach( $tabs as $index => $tab ) 
				$tab_html .= !empty( $tab['content'] ) 
					? $this->tab_list_item( $index, esc_html( $tab['name'] ), true ) . $this->tab_box_item( $index, $tab['content'], true ) 
					: ''; // Ignore empty tabs on feeds
		}
		else {
			
			$hide_empty = $this->get_plugin_option( 'disable_empty' ) ? true : false;

			foreach( $tabs as $index => $tab ) {
				$tab_html .= $this->tab_list_item( $index, esc_html( $tab['name'] ) ); // No HTML in tab names
				$box_html .= $this->tab_box_item( $index, $tab['content'] ); 
				
				if( !$hide_empty )
					continue;
				
				if( empty( $tab['content'] ) )
					$this->disabled[$this->set][] = $index - 1;
				else
					$this->enabled[$this->set][] = $index - 1;
			}
			$tab_html = '<ul>' . $tab_html . '</ul>';
		}
		// Reset the index
		$this->index = 1;
		// Increment the set index
		$this->set++;
		
		return $before_html . $tab_html . $box_html . $after_html;
	}
	private function plugin_field( $input_name, $properties ) {
		
		$current = $this->get_plugin_option( $input_name );
		
		$row = "<tr>";

		switch( $properties['type'] ) {
			case 'checkbox':
				
				$row .= sprintf( '<th scope="row">%s<p class="description">%s</p></th>', $properties['name'], $properties['desc'] );
				$row .= sprintf( "<td><input type='checkbox' name='%s[%s]' value='1'%s /> ", $this->opt_name, $input_name, checked( $current, true, false ) );
				$row .= sprintf( '<label for="%s-%s">%s</label></td>', $this->opt_name, $input_name, $properties['label'] );

			break;
			case 'dropdown':
				
				$row .= sprintf( '<th scope="row">%s<p class="description">%s</p></th>', $properties['name'], $properties['desc'] );
				$row .= sprintf( '<td><select name="%s[%s]">', $this->opt_name, $input_name );

				foreach( $properties['options'] as $option ) 
					$row .= sprintf( '<option value="%s"%s>%s</option>', $option, selected( $option == $current, true, false ), ucfirst( str_replace( '-', ' ', $option ) ) );
				
				$row .= sprintf( '</select><br /><label for="%s-%s">%s</label></td>', $this->opt_name, $input_name, $properties['label'] );		
				
			break;
		}
		$row .= "</tr>";
		
		echo $row;
	}
	public function on_plugin_styles() {
		// Some CSS to help the admin page accomodate the tabs preview
		?>
		<style type="text/css">
		form { width: 55%;float:left }
		.ui-tabs.ui-widget { width: 40%;float:right }
		.ui-tabs-hide { display:none!important; }
		hr.ui-widget-content { border-width: 1px 0 0;margin:0 2px; }
		.ui-widget-content p { margin-bottom:0; }
		</style>
		<?php
	}
	public function on_admin_enqueue( $hook ) {
	
		if( $this->pagehook != $hook )
			return;
		
		if( empty( $this->config ) ) 
			return;
		
		$this->skin_dir = apply_filters( 'put_theme_dir',  'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->ui_version . '/themes' );
		$this->skins    = apply_filters( 'put_skins',      $this->skins );
		$this->cssfile  = apply_filters( 'put_stylesheet', $this->cssfile );
		
		$script_dependancies = array( 'jquery-ui-core', 'jquery-ui-tabs' );
		
		wp_enqueue_script(  $this->plugin_slug, plugins_url( '/js/demo.js', __FILE__ ), $script_dependancies, $this->version, true );
		
		// Create variables for the demo javasript
		wp_localize_script( $this->plugin_slug, 'put_skins', $this->skins );
		wp_localize_script( $this->plugin_slug, 'put_dir',   $this->skin_dir );
		wp_localize_script( $this->plugin_slug, 'put_file',  $this->cssfile );
			
		$skin = $this->get_plugin_option( 'skin' );
		
		if( !$skin || $this->get_plugin_option( 'disable_css' ) ) {
			do_action( 'put_admin_enqueue_css' );
			return;
		}
		wp_enqueue_style( 'jquery-ui-tabs', $this->plugin_cssfile( $skin ), array(), $this->ui_version );
	}
	public function on_plugin_save( $_post_data ) {
		
		if( empty( $_post_data ) || !is_array( $_post_data ) )
			return array();
		
		$clean = array();
		$_post_data = array_map( 'trim', $_post_data );
		
		foreach( $_post_data as $option_name => $option_value ) {
			
			if( !isset( $this->options[$option_name] ) )
				return $this->config;
			
			switch( $this->options[$option_name]['type'] ) {
				case 'checkbox':
					$clean[$option_name] = 1;
				break;
				case 'dropdown':
					foreach( $this->options[$option_name]['options'] as $valid_option ) {
						if( $option_value != $valid_option )
							continue;
						$clean[$option_name] = $valid_option;
					}
				break;
			}
		}
		return $clean;
	}
	private function style_preview() {
		
		$this->has_tabs = true;
		
		add_shortcode( 'tab',        array( $this, 'on_tab_shortcode' ) );
		add_shortcode( 'end_tabset', array( $this, 'on_tab_end_shortcode' ) );
		
		echo do_shortcode( '
			[tab]'.$this->sample_text(0).'[/tab] 
			[tab]'.$this->sample_text(1).'[/tab]
			[tab name="Disabled tab 3"][/tab]
			[tab]'.$this->sample_text(2).'[/tab] 
			[end_tabset]
		' );
		
		remove_shortcode( 'tab' );
		remove_shortcode( 'end_tabset' );
		
		$this->has_tabs = false;
	}
	private function sample_text( $key ) {
		$sample_text = array( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sem justo, lobortis mollis rutrum non, imperdiet vel eros. Morbi ut libero odio. Donec metus mauris, scelerisque sed consequat at, rutrum id lacus. Nam consectetur elit nec mi tristique condimentum fermentum nisi dictum. Sed sed ligula urna. Ut tellus dui, sollicitudin et congue eget, auctor eu felis. Duis interdum odio eu metus tristique quis porttitor leo eleifend. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Nam mattis pulvinar sapien et porttitor. Nam et nibh ac dui convallis interdum. Fusce ornare volutpat metus, quis consectetur ipsum tempor a. Integer in velit vitae erat venenatis laoreet vitae eu tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris eu nunc at urna luctus convallis. Vestibulum et dolor imperdiet ligula placerat ornare.
<br /><br />
Mauris et justo augue. Quisque tempor nisl eu lorem varius pharetra. Donec sed lacus nisl, non ultricies erat. Cras posuere fringilla enim, eu porttitor sapien condimentum adipiscing. Pellentesque elementum velit nec turpis blandit nec aliquet nunc suscipit. Phasellus suscipit sapien a ipsum gravida quis consectetur leo semper. Ut faucibus dui et velit tincidunt eget adipiscing augue posuere. Sed eu consectetur lorem. Donec vulputate, metus vel scelerisque pharetra, felis ligula pellentesque metus, vel vestibulum dui nulla vulputate nibh. Vivamus venenatis, neque eget pharetra facilisis, ligula diam iaculis tortor, at vehicula mi tortor interdum purus. Nulla sodales congue nisi nec blandit. Donec lacinia condimentum tellus ut tincidunt. Nunc eleifend, purus ac facilisis ornare, urna justo accumsan dolor, nec laoreet ligula tortor non metus.', 'Nulla facilisi. Sed eget orci id nibh sagittis facilisis. Proin nec pulvinar ante. Donec dignissim pretium ipsum, pulvinar vulputate sem volutpat ac. Donec hendrerit est eu eros laoreet sagittis. Duis vel viverra ipsum. Nam sit amet lectus risus. Pellentesque quis ante bibendum nisi sollicitudin tempor. Aliquam orci nulla, pharetra in scelerisque et, imperdiet id massa. Pellentesque nec dolor et nulla gravida pretium at quis nisl. Nullam venenatis ultrices nunc nec mattis. Maecenas consectetur tempor erat nec commodo. Phasellus ac porttitor nulla. Morbi malesuada urna id nisl egestas vel tempus tellus pharetra. Vestibulum a augue sit amet tellus interdum dictum vitae ac ante. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sodales, tellus non convallis fermentum, neque sem vehicula nibh, eu dictum libero nulla sed justo. In vitae volutpat odio. Etiam sollicitudin nibh et erat scelerisque a mollis mi eleifend.
<br /><br />
Donec dui purus, vulputate ac pulvinar ut, tincidunt ut eros. Praesent sit amet nisl odio. Nulla id augue augue. Phasellus placerat sollicitudin lorem eu placerat. Fusce tincidunt nisi sed arcu tincidunt ut suscipit lorem viverra. Praesent urna libero, laoreet eu rhoncus vitae, elementum at leo. Quisque ut magna urna, eu tempor arcu. Nullam massa tellus, feugiat in posuere vitae, vulputate ac justo. Maecenas et molestie massa. Donec lectus eros, cursus at posuere sed, semper id erat. Mauris eget mi magna.', 'Mauris tristique fringilla dictum. Vivamus pulvinar neque sed magna sagittis molestie. Ut fermentum sem at mi lacinia dictum. Mauris sed adipiscing justo. Nulla a ornare massa. Sed felis orci, venenatis at gravida ac, feugiat vel elit. Praesent fringilla, lectus a fringilla rhoncus, urna leo gravida magna, at porta justo metus eget nisl. Integer porta enim ut velit luctus nec condimentum velit feugiat. Pellentesque elementum convallis molestie. Aliquam mattis ligula at libero rhoncus vehicula. Vestibulum in erat id nunc tincidunt elementum ac eu metus. Pellentesque sit amet odio metus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc congue malesuada felis at euismod. Cras mattis ullamcorper neque, vitae tristique urna blandit in. Donec volutpat tempus lorem nec mattis. Cras fermentum sollicitudin ipsum et convallis. Donec sollicitudin tempus nisi, malesuada interdum arcu posuere vel. Duis sit amet magna purus, dignissim lobortis ipsum. Quisque at pellentesque justo.
<br /><br />
Maecenas non volutpat nulla. Duis placerat aliquam odio, at rhoncus tellus faucibus nec. Integer vitae erat nisi. Sed congue ante eget libero ultricies rutrum vestibulum quam pellentesque. Proin molestie tristique nisi, a fringilla nulla mollis in. Nullam faucibus imperdiet dignissim. Proin nec nisl libero, id mollis nisl. Vivamus varius dignissim augue, eu facilisis augue cursus non. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum eget est vitae nulla aliquam scelerisque mattis at turpis. Nunc dignissim consectetur facilisis. Mauris magna eros, cursus eu vestibulum sed, pretium sit amet lectus. Nam quis enim magna, venenatis venenatis metus. Integer pharetra auctor ipsum, quis suscipit velit lacinia quis. Aliquam non eros in felis sollicitudin mattis nec non felis. Nullam consequat elementum pharetra. Quisque nunc augue, auctor a fermentum sed, congue consequat nulla. ' );
		return $sample_text[$key];
	}
	private function get_plugin_option( $name ) {
		
		if( !isset( $this->config[$name] ) )
			return false;
		
		if( empty( $this->config[$name] ) || !$this->config[$name] )
			return false;
		
		return $this->config[$name];
	}
	private function get_tab_id( $index = '' ) {
		
		if( !empty( $index ) )
			$index = "-$index";
		
		return "tabs-{$this->set}{$index}";
	}
	private function get_nav_link( $classes, $text ) {
		return sprintf( '<a href="#%s" class="%s">%s</a>', $this->get_tab_id(), $classes, $text );
	}
	private function tab_list_item( $index, $name, $feed = false ) {
		return $feed ? sprintf( '<h3>%s</h3>', $name ) : sprintf( '<li><a href="#%s">%s</a></li>', $this->get_tab_id( $index ), $name );
	}
	private function tab_box_item( $index, $content, $feed = false ) {
		return $feed ? sprintf( '<div>%s</div>', $content ) : sprintf( '<div id="%s"><p>%s</p></div>', $this->get_tab_id( $index ), $content );
	}
	private function plugin_rows() {
		foreach( $this->options as $name => $data )
			$this->plugin_field( $name, $data ); 
	}
	public function on_admin_menu() {
		
		$this->pagehook = add_options_page( __( 'Post UI Tabs', $this->textdom ), __( 'Post UI Tabs', $this->textdom ), 'manage_options' , $this->plugin_slug, array( $this, 'on_settings_page' ) );
		
		add_action( "admin_enqueue_scripts",                array( $this, 'on_admin_enqueue' ) ); // Ideal for both scripts and styles
		add_action( "admin_print_styles-{$this->pagehook}", array( $this, 'on_plugin_styles' ) ); // CSS specifically for the plugin config page
		add_action( "load-{$this->pagehook}",               array( $this, 'on_load_plugin_settings' ) );
	}
	public function on_admin_init() {
		
		//add_filter( 'plugin_locale', array( $this, 'on_plugin_locale' ) ); // Quick/easy way to fake locale
		
		global $plugin_page;
		
		register_setting( $this->opt_group, $this->opt_name, array( $this, 'on_plugin_save' ) );
		
		if( $this->plugin_slug == $plugin_page )
			load_plugin_textdomain( $this->textdom, false, basename( dirname( __FILE__ ) ) . '/lang' );
			
		// The plugin options(needs to defined here so the sanitization function has access to the data)
		$this->options = array(
			'skin'           => array( 
				'type'        => 'dropdown', 
				'name'        => __( 'Choose a UI skin', $this->textdom ), 
				'desc'        => __( 'Select a skin', $this->textdom ),
				'label'       => __( 'Select which jQuery UI skin to apply to the tabs - ignored if skins have been disabled', $this->textdom ),
				'options'     => $this->skins
			),
			'disable_css'    => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Disable skins', $this->textdom ), 
				'desc'        => __( 'Disable the plugin stylesheet', $this->textdom ),
				'label'       => __( 'Enable this option if you want to define your own CSS for the tabs', $this->textdom )
			),
			/**
			'use_cookies'    => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Enable jQuery cookies', $this->textdom ), 
				'desc'        => __( 'Should the script be included', $this->textdom ),
				'label'       => __( 'Enable the jquery cookie script to remember selected tabs.', $this->textdom )
			),
			/**/
			'on_archives'    => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Enable tabs for archives', $this->textdom ), 
				'desc'        => __( 'Use tabs on more pages', $this->textdom ),
				'label'       => __( 'Whether to enable tabs on non-single pages, such as category archives, date archives, the index and so on.', $this->textdom )
			),
			'show_nav'       => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Enable tab navigation', $this->textdom ), 
				'desc'        => __( 'Next and previous links', $this->textdom ),
				'label'       => __( 'Enabling this feature adds next and previos tabs links to all tab sets', $this->textdom )
			),
			'disable_empty'  => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Use disabled tabs', $this->textdom ), 
				'desc'        => __( 'Disable empty tabs', $this->textdom ),
				'label'       => __( 'Using this option will prevent users being able to click on empty tabs', $this->textdom )
			),
			'rw_feed_html'   => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Rewrite feed HTML', $this->textdom ), 
				'desc'        => __( 'Replace tabs and content with headings and paragraphs', $this->textdom ),
				'label'       => __( 'If this option is not enabled tabs are removed from feeds', $this->textdom )
			),
			'select_active'   => array( 
				'type'        => 'checkbox', 
				'name'        => __( 'Set active tab', $this->textdom ), 
				'desc'        => __( 'Disable to allow tab selection via anchors', $this->textdom ),
				'label'       => __( 'Automatically set the active tab for each set of tabs', $this->textdom )
			),
		);
		// User can rich edit?
		if( 'true' != get_user_option('rich_editing') )
			return;
		
		// Current user has required caps?
		if( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
			return;
		
		add_filter( 'mce_external_plugins',       array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons',                array( $this, 'add_tinymce_items' ) ); 
		add_action( 'admin_footer-post.php',      array( $this, 'add_put_quicktags' ), 1000 );
		add_action( 'admin_footer-post-new.php',  array( $this, 'add_put_quicktags' ), 1000 );
	}
	public function add_put_quicktags() {
		?>
		<script type="text/javascript">
			edButtons[edButtons.length] = new edButton( 'put_addTab', 'tab', '[tab name="<?php _e( 'Tab', $this->textdom ); ?>"]', "[/tab]\n", '' );
			edButtons[edButtons.length] = new edButton( 'put_endTabs', 'end tabs', "[end_tabset]\n", '', '' );
		</script>
		<?php
	}
	public function add_tinymce_plugin( $tinymce_external_plugins ) {
		$tinymce_external_plugins['wordpress_put'] = plugins_url( '/js/editor_plugin.js', __FILE__ );
		return $tinymce_external_plugins;
	}
	public function add_tinymce_items( $buttons ) {
		array_push( $buttons, 'separator', 'put_addtab', 'put_addtab_quick', 'put_endtabs' );
		return $buttons;
	}
}
$Post_UI_Tabs = new Post_UI_Tabs;
