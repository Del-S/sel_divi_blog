<?php
class WP_Widget_Recent_Posts_Edited extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries_edited', 'description' => __( "Your site&#8217;s most recent Posts.") );
		parent::__construct('recent-posts-edited', __('Recent Posts')." ".__('Edited'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries_edited';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;
        $show_categories = isset( $instance['show_categories'] ) ? $instance['show_categories'] : false;
        $display_popular_posts = isset( $instance['display_popular_posts'] ) ? $instance['display_popular_posts'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
        $query_args = array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true);
        if($display_popular_posts) { $args['orderby'] = 'comment_count';  }
		$r = new WP_Query( apply_filters( 'widget_posts_args', $query_args ) );

		if ($r->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
			<?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
                <div class="thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array(150, 150) ); ?></a></div>
			<?php endif; ?>
                
                <div class="post-info">
                    <a class="post-link" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
            <?php if ( $show_categories ) : ?>
                    <p class="categories">
                        <?php echo get_the_category_list( ', ', '', get_the_ID() ); ?>
                    </p>
            <?php endif; ?>
			<?php if ( $show_date ) : ?>
				    <span class="post-date"><?php echo get_the_date(); ?></span>
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

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['show_categories'] = isset( $new_instance['show_categories'] ) ? (bool) $new_instance['show_categories'] : false;
        $instance['display_popular_posts'] = isset( $new_instance['display_popular_posts'] ) ? (bool) $new_instance['display_popular_posts'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries_edited']) )
			delete_option('widget_recent_entries_edited');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
        $show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : false;
        $display_popular_posts = isset( $instance['display_popular_posts'] ) ? (bool) $instance['display_popular_posts'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_categories ); ?> id="<?php echo $this->get_field_id( 'show_categories' ); ?>" name="<?php echo $this->get_field_name( 'show_categories' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_categories' ); ?>"><?php _e( 'Display post categories?' ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $display_popular_posts ); ?> id="<?php echo $this->get_field_id( 'display_popular_posts' ); ?>" name="<?php echo $this->get_field_name( 'display_popular_posts' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'display_popular_posts' ); ?>"><?php _e( 'Display popular posts?' ); ?></label></p>
<?php
	}
}

function WP_Widget_Recent_Posts_Edited_Init() {
	register_widget('WP_Widget_Recent_Posts_Edited');
}

add_action('widgets_init', 'WP_Widget_Recent_Posts_Edited_Init'); 