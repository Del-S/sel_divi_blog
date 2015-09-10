<?php if ( ! function_exists( 'et_custom_comments_display' ) ) :
function et_custom_comments_display($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
			<div class="comment_postinfo clearfix">
                <div class="comment_avatar">
				    <?php echo get_avatar( $comment, $size = '80' ); ?>
			    </div>
                <?php
                    $et_comment_reply_link = get_comment_reply_link( array_merge( $args, array(
                        'reply_text' => esc_attr__( 'Reply', 'Divi' ),
                        'depth'      => (int) $depth,
                        'max_depth'  => (int) $args['max_depth'],
                    ) ) );
                    if ( $et_comment_reply_link ) echo '<span class="reply-container">' . $et_comment_reply_link . '</span>'; 
                ?>
				<?php printf( '<div class="fn">%s</div>', get_comment_author_link() ); ?>
				<div class="comment_date">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( 'on %1$s at %2$s', 'Divi' ), get_comment_date(), get_comment_time() );
				?>
				</div>
				<?php edit_comment_link( esc_html__( '(Edit)', 'Divi' ), ' ' ); ?>
			</div> <!-- .comment_postinfo -->

			<div class="comment_area">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="moderation"><?php esc_html_e('Your comment is awaiting moderation.','Divi') ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-content clearfix">
				<?php $comment_text = get_comment_text();
                    $length = apply_filters('comment_length');
                    if($length == null || $length == 0 || $length == '') { $length = 250; }
                    if( strlen($comment_text) > $length) {
                        $split[0] = substr($comment_text, 0, $length);
                        $split[1] = substr($comment_text, $length);
                        $comment_id = $comment->comment_ID;
                        $comment_link_text = apply_filters('comment_link_text');
                        if($comment_link_text == null || $comment_link_text == '') { $comment_link_text = 'Show more'; }
                        
                        $output = '<div class="comment_text id_'.$comment_id.'">
                            <p>'.$split[0].'
                                <span class="text_more_show">...</span>
                                <span class="text_hide">'.$split[1].'</span>
                            </p>
                            <span class="comment_hidden"><a class="see_more_link" href="#" role="button">'.$comment_link_text.'</a></span>
                        </div>';
                    } else {
                        $output = '<div class="comment_text id_'.$comment_id.'">
                            <p>'.$comment_text.'</p>
                        </div>';
                    }
                    echo $output;
                ?>
				</div> <!-- end comment-content-->
			</div> <!-- end comment_area-->
		</article> <!-- .comment-body -->
<?php }
endif;