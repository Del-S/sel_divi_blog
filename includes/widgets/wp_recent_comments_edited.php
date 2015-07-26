<?php
class WP_Widget_Recent_Comments_Edited extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_comments_edited', 'description' => __( 'Your site&#8217;s most recent comments.' ) );
		parent::__construct('recent-comments-edited', __('Recent Comments')." ".__('Edited'), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments_edited';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		add_action( 'comment_post', array($this, 'flush_widget_cache') );
		add_action( 'edit_comment', array($this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
	}

	public function recent_comments_style() {

		/**
		 * Filter the Recent Comments default widget styles.
		 *
		 * @since 3.1.0
		 *
		 * @param bool   $active  Whether the widget is active. Default true.
		 * @param string $id_base The widget ID.
		 */
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments_edited', 'widget');
	}

	public function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get('widget_recent_comments_edited', 'widget');
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		$output = '';

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        $show_avatar = ( ! empty( $instance['show_avatar'] ) ) ? $instance['show_avatar'] : 0;
		if ( ! $show_avatar )
			$show_avatar = 0;
        
        $show_content = ( ! empty( $instance['show_content'] ) ) ? $instance['show_content'] : 0;
		if ( ! $show_content )
			$show_content = 0;

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
        
        $content_length = ( ! empty( $instance['content_length'] ) ) ? absint( $instance['content_length'] ) : 120;
		if ( ! $content_length )
			$content_length = 120;

		/**
		 * Filter the arguments for the Recent Comments widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Comment_Query::query() for information on accepted arguments.
		 *
		 * @param array $comment_args An array of arguments used to retrieve the recent comments.
		 */
		$comments = get_comments( apply_filters( 'widget_comments_args', array(
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish'
		) ) );

		$output .= $args['before_widget'];
		if ( $title ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}

		$output .= '<ul id="recentcomments">';
		if ( $comments ) {
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

            $counter = 1;
			foreach ( (array) $comments as $comment) { 
				$output .= '<li class="recentcomments">';
                $output .= '<header class="clearfix">';
                $output .= '<span class="comment-count">'.$counter.'</span>';
                
                if($show_avatar) {
                    $output .= '<figure>';
                    if($comment->user_id != 0 || !empty($comment->user_id)) {
                        $output .= '<a href="'.get_author_posts_url($comment->user_id).'"> 
                        '.get_avatar( $comment, 40 ).'
                        </a>';
                    } else {
                        $output .= get_avatar( $comment, 40 );
                    }  
                    $output .= '</figure>';
                }
                
				$output .= '<span class="comment-author-link">' .get_comment_author_link(). '</span>';
				$output .= '<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>';
                $output .= '</header>';
                
                if($show_content) {
                    $output .= '<div class="comment-text">'.wp_trim_words( $comment->comment_content, $content_length, '...' ).'</div>';
                }
                
				$output .= '</li>';
                $counter++;
			}
		}
		$output .= '</ul>';
		$output .= $args['after_widget'];

		echo $output;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = $output;
			wp_cache_set( 'widget_recent_comments_edited', $cache, 'widget' );
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
        $instance['content_length'] = absint( $new_instance['content_length'] );
        $instance['show_avatar'] = ( isset($new_instance['show_avatar']) ) ? 1 : 0;
        $instance['show_content'] = ( isset($new_instance['show_content']) ) ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments_edited']) )
			delete_option('widget_recent_comments_edited');

		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $content_length = isset( $instance['content_length'] ) ? absint( $instance['content_length'] ) : 120;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['show_avatar'], true) ?> id="<?php echo $this->get_field_id('show_avatar'); ?>" name="<?php echo $this->get_field_name('show_avatar'); ?>" />
		<label for="<?php echo $this->get_field_id('show_avatar'); ?>"><?php _e('Show User Avatar?'); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['show_content'], true) ?> id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>" />
		<label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Show Comment Content?'); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <p><label for="<?php echo $this->get_field_id( 'content_length' ); ?>"><?php _e( 'Limit comment content length:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'content_length' ); ?>" name="<?php echo $this->get_field_name( 'content_length' ); ?>" type="text" value="<?php echo $content_length; ?>" size="3" /></p>
<?php
	}
}

function WP_Widget_Recent_Comments_Edited_Init() {
	register_widget('WP_Widget_Recent_Comments_Edited');
}

add_action('widgets_init', 'WP_Widget_Recent_Comments_Edited_Init'); 