<?php

/**
 *
 * @link              https://www.whiteboard-mktg.com
 * @since             1.0.0
 * @package           wm_dashboard
 *
 * @wordpress-plugin
 * Plugin Name:       Whiteboard Marketing Dashboard Widget
 * Description:       This plugin installs a dashboard widget that displays recent posts from the Whiteboard Marketing website JSON feed. There are no settings or options. To use, just install and activate. To disable, deactivate.
 * Version:           1.1
 * Author:            Whiteboard Marketing
 * Author URI:        https://www.whiteboard-mktg.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wm-dashboard
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'wm-dashboard', '1.0.0' );

/* 
Add function to WP Dashboard
*/
function wm_dashboard_add_dashboard_widgets() {

	wp_add_dashboard_widget(
		'wm_news_widget',
		__('Whiteboard Marketing News', 'wm-dashboard'),
		'wm_dashboard_feed_loop'
	);
}
add_action( 'wp_dashboard_setup', 'wm_dashboard_add_dashboard_widgets' );


/* 
Loop through posts from feed
*/
function wm_dashboard_feed_loop() {

?>

<h3 style="font-weight:bold;"><?php _e( 'Recent News From Whiteboard Marketing Blog:', 'wm-dashboard' ); ?></h3>

<?php

	include_once( ABSPATH . WPINC . '/feed.php' );

	$rss = fetch_feed( 'https://www.whiteboard-mktg.com/feed/' );
	$maxitems = 0;

	if ( ! is_wp_error( $rss ) ) {

		$maxitems = $rss->get_item_quantity( 5 ); 
		$rss_items = $rss->get_items( 0, $maxitems );

	}

?>

	<ul>
	
	<?php if ( $maxitems == 0 ) { ?>
		
		<li><?php _e( 'No items', 'wm-dashboard' ); ?></li>

	<?php } else { ?>

			<?php foreach ( $rss_items as $item ) { ?>

				<li>
					<div class="item-container" style="display:flex;align-items:top;border:1px solid #eee;box-shadow:1px 1px 6px 0px rgba(0,0,0,0.1);margin:8px 0;padding:10px;">
						<div class="item">
							<p>
								<strong>
									<a href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php printf( __( 'Posted %s', 'wm-dashboard' ), $item->get_date('j F Y | g:i a') ); ?>" target="_blank">
										<?php echo esc_html( $item->get_title() ); ?>
									</a>
								</strong>
							</p>
							<div>
								<?php echo $item->get_description(); ?>
							</div>
							<p style="color:#aaa;">
								<span class="dashicons dashicons-admin-post" style="font-size: 14px;margin-top: 3px;" aria-hidden="true"></span> <span style="font-size:12px"><?php printf( __( 'Posted %s', 'wm-dashboard' ), $item->get_date('j F Y | g:i a') ); ?></span>
							</p>
						</div>
					</div>
				</li>

			<?php } ?>

	<?php } ?>

	</ul>
	
	<p class="textright">
		<a class="gf_dashboard_button button" href="https://whiteboard-mktg.com/blog/" target="_blank"><?php _e('View All WM News', 'wm-dashboard'); ?></a>
	</p>

<?php

}

?>