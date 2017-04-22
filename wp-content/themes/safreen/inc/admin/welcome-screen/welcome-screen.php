<?php
/**
 * Welcome Screen Class
 */
class safreen_Welcome {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'safreen_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'safreen_activation_admin_notice' ) );

		
		
		/* load welcome screen */
		add_action( 'safreen_welcome', array( $this, 'safreen_welcome_getting_started' ),10 );
	
	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 */
	public function safreen_welcome_register_menu() {
		$safreen_theme = wp_get_theme();
		$page_menu_title = esc_html__('Documentation', 'safreen').' '.$safreen_theme->get( 'Name' );
		add_theme_page( $page_menu_title, $page_menu_title, 'edit_theme_options', 'safreen-welcome', array( $this, 'safreen_welcome_screen' ) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 */
	public function safreen_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'safreen_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 */
	public function safreen_welcome_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing Safreen ! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page & Documentation%s.', 'safreen' ), '<a href="' . esc_url( admin_url( 'themes.php?page=safreen-welcome' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=safreen-welcome' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with safreen ', 'safreen' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Welcome screen content
	 */
	public function safreen_welcome_screen() {

		
		?>

		
			<?php
			do_action( 'safreen_welcome' ); ?>

		</div>
		<?php
	}
/**
	 * Getting started
	 */
	public function safreen_welcome_getting_started() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/getting-started.php' );
	}

	
}

$GLOBALS['safreen_Welcome'] = new safreen_Welcome();