<?php
if ( is_search() || is_author() || is_date() ) { $class = " pagi-bottom"; }
$args = array('before_pagination' => '<div class="pagination clearfix'.$class.'">', 'after_pagination' => '</div>');
wp_simple_pagination($args); 
?>
<?php
/*<div class="pagination clearfix">
	<div class="alignleft"><?php next_posts_link(esc_html__('&laquo; Older Entries','Divi')); ?></div>
	<div class="alignright"><?php previous_posts_link(esc_html__('Next Entries &raquo;', 'Divi')); ?></div>
</div>*/
?>	
