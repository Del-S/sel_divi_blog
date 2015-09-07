<?php
class ET_Builder_Module_Slider_v2 extends ET_Builder_Module {
    function init() {
        $this->name            = __( 'Post slider', 'et_builder' );
        $this->slug            = 'et_pb_post_slider';
        //$this->child_slug      = 'et_pb_slide_v2';
        //$this->child_item_text = __( 'Slide v2', 'et_builder' );

        $this->whitelisted_fields = array(
            'post_count',
            'show_arrows',
            'show_pagination',
            'alignment',
            'background_layout',
            'auto',
            'auto_speed',
            'auto_ignore_hover',
            'parallax',
            'parallax_method',
            'remove_inner_shadow',
            'background_position',
            'background_size',
            'admin_label',
            'module_id',
            'module_class',
            'top_padding',
            'bottom_padding',
            'hide_content_on_mobile',
            'hide_cta_on_mobile',
            'show_image_video_mobile',
        );

        $this->fields_defaults = array(
            'post_count'              => array( '5' ),
            'show_arrows'             => array( 'on' ),
            'show_pagination'         => array( 'on' ),
            'alignment'               => array( 'center' ),
            'background_layout'       => array( 'dark' ),
            'auto'                    => array( 'off' ),
            'auto_speed'              => array( '7000' ),
            'auto_ignore_hover'       => array( 'off' ),
            'parallax'                => array( 'off' ),
            'parallax_method'         => array( 'off' ),
            'remove_inner_shadow'     => array( 'off' ),
            'background_position'     => array( 'default' ),
            'background_size'         => array( 'default' ),
            'hide_content_on_mobile'  => array( 'off' ),
            'hide_cta_on_mobile'      => array( 'off' ),
            'show_image_video_mobile' => array( 'off' ),
        );

        $this->main_css_element = '%%order_class%%.et_pb_slider';
        $this->advanced_options = array(
            'fonts' => array(
                'header' => array(
                    'label'    => __( 'Header', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} .et_pb_slide_description h2",
                    ),
                ),
                'body'   => array(
                    'label'    => __( 'Body', 'et_builder' ),
                    'css'      => array(
                        'line_height' => "{$this->main_css_element}",
                        'main' => "{$this->main_css_element} .et_pb_slide_content",
                    ),
                ),
            ),
            'button' => array(
                'button' => array(
                    'label' => __( 'Button', 'et_builder' ),
                ),
            ),
        );
        $this->custom_css_options = array(
            'slide_description' => array(
                'label'    => __( 'Slide Description', 'et_builder' ),
                'selector' => '.et_pb_slide_description',
            ),
            'slide_title' => array(
                'label'    => __( 'Slide Title', 'et_builder' ),
                'selector' => '.et_pb_slide_description h2',
            ),
            'slide_button' => array(
                'label'    => __( 'Slide Button', 'et_builder' ),
                'selector' => 'a.et_pb_more_button',
            ),
            'slide_controllers' => array(
                'label'    => __( 'Slide Controllers', 'et_builder' ),
                'selector' => '.et-pb-controllers',
            ),
            'slide_active_controller' => array(
                'label'    => __( 'Slide Active Controller', 'et_builder' ),
                'selector' => '.et-pb-controllers .et-pb-active-control',
            ),
        );
    }

    function get_fields() {
        $fields = array(
            'post_count' => array(
                'label'   => __( 'Post count', 'et_builder' ),
                'type'    => 'text',
                'description'       => __( "Here you can set the number of posts displayed in slider.", 'et_builder' ),
            ),
            'show_arrows' => array(
                'label'   => __( 'Arrows', 'et_builder' ),
                'type'    => 'select',
                'options' => array(
                    'on'  => __( 'Show Arrows', 'et_builder' ),
                    'off' => __( 'Hide Arrows', 'et_builder' ),
                ),
                'description'        => __( 'This setting will turn on and off the navigation arrows.', 'et_builder' ),
            ),
            'show_pagination' => array(
                'label'              => __( 'Controls', 'et_builder' ),
                'type'              => 'select',
                'options'           => array(
                    'on'  => __( 'Show Slider Controls', 'et_builder' ),
                    'off' => __( 'Hide Slider Controls', 'et_builder' ),
                ),
                'description'        => __( 'This setting will turn on and off the circle buttons at the bottom of the slider.', 'et_builder' ),
            ),
            'alignment' => array(
                'label' => __( 'Slide Image Vertical Alignment', 'et_builder' ),
                'type'  => 'select',
                'options' => array(
                    'center' => __( 'Center', 'et_builder' ),
                    'bottom' => __( 'Bottom', 'et_builder' ),
                ),
                'description' => __( 'This setting determines the vertical alignment of your slide image. Your image can either be vertically centered, or aligned to the bottom of your slide.', 'et_builder' ),
            ),
            'background_layout' => array(
                'label'   => __( 'Text Color', 'et_builder' ),
                'type'    => 'select',
                'options' => array(
                    'dark'  => __( 'Light', 'et_builder' ),
                    'light' => __( 'Dark', 'et_builder' ),
                ),
                'description' => __( 'Here you can choose whether your text is light or dark. If you have a slide with a dark background, then choose light text. If you have a light background, then use dark text.' , 'et_builder' ),
            ),
            'auto' => array(
                'label'   => __( 'Automatic Animation', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off' => __( 'Off', 'et_builder' ),
                    'on'  => __( 'On', 'et_builder' ),
                ),
                'affects' => array(
                    '#et_pb_auto_speed, #et_pb_auto_ignore_hover',
                ),
                'description'        => __( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
            ),
            'auto_speed' => array(
                'label'             => __( 'Automatic Animation Speed (in ms)', 'et_builder' ),
                'type'              => 'text',
                'depends_default'   => true,
                'description'       => __( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
            ),
            'auto_ignore_hover' => array(
                'label'  => __( 'Continue Automatic Slide on Hover', 'et_builder' ),
                'type'   => 'yes_no_button',
                'depends_default' => true,
                'options' => array(
                    'off' => __( 'Off', 'et_builder' ),
                    'on'  => __( 'On', 'et_builder' ),
                ),
                'description' => __( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
            ),
            'parallax' => array(
                'label'   => __( 'Use Parallax effect', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off'  => __( 'No', 'et_builder' ),
                    'on' => __( 'Yes', 'et_builder' ),
                ),
                'affects'           => array(
                    '#et_pb_parallax_method',
                    '#et_pb_background_position',
                    '#et_pb_background_size',
                ),
                'description'        => __( 'Enabling this option will give your background images a fixed position as you scroll.', 'et_builder' ),
            ),
            'parallax_method' => array(
                'label'   => __( 'Parallax method', 'et_builder' ),
                'type'    => 'select',
                'options' => array(
                    'off' => __( 'CSS', 'et_builder' ),
                    'on'  => __( 'True Parallax', 'et_builder' ),
                ),
                'depends_show_if'   => 'on',
                'description'       => __( 'Define the method, used for the parallax effect.', 'et_builder' ),
            ),
            'remove_inner_shadow' => array(
                'label'   => __( 'Remove Inner Shadow', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off' => __( 'No', 'et_builder' ),
                    'on'  => __( 'Yes', 'et_builder' ),
                ),
            ),
            'background_position' => array(
                'label'   => __( 'Background Image Position', 'et_builder' ),
                'type'    => 'select',
                'options' => array(
                    'default'       => __( 'Default', 'et_builder' ),
                    'top_left'      => __( 'Top Left', 'et_builder' ),
                    'top_center'    => __( 'Top Center', 'et_builder' ),
                    'top_right'     => __( 'Top Right', 'et_builder' ),
                    'center_right'  => __( 'Center Right', 'et_builder' ),
                    'center_left'   => __( 'Center Left', 'et_builder' ),
                    'bottom_left'   => __( 'Bottom Left', 'et_builder' ),
                    'bottom_center' => __( 'Bottom Center', 'et_builder' ),
                    'bottom_right'  => __( 'Bottom Right', 'et_builder' ),
                ),
                'depends_show_if'   => 'off',
            ),
            'background_size' => array(
                'label'   => __( 'Background Image Size', 'et_builder' ),
                'type'    => 'select',
                'options' => array(
                    'default' => __( 'Default', 'et_builder' ),
                    'contain' => __( 'Fit', 'et_builder' ),
                    'initial' => __( 'Actual Size', 'et_builder' ),
                ),
                'depends_show_if'   => 'off',
            ),
            'admin_label' => array(
                'label'       => __( 'Admin Label', 'et_builder' ),
                'type'        => 'text',
                'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
            ),
            'module_id' => array(
                'label'       => __( 'CSS ID', 'et_builder' ),
                'type'        => 'text',
                'description' => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
            ),
            'module_class' => array(
                'label'       => __( 'CSS Class', 'et_builder' ),
                'type'        => 'text',
                'description' => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
            ),
            'top_padding' => array(
                'label'    => __( 'Top Padding', 'et_builder' ),
                'type'     => 'text',
                'tab_slug' => 'advanced',
            ),
            'bottom_padding' => array(
                'label'    => __( 'Bottom Padding', 'et_builder' ),
                'type'     => 'text',
                'tab_slug' => 'advanced',
            ),
            'hide_content_on_mobile' => array(
                'label'   => __( 'Hide Content On Mobile', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off' => __( 'No', 'et_builder' ),
                    'on'  => __( 'Yes', 'et_builder' ),
                ),
                'tab_slug'          => 'advanced',
            ),
            'hide_cta_on_mobile' => array(
                'label'   => __( 'Hide CTA On Mobile', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off' => __( 'No', 'et_builder' ),
                    'on'  => __( 'Yes', 'et_builder' ),
                ),
                'tab_slug'          => 'advanced',
            ),
            'show_image_video_mobile' => array(
                'label'   => __( 'Show Image / Video On Mobile', 'et_builder' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'off' => __( 'No', 'et_builder' ),
                    'on'  => __( 'Yes', 'et_builder' ),
                ),
                'tab_slug'          => 'advanced',
            ),
        );
        return $fields;
    }

    function pre_shortcode_content() {
        global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

        $et_pb_slider_item_num = 0;

        $parallax        = $this->shortcode_atts['parallax'];
        $parallax_method = $this->shortcode_atts['parallax_method'];
        $hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
        $hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
        $button_custom           = $this->shortcode_atts['custom_button'];
        $custom_icon             = $this->shortcode_atts['button_icon'];

        $et_pb_slider_has_video = false;

        $et_pb_slider_parallax = $parallax;

        $et_pb_slider_parallax_method = $parallax_method;

        $et_pb_slider_hide_mobile = array(
            'hide_content_on_mobile'  => $hide_content_on_mobile,
            'hide_cta_on_mobile'      => $hide_cta_on_mobile,
        );

        $et_pb_slider_custom_icon = 'on' === $button_custom ? $custom_icon : '';

    }

    function shortcode_callback( $atts, $content = null, $function_name ) {
        $alignment               = $this->shortcode_atts['alignment'];
        $module_id               = $this->shortcode_atts['module_id'];
        $module_class            = $this->shortcode_atts['module_class'];
        $post_count              = $this->shortcode_atts['post_count'];
        $show_arrows             = $this->shortcode_atts['show_arrows'];
        $show_pagination         = $this->shortcode_atts['show_pagination'];
        $parallax                = $this->shortcode_atts['parallax'];
        $parallax_method         = $this->shortcode_atts['parallax_method'];
        $auto                    = $this->shortcode_atts['auto'];
        $auto_speed              = $this->shortcode_atts['auto_speed'];
        $auto_ignore_hover       = $this->shortcode_atts['auto_ignore_hover'];
        $top_padding             = $this->shortcode_atts['top_padding'];
        $body_font_size 		 = $this->shortcode_atts['body_font_size'];
        $bottom_padding          = $this->shortcode_atts['bottom_padding'];
        $remove_inner_shadow     = $this->shortcode_atts['remove_inner_shadow'];
        $hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
        $hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
        $show_image_video_mobile = $this->shortcode_atts['show_image_video_mobile'];
        $background_position     = $this->shortcode_atts['background_position'];
        $background_size         = $this->shortcode_atts['background_size'];
        $background_layout       = $this->shortcode_atts['background_layout'];

        global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon;

        $et_pb_slider_item_num++;

        $posts_number = $post_count;

        $args = array(
            'posts_per_page' => (int) $posts_number,
            'post_type'      => 'post',
        );

        query_posts($args);

        $content = '';
        if ( have_posts() ) {
            while (have_posts()) {
                the_post();

                $button_link = get_permalink();
                $button_text = __('Read more', 'et_builder');
                $button = sprintf('<a href="%1$s" class="et_pb_more_button et_pb_button">%2$s</a>',
                    esc_attr($button_link),
                    esc_html($button_text)
                );

                $heading = get_the_title();

                if ('' !== $heading) {
                    $heading = sprintf('<a href="%1$s">%2$s</a>',
                        esc_url($button_link),
                        $heading
                    );
                    $heading = '<h2>' . $heading . '</h2>';
                }

                $author = sprintf('<a href="%1$s" alt="%2$s">%2$s</a>',
                    get_the_author_link(),
                    get_the_author()
                );

                $slide_content = sprintf('<p>%1$s %2$s / %3$s / %4$s</p>',
                    __('By', 'et_builder'),
                    $author,
                    get_the_category_list(', '),
                    sprintf( __( '%s', 'et_builder' ), get_the_date( $meta_date ) )
                );

                $style = $class = '';
                $background_image_query = wp_get_attachment_image_src( get_post_thumbnail_id(), 'et-pb-post-main-image-fullwidth' );
                if( is_array($background_image_query) ) {
                    $background_image = $background_image_query[0];
                }

                if ('' !== $background_image && 'on' !== $parallax) {
                    $style .= sprintf('background-image:url(%s);',
                        esc_attr($background_image)
                    );
                }

                $style = '' !== $style ? " style='{$style}'" : '';

                $class .= " et_pb_bg_layout_{$background_layout}";

                if ( 'bottom' !== $alignment ) {
                    $class .= " et_pb_media_alignment_{$alignment}";
                }

                //$class = ET_Builder_Element::add_module_order_class( $class, 'et_pb_post_slide' );

                if ( 1 === $et_pb_slider_item_num ) {
                    $class .= " et-pb-active-slide";
                }

                $link = '';
                if ('' !== $button_link ) {
                    $link = sprintf(
                        '<div class="et_pb_slide_link_wrapper">
                            <a href="%1$s" class="et_pb_slide_link"></a>
                        </div>',
                        $button_link
                    );
                }

                $content .= sprintf(
                    '<div class="et_pb_slide%5$s"%4$s>
                        %7$s
                        <div class="et_pb_container clearfix">
                            <div class="et_pb_slide_description">
                                %1$s
                                <div class="et_pb_slide_content">%2$s</div>
                                %3$s
                            </div> <!-- .et_pb_slide_description -->
                        </div> <!-- .et_pb_container -->
                    </div> <!-- .et_pb_slide -->
                    ',
                    $heading,
                    $slide_content,
                    $button,
                    $style,
                    esc_attr($class),
                    $link,
                    ('' !== $background_image && 'on' === $parallax ? sprintf('<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>', esc_attr($background_image), ('off' === $parallax_method ? ' et_pb_parallax_css' : '')) : '')
                );
            }
            wp_reset_query();
        }

        $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

        if ( '' !== $top_padding ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
                'declaration' => sprintf(
                    'padding-top: %1$s;',
                    esc_html( et_builder_process_range_value( $top_padding ) )
                ),
            ) );
        }

        if ( '' !== $bottom_padding ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
                'declaration' => sprintf(
                    'padding-bottom: %1$s;',
                    esc_html( et_builder_process_range_value( $bottom_padding ) )
                ),
            ) );
        }

        if ( '' !== $bottom_padding || '' !== $top_padding ) {
            ET_Builder_Module::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
                'declaration' => 'padding-right: 0; padding-left: 0;',
            ) );
        }

        if ( 'default' !== $background_position && 'off' === $parallax ) {
            $processed_position = str_replace( '_', ' ', $background_position );

            ET_Builder_Module::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_slide',
                'declaration' => sprintf(
                    'background-position: %1$s;',
                    esc_html( $processed_position )
                ),
            ) );
        }

        if ( 'default' !== $background_size && 'off' === $parallax ) {
            ET_Builder_Module::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_slide',
                'declaration' => sprintf(
                    '-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
                    esc_html( $background_size )
                ),
            ) );
        }

        $fullwidth = 'et_pb_fullwidth_slider' === $function_name ? 'on' : 'off';

        $class  = '';
        $class .= 'off' === $fullwidth ? ' et_pb_slider_fullwidth_off' : '';
        $class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
        $class .= 'off' === $show_pagination ? ' et_pb_slider_no_pagination' : '';
        $class .= 'on' === $parallax ? ' et_pb_slider_parallax' : '';
        $class .= 'on' === $auto ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';
        $class .= 'on' === $auto_ignore_hover ? ' et_slider_auto_ignore_hover' : '';
        $class .= 'on' === $remove_inner_shadow ? ' et_pb_slider_no_shadow' : '';
        $class .= 'on' === $show_image_video_mobile ? ' et_pb_slider_show_image' : ''; 

        $output = sprintf(
            '<div%4$s class="et_pb_module et_pb_slider et_pb_post_slider%1$s%3$s%5$s">
				<div class="et_pb_slides">
					%2$s
				</div> <!-- .et_pb_slides -->
			</div> <!-- .et_pb_slider -->
			',
            $class,
            $content,
            ( $et_pb_slider_has_video ? ' et_pb_preload' : '' ),
            ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
        );

        return $output;
    }
}
new ET_Builder_Module_Slider_v2;