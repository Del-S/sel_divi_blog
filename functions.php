<?php
// Add custom widget and more..
function el_et_setup_theme() {
    $template_directory = get_stylesheet_directory();
    include( $template_directory . '/includes/widgets.php' );
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

/* Divi changes */
?>