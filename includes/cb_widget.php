<?php
/**
 * Reusable Content & Text Blocks
 */

class cb_widget extends WP_Widget {
    
    protected $plugin;
    
	public function __construct() {
	    $this->plugin = new cb_main();
		parent::__construct( 'content_block', __( 'Content Block', 'block' ), array( 'description' => __( 'Place a content/text block in the selected area.', 'block' ) ) );
	}

	function get_widget_info( $instance, &$title, &$cbid, &$para ) {
		$title = '';

		if ( isset( $instance[ 'title' ] ) ) {
			$title = strip_tags( strval( $instance[ 'title' ] ) );
		}

		$cbid = '';

		if ( isset( $instance[ 'cbid' ] ) ) {

			$cbid = $this->plugin->get_clean_id( $instance[ 'cbid' ] );

			if ( $cbid === false ) {
				$cbid = '';
			} elseif ( ! isset( $this->plugin->$content_block_list[ $cbid ] ) ) {
				$cbid = '';
			}
		}

		$para = '';

		if ( isset( $instance[ 'para' ] ) ) {
			$para = $this->plugin->get_clean_para( $instance[ 'para' ] );
		} elseif ( isset( $instance[ 'wpautop' ] ) ) {
			$para = $this->plugin->get_clean_para( $instance[ 'wpautop' ] );
		}

		return ( $cbid != '' );
	}

	public function widget( $args, $instance ) {

		$title = '';
		$cbid  = '';
		$para  = '';

		if ( $this->get_widget_info( $instance, $title, $cbid, $para ) ) {

			echo $args[ 'before_widget' ];

			if ( ! empty( $title ) ) {
				echo $args[ 'before_title' ] . apply_filters( 'widget_title', $title ) . $args[ 'after_title' ];
			}

			echo $this->plugin->get_block_by_id( $cbid, $para );

			echo $args[ 'after_widget' ];
		}
	}

	public function form( $instance ) {
		$title = '';
		$cbid  = '';
		$para  = '';

		$this->get_widget_info( $instance, $title, $cbid, $para );

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' ) . '">' . esc_html( __( 'Title:', 'block' ) ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'cbid' ) . '">' . esc_html( __( 'Content Block:', 'block' ) ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'cbid' ) . '" name="' . $this->get_field_name( 'cbid' ) . '">';
		echo '<option value=""' . ( ( $cbid == '' ) ? ' selected="selected"' : '' ) . '></option>';
		foreach ( $this->plugin->content_block_list as $content_block_id => $content_block_title ) {
			echo '<option value="' . esc_attr( $content_block_id ) . '"' . ( ( $cbid == $content_block_id ) ? ' selected="selected"' : '' ) . '>' . esc_html( $content_block_title ) . '</option>';
		}
		echo '</select>';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'para' ) . '">' . esc_html( __( 'Content Filtering / Paragraph Tags:', 'block' ) ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'para' ) . '" name="' . $this->get_field_name( 'para' ) . '">';
		foreach ( $this->plugin->para_list as $para_key => $para_value ) {
			if ( $para_key == 'none' ) {
				$para_key = '';
			}
			echo '<option value="' . esc_attr( $para_key ) . '"' . ( ( $para_key == $para ) ? ' selected="selected"' : '' ) . '>' . esc_html( __( $para_value, 'block' ) ) . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	public function update( $new_instance, $old_instance ) {
		$title = '';
		$cbid  = '';
		$para  = '';

		$this->get_widget_info( $new_instance, $title, $cbid, $para );

		$instance = array(
			'title' => $title,
			'cbid'  => $cbid,
			'para'  => $para
		);

		return $instance;
	}
}
