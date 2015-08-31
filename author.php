<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php echo do_shortcode('[et_pb_section fullwidth="on" specialty="off"][et_pb_fullwidth_header title="<span>'.get_the_author().'</span>" text_orientation="center" content_orientation="center" image_orientation="center" module_class="title-with-sep post_heading"][/et_pb_fullwidth_header][/et_pb_section]'); ?>
			<div id="left-area">
				<div class="et_pb_column et_pb_column_2_3 full_width">
					<div class="et_pb_blog_grid custom_blog" data-columns>
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 400 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 250 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						elseif ( 'on' == et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb  ) : ?>
							<div class="et_pb_image_container">
								<a href="<?php the_permalink(); ?>">
									<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
								</a>
							</div>
					<?php
						endif;
					} ?>
						<div class="post-meta-wrapper">

						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<?php
						et_divi_post_meta();

						if ( 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) )
							truncate_post( 270 );
						else
							the_content();
					?>

					</article>

			<?php endwhile; ?>

					</div> <!-- .et_pb_column_2_3 -->
				</div> <!-- .et_pb_blog_grid -->

			<?php
				if ( function_exists( 'wp_pagenavi' ) )
					wp_pagenavi();
				else
					get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>

			</div> <!-- #left-area -->

			<?php
				$author_thumb_link = get_simple_local_avatar(get_the_author_meta('ID'));
				$author_thumb_link = preg_match("/src='(.*?)'/i", $author_thumb_link, $match);
				$author_thumb_link = $match[1];
				$author_description = get_the_author_meta( 'description' );
				$author_name = get_the_author();
				echo do_shortcode('[author] [author_image timthumb="off"]'.esc_url($author_thumb_link).'[/author_image] [author_info]<h2 class="vcard author"><span class="fn">'.$author_name.'</span></h2>'.$author_description.'[/author_info] [/author]');
			?>

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>