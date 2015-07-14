<?php class ETSocialLinksWidget extends WP_Widget
{
	function ETSocialLinksWidget(){
		parent::WP_Widget( false, $name='ET Social Links Widget' );
	}

	/* Displays the Widget in the front-end */
	function widget( $args, $instance ){
		echo $before_widget;
        
        ?>
		<div id="et-footer-social">
            <?php 
            if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
                get_template_part( 'includes/social_icons', 'footer' );
            }
            ?>
        </div> <!-- #et-footer-social -->
	    <?php
        
		echo $after_widget;
	}

	/*Saves the settings. */
	function update( $new_instance, $old_instance ){
        $instance = $old_instance;

		return $instance;
	}

	/*Creates the form for the widget in the back-end. */
	function form( $instance ){
        echo '<p>This widget displays Elegant Themes social icons.</p>';
	}

}// end AdsenseWidget class

function ETSocialLinksWidgetInit() {
	register_widget('ETSocialLinksWidget');
}

add_action('widgets_init', 'ETSocialLinksWidgetInit'); ?>