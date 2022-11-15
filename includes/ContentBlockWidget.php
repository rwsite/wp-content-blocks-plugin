<?php
/**
 * Reusable Content & Text Blocks
 */

class ContentBlockWidget extends WP_Widget {
    
    protected $plugin;

	/**
	 * Sets up a new Meta widget instance.
	 */
	public function __construct() {
		$this->plugin = new ContentBlock();

		$widget_ops = [
			'classname'                   => 'block_meta',
			'description'                 => __( 'Place a content/text block in the selected area.', 'block' ),
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		];

		parent::__construct( 'wp_block', __( 'Content Block', 'block' ), $widget_ops );
	}

	/**
	 * Saves the settings for all instances of the widget class.
	 *
	 * @since 2.8.0
	 *
	 * @param array $settings Multi-dimensional array of widget instance settings.
	 */
	public function save_settings( $settings ) {
		$settings['_multiwidget'] = 1;
		update_option( $this->option_name, $settings );
	}

	public function get_widget_info( $instance, &$title, &$cbid ) {

		$title = '';
		if ( isset( $instance[ 'title' ] ) ) {
			$title = strip_tags( strval( $instance[ 'title' ] ) );
		}

		$cbid = '';
		if ( isset( $instance[ 'cbid' ] ) ) {
			$cbid = $this->plugin->get_clean_id( $instance[ 'cbid' ] );
			if ( $cbid === false ) {
				$cbid = '';
			} elseif ( ! isset( $this->plugin->content_block_list[ $cbid ] ) ) {
				$cbid = '';
			}
		}

		return ( $cbid != '' );
	}


	/**
	 * @param $args
	 * @param $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$title = '';
		$cbid  = '';

		if ( $this->get_widget_info( $instance, $title, $cbid ) ) {
			echo $args[ 'before_widget' ];
			if ( !empty($title) ) {
				echo $args[ 'before_title' ] . apply_filters( 'widget_title', $title ) . $args[ 'after_title' ];
			}
			echo $this->plugin->get_block_by_id( $cbid );
			echo $args[ 'after_widget' ];
		}
	}

	/**
	 * Form
	 *
	 * @param $instance
	 * @return string|void
	 */
	public function form( $instance ) {

		$title = '';
		$cbid  = '';

		$this->get_widget_info( $instance, $title, $cbid );

		$this->plugin->get_list();
		$this->plugin->get_para_list();

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' ) . '">' . esc_html( __( 'Title:', 'block' ) ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" />';
		echo '</p>';
		echo '<p>';

		echo '<label for="' . $this->get_field_id( 'cbid' ) . '">' . esc_html( __( 'Content Block:', 'block' ) ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'cbid' ) . '" name="' . $this->get_field_name( 'cbid' ) . '">';
		echo '<option value=""' . ( ( $cbid == '' ) ? ' selected="selected"' : '' ) . '></option>';
		foreach ( $this->plugin->content_block_list as $content_block_id => $content_block_title ) {
			echo '<option value="' . esc_attr( $content_block_id ) . '"' . ( ( $cbid == $content_block_id ) ? ' selected="selected"' : '' ) . '>' . $content_block_id  .' - '. esc_html( $content_block_title ) . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	/**
	 * Update
	 *
	 * @param $new_instance
	 * @param $old_instance
	 *
	 * @return string[]
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
}
