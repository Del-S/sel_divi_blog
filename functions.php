<?php
// Add custom widget and more..
function el_et_setup_theme() {
    $template_directory = get_stylesheet_directory();
    include( $template_directory . '/includes/widgets.php' );
    include( $template_directory . '/includes/functions/comments.php' );
}
add_action( 'after_setup_theme', 'el_et_setup_theme' );

function divi_changes() {
    if (!is_admin()) {
        wp_register_script( 'js-divi-changes', get_stylesheet_directory_uri().'/js/divi-changes.js' , array('jquery'), '0.1', true );
        //wp_localize_script( 'js-divi-changes', 'data', $data );
        wp_enqueue_script( 'js-divi-changes' );
        if(is_author() || is_tag() || is_date() || is_tax() || is_post_type_archive()) {
            $theme_version = et_get_theme_version();
            wp_register_script( 'js-custom-salvattore', get_template_directory_uri() . '/js/salvattore.min.js', array(), $theme_version, true );
            wp_enqueue_script( 'js-custom-salvattore' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'divi_changes' );


function et_builder_add_main_elements_custom() {
    $template_directory = get_stylesheet_directory();
    require $template_directory . '/includes/builder/slider-module.php';
}
$action_hook = is_admin() ? 'wp_loaded' : 'wp';
add_action( $action_hook, 'et_builder_add_main_elements_custom' );

// Replace link on WP logo
function put_my_url(){
	return "http://www.selfino.cz/"; // your URL here
}
add_filter('login_headerurl', 'put_my_url');

// Remove Jetpack OGD
add_filter( 'jetpack_enable_opengraph', '__return_false', 99 );

// Remove ET Google Fonts
function wpdocs_dequeue_style() {
    wp_dequeue_style( 'open-sans-css' );
}
add_action( 'wp_print_scripts', 'wpdocs_dequeue_style', 100 );

function dequeue_unused_styles() {
    wp_dequeue_style( 'et_monarch-open-sans' );
    wp_dequeue_style( 'et-open-sans-700' );   
    wp_dequeue_style( 'open-sans' );
    wp_dequeue_style( 'google_font_open_sans' );
    wp_dequeue_style( 'google_font_open_sans_condensed' );
}
add_action('wp_enqueue_scripts', 'dequeue_unused_styles', 100 );
add_action('wp_print_scripts', 'dequeue_unused_styles', 100 );

// Change wp_mail() from default adress (wp@domain.xx) to wordpress@elnino.cz
add_filter('wp_mail_from','change_wp_default_email');
function change_wp_default_email($content_type) {
  return 'wordpress@elnino.cz';
}

// Add Google Fonts as we need
function google_fonts(){
    wp_enqueue_style( 'google_font_1', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700,300&subset=latin,latin-ext' );
    wp_enqueue_style( 'google_font_2', 'http://fonts.googleapis.com/css?family=Oswald:400,300,700&subset=latin,latin-ext');
	//wp_enqueue_style( 'google_font_2', 'http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,latin-ext' );
}
add_action( 'wp_enqueue_scripts', 'google_fonts', 9 );
// Google Fonts - end

// Remove information about WP version
remove_action('wp_head', 'wp_generator');
function completely_remove_wp_version() {
	return '';
}
add_filter('the_generator', 'completely_remove_wp_version');

// Del_S: Adding styles to ePanel and website header
add_action('init', 'remove_et_customizer');
function remove_et_customizer() {
    remove_action( 'wp_head', 'et_divi_add_customizer_css' );
}

// Redirect to Post If Search Results Return One Post.
add_action('template_redirect', 'redirect_single_post');
function redirect_single_post() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            exit;
        }
    }
}

// Remove HomePage from search (change on live !!!!!!)
add_action('pre_get_posts','exclude_posts_from_search');
function exclude_posts_from_search( $query ){

    if( $query->is_main_query() && is_search() ){
        $post_ids = array(2874);
        $query->set('post__not_in', $post_ids);
    }

}

/* UTM codes for outbound links js */   
add_filter('last_url_segment', 'last_url_segment', 10, 2);

/* 
* Settings for UTM codes
* Most of the settings is mainly for fron-end links.
* Facebook and social links are changed only by array utm. Does not care about domains.
*/
$campain = apply_filters('last_url_segment', $_SERVER['REQUEST_URI'], $_SERVER['HTTP_HOST']);
$domains = array( 'parfemy-elnino.cz', 'facebook.com' ); // Works with http:// and www. Remove last / from URL please.
$utm = array( 'utm_source' => 'selfino', 'utm_medium' => 'free_post', 'utm_campaign' => $campain ); 

/* jQuery front end links change */
function utm_codes() {
    global $domains, $utm;
    if(!is_admin()) {        
        /* register, localize and enque javascript for utm codes */    
        if(!empty($domains)) { 
            $data = array( 'domains' => json_encode($domains), 'utm_data' => json_encode($utm) );

            wp_register_script( 'js-outbound-links-utm', get_stylesheet_directory_uri().'/js/outbound-links.js' , array('jquery'), '1.2', true );
            wp_localize_script( 'js-outbound-links-utm', 'data', $data );
            wp_enqueue_script( 'js-outbound-links-utm' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'utm_codes' );

/* change og:url (facebook) for wp seo */
function utm_change_og_seo( ) {
    global $utm;
    
    $utm_str = "?";
    foreach($utm as $k => $v) {
        $utm_str .= $k."=".$v."&";
    }
    $utm_str = substr($utm_str, 0, -1);
    
    $link = "http://". $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $link .= $utm_str;
    $link = esc_url( $link );
    return $link;
}
add_filter( 'wpseo_opengraph_url', 'utm_change_og_seo' );

/* change og:url (facebook) for jatpack */
function utm_change_og_jetpack( $tags ) {
    global $utm;
    
    $utm_str = "?";
    foreach($utm as $k => $v) {
        $utm_str .= $k."=".$v."&";
    }
    $utm_str = substr($utm_str, 0, -1);
    
    $url = $tags['og:url'];
    unset($tags['og:url']);
    $url .= $utm_str;
    $tags['og:url'] = esc_url($url);
}
add_filter( 'jetpack_open_graph_tags', 'utm_change_og_jetpack' );

/* 
* changing url for share and automatic share (post creation)
* creating shortlink via wp.me
*/
function utm_change_url( ) {
    global $utm;

    $utm_str = "?";
    foreach($utm as $k => $v) {
        $utm_str .= $k."=".$v."&";
    }
    $utm_str = substr($utm_str, 0, -1);
    
    $link = get_permalink( $post->ID );
    $link .= $utm_str;
    $link = esc_url( apply_filters( 'get_shortlink', $link, 0) );  // calc shortlink
    return $link;
}
add_filter( 'wpas_post_url', 'utm_change_url' );
add_filter( 'sharing_permalink', 'utm_change_url' ); /* change sharing link */

/* function for last segment of url or host (utm_campain) */
function last_url_segment($url, $host) {
    $path = parse_url($url, PHP_URL_PATH);
    $pathTrim = trim($path, '/');
    $segments = explode('/', $pathTrim);

    if (substr($path, -1) !== '/') {
        array_pop($segments);
    }
    
    /* If return is empty add domain name as last segment */
    $return = end($segments);
    if(empty($return)) { 
        $domain = $host;
        $domain = preg_replace('/^www\./i', '', $domain); // removes www.
        $domain = str_replace('.', '_', $domain);
        $return = $domain;
        
    }
    return $return;
}
/* UTM codes - end */

/* Comment edits */
function elnino_comment($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter( 'comment_form_default_fields', 'elnino_comment' );

function remove_textarea($defaults) {
    $defaults['comment_field'] = " ";
    $move = $defaults['comment_notes_before'];
    $defaults['comment_notes_before'] = '';
    $save = $defaults['comment_notes_after'];
    $defaults['comment_notes_after'] = $move.$save;
    return $defaults;
}
add_filter( 'comment_form_defaults', 'remove_textarea' ); 

function add_textarea()
{
    if(get_comments_number() == 0) { echo '<p class="comment-notes">Příspěvek zatím nebyl okomentován. Buďte první kdo přidá komentář.</p>'; }
    echo '<p class="comment-form-comment"><label for="comment" style="display: none;">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
}
add_action( 'comment_form_top', 'add_textarea' );

/* Add something after content (link to comment and share alert) */
function like_share_alert($content) {
    if(is_single()) {
        $content .= '<p class="comment_link"><a href="#respond" class="icon-button paper-icon">Okomentujte tento příspěvek<span class="et-icon"></span></a></p>';
        $content .= '<p class="share_alert">Sdílejte tento příspěvek pomocí:</p>';
    }
    return $content;
}
add_filter( 'the_content', 'like_share_alert', 9);
/* Comments - end */

/* Divi changes */
if ( ! function_exists( 'et_pb_get_comments_popup_link' ) ) :
    function et_pb_get_comments_popup_link( $zero = false, $one = false, $more = false ){
        $id = get_the_ID();
        $number = get_comments_number( $id );

        // Add facebook comment count
        if( 0 == $number ) {
            $url = get_permalink(get_the_ID());
            $json = json_decode(file_get_contents('https://graph.facebook.com/?ids=' . $url));
            $number = isset($json->$url->comments) ? $json->$url->comments : 0;
        }

        if ( 0 == $number && !comments_open() && !pings_open() ) return;

        if ( $number > 1 )
            $output = str_replace( '%', number_format_i18n( $number ), ( false === $more ) ? __( '% Comments', $themename ) : $more );
        elseif ( $number == 0 )
            $output = ( false === $zero ) ? __( 'No Comments', 'et_builder' ) : $zero;
        else // must be one
            $output = ( false === $one ) ? __( '1 Comment', 'et_builder' ) : $one;

        return '<span class="comments-number">' . '<a href="' . esc_url( get_permalink() . '#respond' ) . '">' . apply_filters( 'comments_number', $output, $number ) . '</a>' . '</span>';
    }
endif;

if ( ! function_exists( 'et_get_first_video' ) ) :
    function et_get_first_video() {
        $first_video  = '';
        $custom_fields = get_post_custom();
        $video_width  = (int) apply_filters( 'et_blog_video_width', 1080 );
        $video_height = (int) apply_filters( 'et_blog_video_height', 630 );

        foreach ( $custom_fields as $key => $custom_field ) {
            if ( 0 !== strpos( $key, '_oembed_' ) ) {
                continue;
            }

            $first_video = $custom_field[0];

            $first_video = preg_replace( '/<embed /', '<embed wmode="transparent" ', $first_video );
            $first_video = preg_replace( '/<\/object>/','<param name="wmode" value="transparent" /></object>', $first_video );

            $first_video = preg_replace( "/width=\"[0-9]*\"/", "width={$video_width}", $first_video );
            $first_video = preg_replace( "/height=\"[0-9]*\"/", "height={$video_height}", $first_video );

            break;
        }

        if ($first_video == '{{unknown}}') { $first_video = ''; }

        if ( '' === $first_video && has_shortcode( get_the_content(), 'video' )  ) {
            $regex = get_shortcode_regex();
            preg_match( "/{$regex}/s", get_the_content(), $match );

            $first_video = preg_replace( "/width=\"[0-9]*\"/", "width=\"{$video_width}\"", $match[0] );
            $first_video = preg_replace( "/height=\"[0-9]*\"/", "height=\"{$video_height}\"", $first_video );

            add_filter( 'the_content', 'et_delete_post_video' );

            $first_video = do_shortcode( et_pb_fix_shortcodes( $first_video ) );
        }

        if ( '' === $first_video && has_shortcode( get_the_content(), 'youtube' )  ) {
            $regex = get_shortcode_regex();
            preg_match( "/{$regex}/s", get_the_content(), $match );

            $first_video = preg_replace( "/width=\"[0-9]*\"/", "width=\"{$video_width}\"", $match[0] );
            $first_video = preg_replace( "/height=\"[0-9]*\"/", "height=\"{$video_height}\"", $first_video );

            add_filter( 'the_content', 'et_delete_post_video' );

            $first_video = do_shortcode( et_pb_fix_shortcodes( $first_video ) );
        }

        return ( '' !== $first_video ) ? $first_video : false;
    }
endif;
?>