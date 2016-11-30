<?php
/**
 * Widget API: WIP_Widget_Recent_Chapters class
 *
 * @package Generatpress
 * @subpackage Widgets
 * @since 1.0.0
 */
class WIP_Widget_Recent_Chapters extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_chapters',
			'description' => __( 'Your site&#8217;s most recent Chapters.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-chapters', __( 'Recent Chapters' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_chapters';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Chapters' );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_desc = isset( $instance['show_desc'] ) ? $instance['show_desc'] : false;

		$r = new WP_Query( array(
			'post_type'			  => 'chapter',	
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) );

		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); 
			$num = get_post_meta( get_the_ID(), 'wip_num', true);
			$terms = get_the_terms( get_the_ID(), 'season' ); 
			$term = array_pop($terms);

			$img_id = get_post_thumbnail_id( get_the_ID() );
			$img = wp_get_attachment_image_src( $img_id, 'thumbnail' );
			$img_alt = get_post_meta($img_id,'_wp_attachment_image_alt', true);
			$img_title = get_post_meta($img_id,'_wp_attachment_image_title', true);
		?>
			<li class="rchapter">
				<div class="rch-img">
					<?php if ( $img ) : ?>
					<a href="<?php the_permalink(); ?>"><img src="<?php echo $img[0];?>" alt="<?php echo $img_alt;?>" title="<?php echo $img_title;?>"/></a>
				<?php else: ?>
					<a href="<?php the_permalink(); ?>"><img src="<?php echo $img[0];?>" alt="<?php echo $img_alt;?>" /></a>
				<?php endif; ?>
				</div>				
				<div class="rch-desc">
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
					<?php if ( $show_desc ) : ?>
						<p class="post-date"><?php echo $term->name.__(' capítulo ').$num; ?></p>
					<?php endif; ?>	
				</div>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_desc'] = isset( $new_instance['show_desc'] ) ? (bool) $new_instance['show_desc'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_desc = isset( $instance['show_desc'] ) ? (bool) $instance['show_desc'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of chapters to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_desc ); ?> id="<?php echo $this->get_field_id( 'show_desc' ); ?>" name="<?php echo $this->get_field_name( 'show_desc' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_desc' ); ?>"><?php _e( 'Display chapter description?' ); ?></label></p>
<?php
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "WIP_Widget_Recent_Chapters" );' ) );
