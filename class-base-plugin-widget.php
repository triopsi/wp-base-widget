<?php
/**
 * Plugin Name: Pluginname
 * Plugin URI: Plugin URL
 * Description: Description
 * Version: 1.0
 * Author: triopsi
 * Author URI: https://wiki.profoxi.de
 * Version: 1.0.0
 */

/**
 * Widget - DEMO
 */
class PLUGINSLUG_Widget extends WP_Widget {

	/**
	 * Contruct class of the widget
	 */
	public function __construct() {
		parent::__construct(
			// Base ID of your widget.
			'Base-plugin',
			// Widget name will appear in UI.
			__( 'WP Base Plugin', 'plugin-slug' ),
			// Widget description.
			array(
				'description' => __( 'Description', 'plugin-slug' ),
			)
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
	}

	/**
	 * Creating widget front-end.
	 * This is where the action happens.
	 *
	 * @param array  $args settings values.
	 * @param object $instance actual values.
	 */
	public function widget( $args, $instance ) {

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $args );
		$variable1 = $instance['variable1'];
		
		// before and after widget arguments are defined by themes.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $before_widget;

		?>
		<div class="plugin-content">
			<?php echo esc_html( $variable1 ); ?>
		</div>
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_widget;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook_suffix suffix of the hook.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}

	/**
	 * Print scripts.
	 */
	public function print_scripts() {
		?>
		<script>
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer.
							$(this).trigger( 'change' );
						}, 3000 )
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );
		</script>
		<?php
	}



	/**
	 * Widget Backend
	 *
	 * @param object $instance acuel values.
	 */
	public function form( $instance ) {

		// Defaults.
		$defaults = array(
			'variable1'       => __( 'Variable 1', 'plugin-slug' ),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		// Widget admin form.
		?>
		<div style="margin-bottom:10px;">
		<label for="<?php echo esc_attr( $this->get_field_id( 'variable1' ) ); ?>"><?php esc_html_e( 'Cell 1:', 'plugin-slug' ); ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'variable1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'variable1' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['variable1'] ); ?>" />
		</div>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new.
	 *
	 * @param array $new_instance new user values.
	 * @param array $old_instance old user vlaues.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['variable1'] = ( ! empty( $new_instance['variable1'] ) ) ? wp_strip_all_tags( $new_instance['variable1'] ) : '';
		return $instance;
	}

}

/**
 * Register and load the widget.
 */
function th_load_widget() {
	register_widget( 'PLUGINSLUG_Widget' );
}
add_action( 'widgets_init', 'th_load_widget' );
