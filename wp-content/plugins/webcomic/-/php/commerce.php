<?php
/** Contains the WebcomicCommerce class.
 * 
 * @package Webcomic
 */

/** Handle the IPN log tool.
 * 
 * @package Webcomic
 */
class WebcomicCommerce extends Webcomic {
	/** Register hooks.
	 * 
	 * @uses WebcomicCommerce::admin_init()
	 * @uses WebcomicCommerce::admin_menu()
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
	
	/** Empty the current ipn log file.
	 * 
	 * @uses Webcomic::$dir
	 * @uses Webcomic::$error
	 * @hook admin_init
	 */
	public function admin_init() {
		global $blog_id;
		
		if ( isset( $_POST[ 'webcomic_commerce' ], $_POST[ 'empty_log' ] ) and wp_verify_nonce( $_POST[ 'webcomic_commerce' ], 'webcomic_commerce' ) ) {
			$logfile = self::$dir . sprintf( '-/log/ipn-%s.php', $blog_id ? $blog_id : 1 );
			
			if ( is_writable( $logfile ) ) {
				file_put_contents( $logfile, "<?php die; ?>\n" );
			} else {
				self::$error[] = __( 'Webcomic could not empty the log file. Please try again.', 'webcomic' );
			}
			
		}
	}
	
	/** Register submenu page for ipn log viewer.
	 * 
	 * @uses WebcomicCommerce::page()
	 * @hook admin_menu
	 */
	public function admin_menu() {
		add_submenu_page( 'tools.php', __( 'Webcomic Commerce', 'webcomic' ), __( 'Webcomic Commerce', 'webcomic' ), 'manage_options', 'webcomic-commerce', array( $this, 'page' ) );
	}
	
	/** Render the commerce tool page. */
	public function page() { 
		global $blog_id;
		
		$logfile = self::$dir . sprintf( '-/log/ipn-%s.php', $blog_id ? $blog_id : 1 );
		$log     = is_readable( $logfile ) ? str_replace( "<?php die; ?>\n", '', file_get_contents( $logfile ) ) : '';
		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"></div>
			<h2><?php _e( 'Webcomic Commerce', 'webcomic' ); ?></h2>
			<br>
			<table class="wp-list-table widefat fixed">
				<thead>
					<tr>
						<th><?php _e( 'Transaction', 'webcomic' ); ?></th>
						<th><?php _e( 'Item', 'webcomic' ); ?></th>
						<th><?php _e( 'Message', 'webcomic' ); ?></th>
						<th class="column-date"><?php _e( 'Date', 'webcomic' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ( $log ) {
							$i   = 0;
							$log = explode( "\n", $log );
							
							foreach ( $log as $l ) {
								if ( empty( $l ) ) {
									continue;
								} else {
									$l      = explode( "\t", $l );
									$l[ 1 ] = empty( $l[ 1 ] ) ? '' : strtotime( $l[ 1 ] );
									$error  = empty( $l[ 4 ] ) ? '' : ' style="color:#bc0b0b;font-weight:bold"';
									
									if ( empty( $l[ 2 ] ) ) {
										$l[ 2 ] = __( '- Shopping Cart -', 'webcomic' );
									}
								}
					?>
					<tr<?php echo $i % 2 ? '' : ' class="alternate"'; ?>>
						<td<?php echo $error; ?>><?php echo $l[ 0 ]; ?></td>
						<td<?php echo $error; ?>><?php echo $l[ 2 ]; ?></td>
						<td<?php echo $error; ?>><?php echo $l[ 3 ]; ?></td>
						<td<?php echo $error; ?>><?php echo empty( $l[ 1 ] ) ? '' : sprintf( '<abbr title="%s">%s</abbr>', date( __( 'Y/m/d g:i:s A', 'webcomic' ), $l[ 1 ] ), date( __( 'Y/m/d', 'webcomic' ), $l[ 1 ] ) ); ?></td>
					</tr>
					<?php $i++; } } else { ?>
					<tr>
						<td colspan="4" class="alternate"><p><?php _e( "Webcomic hasn't logged any commerce activity.", 'webcomic' ); ?></p></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php if ( $log ) { ?>
			<form method="post" style="float:right">
				<?php
					wp_nonce_field( 'webcomic_commerce', 'webcomic_commerce' );
					submit_button( __( 'Empty Log', 'webcomic' ), 'primary', 'empty_log' );
				?>
			</form>
			<?php } ?>
		</div>
		<?php
	}
}