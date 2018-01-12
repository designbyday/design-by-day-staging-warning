<?php

/*===================================================
	Plugin Name: Design By Day Staging Warning
	Version: 1.0
	Description: Plugin to warn users to use live version of the site now instead.
	Author: Design By Day
	Author URI: https://www.designbyday.co.uk/
	Text Domain: dbdwarn
	Prefix: dbdwarn
===================================================*/

define( 'DBDWARN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DBDWARN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*===================================================
	Add Staging Warning Settings Page
===================================================*/

function dbdwarn_warning_menu() {
	// Add settings page for Staging Warning
	add_options_page( 'Staging Warning', 'Staging Site Warning', 'manage_options', 'dbdwarn-warning-settings', 'dbdwarn_warning_settings_callback' );
}

add_action( 'admin_menu', 'dbdwarn_warning_menu' );


/*===================================================
	Staging Warning Settings Page Callback
===================================================*/

function dbdwarn_warning_settings_callback() {

	?>

		<div class="wrap">
			<h1>Staging Warning Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'warning-section' ); ?> 
				<?php do_settings_sections( 'dbdwarn-warning-settings' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>

	<?php
}

/*===================================================
	Warning Site URL Field
===================================================*/

function dbdwarn_warning_url_element() {

	?>

		<input class="widefat" type="text" name="warning-live-url" id="warning-live-url" value="<?php echo esc_attr( get_option( 'warning-live-url' ) ); ?>">

	<?php

}

/*===================================================
	Warning Display Settings
===================================================*/

function dbdwarn_warning_display_settings() {

	add_settings_section( 'warning-section', 'Staging Warning Settings', null, 'dbdwarn-warning-settings' );

	add_settings_field( 'warning-live-url', 'Live Site URL', 'dbdwarn_warning_url_element', 'dbdwarn-warning-settings', 'warning-section' );

	register_setting( 'warning-section', 'warning-live-url' );

}

add_action( 'admin_init', 'dbdwarn_warning_display_settings' );

/*===================================================
	Output Site Warning
===================================================*/

function dbdwarn_output_site_warning() {
	$live_url = get_option( 'warning-live-url' );

	if ( $live_url ) {
		$warning = 'Your website is now live – This is your staging site. You will now need to access your live URL: <a href="' . esc_url_raw( rtrim( $live_url, '/' ) ) . '">' . esc_html( rtrim( $live_url, '/' ) );
		require_once('includes/site-warning-html.php');
	}	
}

add_action( 'wp_footer', 'dbdwarn_output_site_warning' );

function dbdwarn_output_login_warning() {
	$live_url = get_option( 'warning-live-url' );

	if ( $live_url ) {
		$warning = 'Your website is now live – This is your staging site. You will now need to log into WordPress at your live URL: <a href="' . esc_url_raw( rtrim( $live_url, '/' ) . '/wp-admin' ) . '">' . esc_html( rtrim( $live_url, '/' ) ) . '/wp-admin</a>';
		require_once('includes/site-warning-html.php');
	}	
}

add_action( 'login_header', 'dbdwarn_output_login_warning' );

/*===================================================
	Enqueue Assets
===================================================*/

function dbdwarn_enqueue_assets() {
	$live_url = get_option( 'warning-live-url' );

	if ( $live_url ) {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'dbdwarnsite', DBDWARN_PLUGIN_URL . 'css/dbdstagwarning.css', array( 'dashicons' ) );
		wp_enqueue_script( 'dbdwarnsite', DBDWARN_PLUGIN_URL . 'js/dbdstagwarning.js', array( 'jquery' ), false, true );
	}	
}

add_action( 'wp_enqueue_scripts', 'dbdwarn_enqueue_assets' );
add_action( 'login_enqueue_scripts', 'dbdwarn_enqueue_assets' );