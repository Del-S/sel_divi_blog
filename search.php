<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php
			$search = sprintf( __( 'Search results for "%s"', $themename ), esc_attr( get_search_query() ) );
			$search = str_replace('"','\'',$search);
			echo do_shortcode('[et_pb_section fullwidth="on" specialty="off"][et_pb_fullwidth_header title="<span>'.$search.'</span>" text_orientation="center" content_orientation="center" image_orientation="center" module_class="title-with-sep post_heading"][/et_pb_fullwidth_header][/et_pb_section]'); ?>

			<div id="left-area">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( array('et_pb_post', 'clearfix' ) ); ?>>

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
							truncate_post( 180 );
						else
							the_content();
					?>
					<?php
						echo sprintf( ' <a href="%1$s" class="more-link" >%2$s</a>' , esc_url( get_permalink() ), __( 'read more', 'et_builder' ) );
					?>

						</div>
					</article> <!-- .et_pb_post -->
			<?php
					endwhile;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>