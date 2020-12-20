<?php
/*
Plugin Name: EDD SL Requires PHP
Plugin URI:  https://github.com/alessandrotesoro/wp-user-manager/
Description: Set a minimum PHP version for EDD WordPress plugins.
Version:     1.0.0
Author:      Iain Poulson
Author URI:  https://polevaultweb.com
License:     GPLv3+
*/

add_action( 'add_meta_boxes', function () {
	remove_meta_box( 'edd_sl_readme_box', 'download', 'normal' );
	add_meta_box( 'edd_sl_readme_box', __( 'Download <code>readme.txt</code> Configuration', 'edd_sl' ), 'pvw_edd_sl_readme_meta_box_render', 'download', 'normal', 'default' );
}, 200 );

function pvw_edd_sl_readme_meta_box_render() {
	global $post;

	edd_sl_render_readme_cache_status();

	edd_sl_readme_meta_box_settings( $post->ID );

	if ( ! current_user_can( 'manage_shop_settings' ) ) {
		return;
	}

	$requires_php = get_post_meta( $post->ID, '_edd_requires_php', true );
	?>
	<p>
		<label for="edd_requires_php"><strong><?php _e( 'Minimum PHP Version Required', 'edd_sl' ); ?></strong></label>
		<span
			class="howto"><?php _e( 'What is the minimum PHP version requires for the plugin to work?', 'edd_sl' ); ?></span>
	</p>
	<p>
		<input type="text" name="_edd_requires_php" class="widefat" id="edd_requires_php"
		       value="<?php echo esc_attr( $requires_php ); ?>" size="50"/>
	</p>
	<?php
}

add_filter( 'edd_metabox_fields_save', function ( $fields ) {
	$fields[] = '_edd_requires_php';

	return $fields;
} );

add_filter( 'edd_sl_license_response', function ( $response, $download ) {
	$requires_php = get_post_meta( $download->ID, '_edd_requires_php', true );
	if ( ! empty( $requires_php ) ) {
		$response['requires_php'] = $requires_php;
	}

	return $response;
}, 10, 2 );