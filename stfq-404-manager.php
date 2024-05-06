<?php
/*
Plugin Name: STFQ 404 Manager
Description: Choose a specific page from your WordPress pages to use as the 404 error page.
Version: 1.0
Author: Strangefrequency LLC
Author URI: https://strangefrequency.com/
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Add settings page to the admin menu
function stfq_404_manager_menu() {
    add_options_page( 'STFQ 404 Manager Settings', 'STFQ 404 Manager', 'manage_options', 'stfq-404-manager', 'stfq_404_manager_settings_page' );
}
add_action( 'admin_menu', 'stfq_404_manager_menu' );

// Render settings page
function stfq_404_manager_settings_page() {
    $redirect_url = get_option( 'stfq_404_redirect_url', home_url() );
    ?>
    <div class="wrap">
        <h2>STFQ 404 Manager Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'stfq_404_manager_settings_group' ); ?>
            <p class="description">Select the page to redirect to when a 404 error occurs.</p>
            <label for="stfq_404_redirect_url">Redirect to Page:</label>
            <select id="stfq_404_redirect_url" name="stfq_404_redirect_url">
                <option value="">Select a page</option>
                <?php
                $pages = get_pages();
                foreach ( $pages as $page ) {
                    printf( '<option value="%s" %s>%s</option>', esc_attr( get_page_link( $page->ID ) ), selected( $redirect_url, get_page_link( $page->ID ), false ), esc_html( $page->post_title ) );
                }
                ?>
            </select>
            <input type="submit" class="button button-primary" value="Save Settings">
        </form>
    </div>
    <?php
}

// Register plugin settings
function stfq_404_manager_register_settings() {
    register_setting( 'stfq_404_manager_settings_group', 'stfq_404_redirect_url' );
}
add_action( 'admin_init', 'stfq_404_manager_register_settings' );

// Override 404 error page
function stfq_override_404_page() {
    if ( is_404() ) {
        $redirect_url = get_option( 'stfq_404_redirect_url', home_url() );
        wp_redirect( $redirect_url );
        exit;
    }
}
add_action( 'template_redirect', 'stfq_override_404_page' );
