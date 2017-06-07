<?php
/**
 * Adds CEMM_Widget widget.
 */
class CEMM_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cemm_widget', // Base ID
			esc_html__( 'CEMM Zon overzicht', 'cemm-demo-plugin' ), // Name
			array( 'description' => esc_html__( 'Overzicht van de zonnepanelen productie van de afgelopen maand.', 'cemm-demo-plugin' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		?>

		<div class="cemm-solar-info">
			<dl class="cemm-solar-items">
				<dt class="cemm-item-title">Realtime opbrengst</dt>
				<dd class="cemm-item-value"><span class="cemm-realtime-solar">-</span> Watt</dd>

				<dt class="cemm-item-title">Opbrengst vandaag</dt>
				<dd class="cemm-item-value"><span class="cemm-today-solar">-</span> kWh</dd>

				<dt class="cemm-item-title">Opbrengst deze maand</dt>
				<dd class="cemm-item-value"><span class="cemm-month-solar">-</span> kWh</dd>
			</dl>
		</div>


		<?php

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Mijn zonnepanelen', 'cemm-demo-plugin' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'cemm-demo-plugin' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class CEMM_Widget