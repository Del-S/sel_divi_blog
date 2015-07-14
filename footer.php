<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		    <?php /* if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; */ ?>

				<div id="footer-bottom">
					<div class="container clearfix">
						<p id="footer-info">2014 - 2015 &copy; el nino parfum, s.r.o.</p>
                        <p id="el-logo"><a href="//www.elnino.cz/"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/elnino-logo.png" alt="Elnino.cz" title="Elnino.cz" /></a></p>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

<?php if( is_front_page() || is_search() || is_category() || is_archive() ) : ?>

            <?php 
                global $wp;
                $permalink = "";
                if(is_front_page()) { $permalink = home_url( $wp->request ); }
                else if(is_search()) { $permalink = add_query_arg( $wp->query_string, '', home_url( $wp->request ) ); }
                else if(is_category() || is_archive()) { $permalink = add_query_arg( '', '', home_url( $wp->request ) ); }

                $title = wp_title('',false); 

                $permalink_raw = rawurlencode( $permalink );
			    $title_raw = rawurlencode( html_entity_decode( $title ) );
                $media_url = '';
                $encoded_space = rawurlencode( ' ' );
            ?>

            <div class="et_social_sidebar_networks et_social_visible_sidebar et_social_slideright et_social_animated et_social_rectangle et_social_sidebar_flip et_social_mobile_on" style="top: 87.5px;">
					<ul class="et_social_icons_container"><li class="et_social_facebook">
									<a href="<?php 
                                          echo sprintf( 'http://www.facebook.com/sharer.php?u=%1$s&t=%2$s',
						                  esc_attr( $permalink_raw ),
						                  esc_attr( $title_raw )); ?>" class="et_social_share" rel="nofollow" data-social_name="facebook" data-post_id="555" data-social_type="share">
										<i class="et_social_icon et_social_icon_facebook"></i>
										<span class="et_social_overlay"></span>
									</a>
								</li><li class="et_social_twitter">
									<a href="<?php echo sprintf( 'http://twitter.com/home?status=%2$s%3$s%1$s%4$s',
						                  esc_attr( $permalink_raw ),
						                  esc_attr( $title_raw ),
						                  $encoded_space,
						                  ''); ?>" class="et_social_share" rel="nofollow" data-social_name="twitter" data-post_id="555" data-social_type="share">
										<i class="et_social_icon et_social_icon_twitter"></i>
										<span class="et_social_overlay"></span>
									</a>
								</li><li class="et_social_googleplus">
									<a href="<?php echo sprintf( 'https://plus.google.com/share?url=%1$s&t=%2$s',
						                  esc_attr( $permalink_raw ),
						                  esc_attr( $title_raw )); ?>" class="et_social_share" rel="nofollow" data-social_name="googleplus" data-post_id="555" data-social_type="share">
										<i class="et_social_icon et_social_icon_googleplus"></i>
										<span class="et_social_overlay"></span>
									</a>
								</li></ul>
					<span class="et_social_hide_sidebar et_social_icon"></span>
				</div>

<?php endif ?>

	<?php wp_footer(); ?>
</body>
</html>