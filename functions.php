<?php
/**
 *
 * Copyright (c) 2017 MPAT Consortium , All rights reserved.
 * Fraunhofer FOKUS, Fincons Group, Telecom ParisTech, IRT, Lacaster University, Leadin, RBB, Mediaset
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 *
 * AUTHORS:
 * Miggi Zwicklbauer (miggi.zwicklbauer@fokus.fraunhofer.de)
 * Thomas Tröllmich  (thomas.troellmich@fokus.fraunhofer.de)
 * Jean-Philippe Ruijs (jean-philippe.ruijs@telecom.paristech.fr)
 * Stefano Miccoli (stefano.miccoli@finconsgroup.com)
 * Marco Ferrari (marco.ferrari@finconsgroup.com)
 **/
/* Required external files */

require_once('external/mpat-utilities.php');

add_action( 'after_setup_theme', 'mpat_theme_setup' );
function mpat_theme_setup(){
    $themepath = get_template_directory() . '/languages';
    load_theme_textdomain( 'mpat', $themepath );
}


//======================================================================
// INITIALIZE
//======================================================================

/* Theme specific settings */
add_theme_support('post-thumbnails');

//-----------------------------------------------------
//  Actions and Filters
//-----------------------------------------------------

//Enqueue global scripts (frontend and backend)
//Enqueue admin scripts (backend)
add_action('admin_enqueue_scripts', 'mpat_admin_scripts_init');
add_action('wp_enqueue_scripts', 'mpat_enqueue_frontend_scripts');

//Register scripts for customizer
add_action('customize_register', 'mpat_add_customizer_custom_controls');
add_action('customize_register', 'mpat_customizer_register');
add_action('customize_controls_enqueue_scripts', 'mpat_enqueue_customizer_admin_scripts');

add_action('admin_head-post.php', 'mpat_editor_style' );
add_action('admin_head-post-new.php', 'mpat_editor_style' );

add_action('admin_menu', 'remove_menus' );

add_action('wp_head', 'mpat_customizer_css');

add_filter('body_class', array('Mpat_Utilities', 'add_slug_to_body_class'));

// Remove Canonical Link Added By Yoast WordPress SEO Plugin
add_filter('wpseo_canonical', function () {
    return false;
});

add_filter('default_page_template_title', function () {
    return __('Full Page', 'mpat');
});

//-----------------------------------------------------
//  Enqueue Scripts and Styles
//-----------------------------------------------------

//Admin scripts
function mpat_admin_scripts_init($hook)
{
    add_thickbox();

    wp_register_script('mpat-media-uploader', get_template_directory_uri() . '/backend/js/media-uploader.js', array('jquery', 'media-upload', 'thickbox'));
    wp_enqueue_script('mpat-media-uploader');

    wp_enqueue_style('thickbox');
}

function mpat_enqueue_frontend_scripts()
{
    // wp_register_script('keycodes',get_template_directory_uri( __FILE__ ).'/frontend/js/keycodes.js',array(),false,true);
    // wp_enqueue_script('keycodes');
    // wp_register_script('hbbtvlib',get_template_directory_uri( __FILE__ ).'/frontend/js/hbbtvlib.js',array(),false,true);
    // wp_enqueue_script('hbbtvlib');
    wp_enqueue_style('reset', get_template_directory_uri().'/reset.css');

    if (isset($_GET['cs']) && $_GET["cs"] === "true") {
        wp_enqueue_style('core', get_template_directory_uri().'/cs.css', array(), 1.0);
    } else {
        wp_enqueue_style('core', get_template_directory_uri().'/style.css', array(), 1.0);
    }
}

function mpat_enqueue_customizer_admin_scripts()
{
    wp_enqueue_script('customizer-admin', get_template_directory_uri() . '/backend/js/customizer-admin.js', array('jquery'), false, true);
    wp_enqueue_style('mpat-customizer-controls', get_template_directory_uri() . '/backend/css/customizer-controls.css');
}

//======================================================================
// CREATE THE MPAT STUFF
//======================================================================


//Remove not needed Menu Tabs from WP
function remove_menus()
{
    remove_menu_page( 'edit.php' );           //Posts
    remove_menu_page( 'edit-comments.php' ); //Comments
}



//-----------------------------------------------------
//  Setup the Customiser (Under Apperance->Customise)
//-----------------------------------------------------

function mpat_add_customizer_custom_controls($wp_customize)
{

    class MPAT_Customize_Alpha_Color_Control extends WP_Customize_Control
    {

        public $type = 'alphacolor';
        public $palette = true;
        public $default = 'rgba(255,255,255,0.9)';

        protected function render()
        {
            $id = 'customize-control-' . str_replace('[', '-', str_replace(']', '', $this->id));
            $class = 'customize-control customize-control-' . $this->type; ?>
            <li id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php $this->render_content(); ?>
            </li>
        <?php                                                                                                                                                                                                                 }

        public function render_content()
        {
    ?>
            <label>
                <input type="text" data-palette="<?php echo $this->palette; ?>"
                       data-default-color="<?php echo $this->default; ?>"
                       value="<?php echo intval($this->value()); ?>"
                       class="mpat-color-control" <?php $this->link(); ?> />
            </label>
        <?php                                                                                                                                                                                                                 }
    }
}

/* Default values MUST be defined here and used in the setting definition and in frontend representation
 * otherwise fronted will always lack of default values with huge inconsistencies
 */
define('MPAT_DEFAULT_BACKGROUND_COLOR', 'transparent');
define('MPAT_DEFAULT_BACKGROUND_IMAGE', false);

define('MPAT_DEFAULT_BORDER_COLOR', '#fff');
define('MPAT_DEFAULT_BORDER_STYLE', 'solid');
define('MPAT_DEFAULT_BORDER_WIDTH', '5');
define('MPAT_DEFAULT_BORDER_RADIUS', '0');

define('MPAT_DEFAULT_HX_WEIGHT', 'bold');
define('MPAT_DEFAULT_H1_SIZE', 26);
define('MPAT_DEFAULT_H2_SIZE', 24);
define('MPAT_DEFAULT_H3_SIZE', 22);
define('MPAT_DEFAULT_H4_SIZE', 20);
define('MPAT_DEFAULT_H5_SIZE', 18);
define('MPAT_DEFAULT_H6_SIZE', 16);

define('MPAT_DEFAULT_FONT_SIZE', 16);
define('MPAT_DEFAULT_LINE_HEIGHT', 24);
define('MPAT_DEFAULT_FONT_COLOR', '#666');

define('MPAT_DEFAULT_LINK_SIZE', 20);
define('MPAT_DEFAULT_LINK_COLOR', '#000');
define('MPAT_DEFAULT_LINK_BACKGROUND_COLOR', 'rgba(0,0,0, 0.0)');
define('MPAT_DEFAULT_LINK_COLOR_FOCUSED', '#000');
define('MPAT_DEFAULT_LINK_BACKGROUND_COLOR_FOCUSED', 'rgba(0,0,0, 0.0)');
define('MPAT_DEFAULT_LINK_WEIGHT', 'normal');
define('MPAT_DEFAULT_LINK_DECORATION', 'none');

define('MPAT_DEFAULT_SIDE_MENU_FONT_SIZE', 22);
define('MPAT_DEFAULT_SIDE_MENU_LINE_HEIGHT', 33);
define('MPAT_DEFAULT_SIDE_MENU_FONT_COLOR', '#FFF');
define('MPAT_DEFAULT_SIDE_MENU_FONT_COLOR_ACTIVE', '#888');
define('MPAT_DEFAULT_SIDE_MENU_WIDTH', 300);
define('MPAT_DEFAULT_SIDE_MENU_SEPARATOR_COLOR', '#FFF');
define('MPAT_DEFAULT_SIDE_MENU_BG_COLOR', 'rgba(0,0,0, 0.5)');
define('MPAT_DEFAULT_SIDE_MENU_LINE_BG_COLOR_ACTIVE', 'rgba(0,0,0, 0.0)');

define('MPAT_DEFAULT_LAUNCHER_FONT_SIZE', 20);
define('MPAT_DEFAULT_LAUNCHER_LINE_HEIGHT', 30);
define('MPAT_DEFAULT_LAUNCHER_FONT_STYLE', 'normal');
define('MPAT_DEFAULT_LAUNCHER_FONT_FLOAT', 'left');
define('MPAT_DEFAULT_LAUNCHER_FONT_COLOR', '#000');
define('MPAT_DEFAULT_LAUNCHER_BG_COLOR', 'rgba(255,255,255,0.8)');
define('MPAT_DEFAULT_LAUNCHER_FONT_COLOR_FOCUSED', '#FFF');
define('MPAT_DEFAULT_LAUNCHER_BG_COLOR_FOCUSED', 'rgba(0,0,0,0.8)');
define('MPAT_DEFAULT_LAUNCHER_BORDER_COLOR_FOCUSED', 'rgba(255,255,255, 0.8)');

define('MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE', 16);
define('MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR', '#666');
define('MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT', 20);
define('MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT', '400');
define('MPAT_DEFAULT_GENERAL_MENU_BORDER_WIDTH', 0);
define('MPAT_DEFAULT_GENERAL_MENU_BORDER_RADIUS', 0);
define('MPAT_DEFAULT_GENERAL_MENU_BORDER_COLOR', '#fff');
define('MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR', 'rgba(255,255,255,0)');
define('MPAT_DEFAULT_GENERAL_MENU_PADDING', 0);
define('MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE_ACTIVE', 16);
define('MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT_ACTIVE', '400');
define('MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR_ACTIVE', '#666');
define('MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR_ACTIVE', 'rgba(255,255,255,0)');
define('MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT_ACTIVE', 20);

define('MPAT_DEFAULT_GALLERY_BACKGROUND_COLOR', 'rgba(255,255,255,0)');
define('MPAT_DEFAULT_GALLERY_ARROW_COLOR', '#FFF');
define('MPAT_DEFAULT_GALLERY_ARROW_BACKGROUND_COLOR', 'rgba(0,0,0,0.5)');
define('MPAT_DEFAULT_GALLERY_ARROW_SIZE', 25);
define('MPAT_DEFAULT_GALLERY_ARROW_PADDING', 5);
define('MPAT_DEFAULT_GALLERY_ARROW_BORDER_RADIUS', 6);
define('MPAT_DEFAULT_GALLERY_DOT_COLOR_FOCUSED', '#cccccc');
define('MPAT_DEFAULT_GALLERY_DOT_COLOR', '#333333');

function mpat_customizer_register( $wp_customize )
{

     /* Frontpage */

    $wp_customize->remove_section( 'static_front_page' );
    update_option( 'show_on_front', 'page' );

    $wp_customize->add_section( 'mpat_front_page', array(
        'title' => __('Frontpage','mpat'),
        'priority' => 120,
        'description' => __('Select the page to be displayed on the front', 'mpat')
    ) );

    $wp_customize->add_setting( 'page_on_front', array(
        'type' => 'option',
        'capability' => 'manage_options'
    ) );

    $wp_customize->add_control( 'page_on_front', array(
        'label' => __('Frontpage', 'mpat'),
        'section' => 'mpat_front_page',
        'type' => 'dropdown-pages'
    ) );

    /* General Settings Panel */
    $wp_customize->add_panel( 'settings', array(
      'title' => __( 'General Settings', 'mpat' ),
      'description' => __( 'Modify the theme settings', 'mpat' ),
      'priority' => 160,
    ) );

    /* Background image and color */
    $wp_customize->add_section( 'mpat_background', array(
        'title' => __( 'Application background', 'mpat' ),
        'description' => __( 'Modify the Default Background ' , 'mpat'),
        'panel' => 'settings',
    ) );
    $wp_customize->add_setting( 'bg_image', array(
        'type' => 'theme_mod',
        'default' => MPAT_DEFAULT_BACKGROUND_IMAGE,
    ) );
    $wp_customize->add_setting( 'bg_video', array(
    		'type' => 'theme_mod',
    ) );
    $wp_customize->add_setting( 'bg_color', array(
    		'type' => 'theme_mod',
    ) );
    $wp_customize->add_setting( 'bg_color', array(
            'type' => 'theme_mod',
            'default' => MPAT_DEFAULT_BACKGROUND_COLOR,
    ) );
    $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'bg_color', array(
	    'label' => __( 'Backgroud color', 'mpat' ),
	    'section' => 'mpat_background'
	) ) );
    $wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'bg_video', array(
	    'label' => __( 'Backgroud video', 'mpat' ),
	    'section' => 'mpat_background'
	) ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bg_image', array(
        'label' => __( 'Background image (1280x720px)', 'mpat' ),
        'section' => 'mpat_background',
        'settings' => 'bg_image',
        'mime_type' => 'image',
    ) ) );


    /* Focused Border */
    $wp_customize->add_section( 'mpat_focused', array(
        'title' => __( 'Focused Component Styles', 'mpat' ),
        'description' => __( 'Modify Global Focused Component Style Settings' , 'mpat'),
        'panel' => 'settings',
    ) );
    $wp_customize->add_setting( 'focusedborder_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_BORDER_COLOR) );
    $wp_customize->add_setting( 'focusedborder_style', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_BORDER_STYLE) );
    $wp_customize->add_setting( 'focusedborder_width', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_BORDER_WIDTH) );
    $wp_customize->add_setting( 'focusedborder_radius', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_BORDER_RADIUS) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'focusedborder_color', array(
        'label' => __( 'Border color', 'mpat' ),
        'section' => 'mpat_focused'
    ) ) );
    $wp_customize->add_control('focusedborder_style', array(
            'type' => 'select',
            'label' => __( 'Border color', 'mpat' ),
            'section' => 'mpat_focused',
            'choices' => array(
                    "none" => "none",
                    "solid" => "solid",
                    "hidden" => "hidden",
                    "dotted" => "dotted",
                    "dashed" => "dashed"
            ),
    ) );
    $wp_customize->add_control( 'focusedborder_width', array(
            'type'        => 'number',
            'section'     => 'mpat_focused',
            'description' => __('Border width (in px, 1-50)', 'mpat' ),
            'input_attrs' => array(
                    'min'   => 0,
                    'max'   => 50,
                    'step'  => 1,
            ),
    ));
    $wp_customize->add_control( 'focusedborder_radius', array(
            'type'        => 'number',
            'section'     => 'mpat_focused',
            'description' => __('Border radius (in px, 1-50)', 'mpat' ),
            'input_attrs' => array(
                    'min'   => 0,
                    'max'   => 50,
                    'step'  => 1,
            ),
    ));

    /*Icon Set*/
    $wp_customize->add_section( 'mpat_iconset', array(
        'title' => __( 'Icon Set', 'mpat'),
        'description' => __( 'Set Icon Color Theme for your App' , 'mpat'),
        'panel' => 'settings'
    ) );
    $wp_customize->add_setting('iconset', array(
          'type' => 'option',
          'default' => '#FFF'
      )
    );
    $wp_customize->add_setting('iconset_opacity', array(
          'type' => 'option',
          'default' => '1.0'
      )
    );
    $wp_customize->add_setting('iconset_focused', array(
          'type' => 'option',
          'default' => '#798393'
      )
    );
    $wp_customize->add_setting('iconset_focused_opacity', array(
          'type' => 'option',
          'default' => '1.0'
      )
    );
    $wp_customize->add_setting('iconset_active', array(
          'type' => 'option',
          'default' => '#43b4f9'
      )
    );
    $wp_customize->add_setting('iconset_active_opacity', array(
          'type' => 'option',
          'default' => '1.0'
      )
    );
    $wp_customize->add_setting('icon_arrow', array(
          'type' => 'option',
          'default' => '#FFF'
      )
    );
    $wp_customize->add_setting('icon_arrow_width', array(
          'type' => 'option',
          'default' => '100'
      )
    );
    $wp_customize->add_setting('icon_arrow_opacity', array(
          'type' => 'option',
          'default' => '1.0'
      )
    );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'iconset', array(
        'label' => __( 'Icons', 'mpat' ),
      'description' => __('Choose Icon Color', 'mpat' ),
        'section' => 'mpat_iconset'
    ) ) );
     $wp_customize->add_control( 'iconset_opacity', array(
        'type'        => 'range',
      'section'     => 'mpat_iconset',
      'description' => __('Icon Color opacity:', 'mpat' ),
      'input_attrs' => array(
        'min'   => 0,
        'max'   => 1.0,
        'step'  => 0.1,
      ),
     ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'iconset_focused', array(
        'label' => __( 'Icons focused', 'mpat' ),
      'description' => __('Choose Icon Color focused', 'mpat' ),
        'section' => 'mpat_iconset'
    ) ) );
      $wp_customize->add_control( 'iconset_focused_opacity', array(
        'type'        => 'range',
      'section'     => 'mpat_iconset',
      'description' => __('Icon Color focused opacity:', 'mpat' ),
      'input_attrs' => array(
        'min'   => 0,
        'max'   => 1.0,
        'step'  => 0.1,
      ),
      ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'iconset_active', array(
        'label' => __( 'Icons active', 'mpat' ),
      'description' => __('Choose Icon Color active', 'mpat' ),
        'section' => 'mpat_iconset'
    ) ) );
      $wp_customize->add_control( 'iconset_active_opacity', array(
        'type'        => 'range',
      'section'     => 'mpat_iconset',
      'description' => __('Icon Color active opacity:', 'mpat' ),
      'input_attrs' => array(
        'min'   => 0,
        'max'   => 1.0,
        'step'  => 0.1,
      ),
      ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'icon_arrow', array(
        'label' => __( 'PageFlow Apps', 'mpat' ),
      'description' => __('Choose PageFlow Arrow Color', 'mpat' ),
        'section' => 'mpat_iconset'
    ) ) );
    $wp_customize->add_control( 'icon_arrow_width', array(
        'type'        => 'range',
      'section'     => 'mpat_iconset',
      'description' => __('PageFlow Arrows size:', 'mpat' ),
      'input_attrs' => array(
        'min'   => 50,
        'max'   => 150,
        'step'  => 10,
      ),
    ) );
    $wp_customize->add_control( 'icon_arrow_opacity', array(
        'type'        => 'range',
      'section'     => 'mpat_iconset',
      'description' => __('PageFlow Arrows opacity:', 'mpat' ),
      'input_attrs' => array(
        'min'   => 0,
        'max'   => 1.0,
        'step'  => 0.1,
      ),
  ) );

	/* Remote keys icons */
	$wp_customize->add_section( 'mpat_remote_icons', array(
		'title' => __( 'Remote keys icons', 'mpat'),
		'description' => __( 'Set Icon Color Theme for your App' ),
		'panel' => 'settings'
	));
	$icons = array(
		'mpat_icons_ok' => array( 'filename' => 'button_ok_black.png', 'label' => 'OK button'),
		'mpat_icons_back' => array( 'filename' => 'button_return_black.png', 'label' => 'Back button'),
		'mpat_icons_red' => array( 'filename' => 'button_red.png', 'label' => 'Red button'),
		'mpat_icons_blue' => array( 'filename' => 'button_blue.png', 'label' => 'Blue button'),
		'mpat_icons_green' => array( 'filename' => 'button_green.png', 'label' => 'Green button'),
		'mpat_icons_yellow' => array( 'filename' => 'button_yellow.png', 'label' => 'Yellow button'),
		'mpat_icons_pause' => array( 'filename' => 'icons_pause_black.png', 'label' => 'Pause'),
		'mpat_icons_play' => array( 'filename' => 'icons_play_black.png', 'label' => 'Play'),
		'mpat_icons_forward' => array( 'filename' => 'icons_forward_black.png', 'label' => 'Forward'),
		'mpat_icons_rewind' => array( 'filename' => 'icons_rewind_black.png', 'label' => 'Rewind'),
		'mpat_icons_0' => array( 'filename' => 'icons_0_black.png', 'label' => 'Number 0'),
		'mpat_icons_1' => array( 'filename' => 'icons_1_black.png', 'label' => 'Number 1'),
		'mpat_icons_2' => array( 'filename' => 'icons_2_black.png', 'label' => 'Number 2'),
		'mpat_icons_3' => array( 'filename' => 'icons_3_black.png', 'label' => 'Number 3'),
		'mpat_icons_4' => array( 'filename' => 'icons_4_black.png', 'label' => 'Number 4'),
		'mpat_icons_5' => array( 'filename' => 'icons_5_black.png', 'label' => 'Number 5'),
		'mpat_icons_6' => array( 'filename' => 'icons_6_black.png', 'label' => 'Number 6'),
		'mpat_icons_7' => array( 'filename' => 'icons_7_black.png', 'label' => 'Number 7'),
		'mpat_icons_8' => array( 'filename' => 'icons_8_black.png', 'label' => 'Number 8'),
		'mpat_icons_9' => array( 'filename' => 'icons_9_black.png', 'label' => 'Number 9'),
	);

	foreach ($icons as $key => $values) {
		$wp_customize->add_setting($key, array(
			'type' => 'option',
			'default' => get_template_directory_uri() . "/shared/assets/remote/{$values['filename']}",
		));
		// since stupid wordpress does not handle the default value in frontend we initialize
		// options with the default value, namely the "default" above is completely useless execpt in the customizer UI
		if (!get_option($key)) add_option($key, get_template_directory_uri() . "/shared/assets/remote/{$values['filename']}");
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, array(
			'label' => __( $values['label'] ),
			'section' => 'mpat_remote_icons',
			'mime_type' => 'image',
		)));
	}
	/***************************************************************/
	/**						Components Settings	Panel		      **/
	/***************************************************************/
	
	//add panel 'Component Settings'
	$wp_customize->add_panel( 'mpat_components', array('title' => __( 'Components Settings', 'mpat' ),'description' => __( 'Modify Components styles Settings' ,'mpat'),'priority' => 160) );
	
	/*********** Menu **********/
	
	/* General Menu Settings */
	
	$wp_customize->add_section( 'mpat_general_menu', array('title' => __( 'Menu', 'mpat' ),'description' => __( 'Modify Global General Menu Settings' ), 'priority' => 202,'panel' => 'mpat_components') );
	
	$wp_customize->add_setting( 'general_menu_font_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE));
	$wp_customize->add_control( 'general_menu_font_size', array('label' => __( 'Menu Font Size in px','mpat' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_line_height', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT));
	$wp_customize->add_control( 'general_menu_line_height', array('label' => __( 'Menu Line Height in px','mpat' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting('general_menu_text_weight', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT));
	$wp_customize->add_control('general_menu_text_weight', array(
			'type' => 'select',
			'label' => 'Menu font weight:',
			'section' => 'mpat_general_menu',
			'choices' => array(
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
			),
	) );
	
	$wp_customize->add_setting( 'general_menu_padding', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_PADDING));
	$wp_customize->add_control( 'general_menu_padding', array('label' => __( 'Menu padding in px', 'mpat' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_width', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_WIDTH));
	$wp_customize->add_control( 'general_menu_border_width', array('label' => __( 'Menu border width in px', 'mpat' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_radius', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_RADIUS));
	$wp_customize->add_control( 'general_menu_border_radius', array('label' => __( 'Menu border radius in px', 'mpat'),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_COLOR) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_border_color', array(
			'label' => __( 'Menu border color', 'mpat' ),
			'section' => 'mpat_general_menu'
	) ) );
	
	$wp_customize->add_setting( 'general_menu_font_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_font_color', array('label' => __( 'Menu font color', 'mpat' ),'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_background_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'general_menu_background_color', array('label' => __( 'Menu background color', 'mpat' ),'palette' => true,'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_font_size_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE_ACTIVE));
	$wp_customize->add_control( 'general_menu_font_size_active', array('label' => __( 'Menu item ACTIVE Font Size in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_line_height_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT_ACTIVE));
	$wp_customize->add_control( 'general_menu_line_height_active', array('label' => __( 'Menu item ACTIVE Line Height in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting('general_menu_text_weight_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT_ACTIVE));
	$wp_customize->add_control('general_menu_text_weight_active', array(
			'type' => 'select',
			'label' => 'Menu item ACTIVE Font Weight:',
			'section' => 'mpat_general_menu',
			'choices' => array(
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
			),
	) );
	
	$wp_customize->add_setting( 'general_menu_font_color_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR_ACTIVE));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_font_color_active', array('label' => __( 'Menu item ACTIVE Font Color', 'mpat' ),'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_background_color_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR_ACTIVE) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'general_menu_background_color_active', array('label' => __( 'Menu item ACTIVE Line Background Color', 'mpat' ),'palette' => true,'section' => 'mpat_general_menu') ) );
	
	
	/*********** Menu **********/
	
	/* General Menu Settings */
	
	$wp_customize->add_section( 'mpat_general_menu', array('title' => __( 'Menu', 'mpat' ),'description' => __( 'Modify Global General Menu Settings' ), 'priority' => 202,'panel' => 'mpat_components') );
	
	$wp_customize->add_setting( 'general_menu_font_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE));
	$wp_customize->add_control( 'general_menu_font_size', array('label' => __( 'Menu Font Size in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_line_height', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT));
	$wp_customize->add_control( 'general_menu_line_height', array('label' => __( 'Menu Line Height in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting('general_menu_text_weight', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT));
	$wp_customize->add_control('general_menu_text_weight', array(
			'type' => 'select',
			'label' => 'Menu font weight:',
			'section' => 'mpat_general_menu',
			'choices' => array(
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
			),
	) );
	
	$wp_customize->add_setting( 'general_menu_padding', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_PADDING));
	$wp_customize->add_control( 'general_menu_padding', array('label' => __( 'Menu padding in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_width', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_WIDTH));
	$wp_customize->add_control( 'general_menu_border_width', array('label' => __( 'Menu border width in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_radius', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_RADIUS));
	$wp_customize->add_control( 'general_menu_border_radius', array('label' => __( 'Menu border radius in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_border_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_BORDER_COLOR) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_border_color', array(
			'label' => __( 'Menu border color', 'mpat' ),
			'section' => 'mpat_general_menu'
	) ) );
	
	$wp_customize->add_setting( 'general_menu_font_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_font_color', array('label' => __( 'Menu font color', 'mpat' ),'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_background_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'general_menu_background_color', array('label' => __( 'Menu background color', 'mpat' ),'palette' => true,'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_font_size_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE_ACTIVE));
	$wp_customize->add_control( 'general_menu_font_size_active', array('label' => __( 'Menu item ACTIVE Font Size in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting( 'general_menu_line_height_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT_ACTIVE));
	$wp_customize->add_control( 'general_menu_line_height_active', array('label' => __( 'Menu item ACTIVE Line Height in px' ),'section' => 'mpat_general_menu','type' => 'number'));
	
	$wp_customize->add_setting('general_menu_text_weight_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT_ACTIVE));
	$wp_customize->add_control('general_menu_text_weight_active', array(
			'type' => 'select',
			'label' => 'Menu item ACTIVE Font Weight:',
			'section' => 'mpat_general_menu',
			'choices' => array(
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
			),
	) );
	
	$wp_customize->add_setting( 'general_menu_font_color_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR_ACTIVE));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'general_menu_font_color_active', array('label' => __( 'Menu item ACTIVE Font Color', 'mpat' ),'section' => 'mpat_general_menu') ) );
	
	$wp_customize->add_setting( 'general_menu_background_color_active', array('type' => 'theme_mod','default' => MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR_ACTIVE) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'general_menu_background_color_active', array('label' => __( 'Menu item ACTIVE Line Background Color', 'mpat' ),'palette' => true,'section' => 'mpat_general_menu') ) );
	
	
	/***** Side Menu Settings ****/
	$wp_customize->add_section( 'mpat_side_menu', array('title' => __( 'Side Menu', 'mpat' ),'description' => __( 'Modify Global Menu settings' ),'priority' => 202,'panel' => 'mpat_components') );
	
	$wp_customize->add_setting( 'sidemenu_bg_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_SIDE_MENU_BG_COLOR));
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'sidemenu_bg_color', array('label' => __( 'Edit Background Color', 'mpat' ),'palette' => true,'section' => 'mpat_side_menu')));
	
	$wp_customize->add_setting( 'sidemenu_font_size', array('type' => 'theme_mod','default' => MPAT_DEFAULT_SIDE_MENU_FONT_SIZE));
	$wp_customize->add_control( 'sidemenu_font_size', array('label' => __( 'Font Size in px' ),'section' => 'mpat_side_menu','type' => 'number'));
	 
	$wp_customize->add_setting( 'sidemenu_line_height', array('type' => 'theme_mod','default' => MPAT_DEFAULT_SIDE_MENU_LINE_HEIGHT));
	$wp_customize->add_control( 'sidemenu_line_height', array('label' => __( 'Line Height in px' ),'section' => 'mpat_side_menu','type' => 'number'));
	 
	$wp_customize->add_setting( 'sidemenu_font_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_SIDE_MENU_FONT_COLOR));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidemenu_font_color', array('label' => __( 'Edit Font Color', 'mpat' ),'section' => 'mpat_side_menu','settings' => 'sidemenu_font_color')));
	
	$wp_customize->add_setting( 'sidemenu_font_color_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_SIDE_MENU_FONT_COLOR_ACTIVE));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidemenu_font_color_active', array('label' => __( 'Edit ACTIVE Font Color', 'mpat' ),'section' => 'mpat_side_menu','settings' => 'sidemenu_font_color_active')));
	
	$wp_customize->add_setting( 'sidemenu_line_bg_color_active', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_SIDE_MENU_LINE_BG_COLOR_ACTIVE));
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'sidemenu_line_bg_color_active', array('label' => __( 'Edit ACTIVE Line Background Color', 'mpat' ),'palette' => true,'section' => 'mpat_side_menu')));
	
	$wp_customize->add_setting( 'sidemenu_separator_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_SIDE_MENU_SEPARATOR_COLOR));
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'sidemenu_separator_color', array('label' => __( 'Edit Separator Color', 'mpat' ),'palette' => true,'section' => 'mpat_side_menu')));
	
	$wp_customize->add_setting( 'sidemenu_width', array( 'type' => 'theme_mod','default' => MPAT_DEFAULT_SIDE_MENU_WIDTH));
	$wp_customize->add_control( 'sidemenu_width', array('label' => __( 'Width in px' ),'section' => 'mpat_side_menu','type' => 'number'));
		
	/*********** Launcher settings **********/
	$wp_customize->add_section( 'mpat_launcher_settings', array('title' => __( 'Launcher', 'mpat' ),'description' => __( 'Modify the Global Launcher Settings' ),'priority' => 202,'panel' => 'mpat_components') );
	
	$wp_customize->add_setting( 'launcher_font_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LAUNCHER_FONT_SIZE));
	$wp_customize->add_control( 'launcher_font_size', array('label' => __( 'Launcher Font Size in px' ),'section' => 'mpat_launcher_settings','type' => 'number'));
	 
	$wp_customize->add_setting( 'launcher_line_height', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_LINE_HEIGHT));
	$wp_customize->add_control( 'launcher_line_height', array('label' => __( 'Launcher Line Height in px' ),'section' => 'mpat_launcher_settings','type' => 'number'));
	
	$wp_customize->add_setting( 'launcher_font_style', array('default' => MPAT_DEFAULT_LAUNCHER_FONT_STYLE));
	$wp_customize->add_setting( 'launcher_font_float', array('default' => MPAT_DEFAULT_LAUNCHER_FONT_FLOAT));
	$wp_customize->add_setting( 'launcher_font_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_FONT_COLOR));
	$wp_customize->add_setting( 'launcher_bg_color', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_BG_COLOR) );
	$wp_customize->add_setting( 'launcher_font_color_focused', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_FONT_COLOR_FOCUSED) );
	$wp_customize->add_setting( 'launcher_bg_color_focused', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_BG_COLOR_FOCUSED) );
	$wp_customize->add_setting( 'launcher_border_color_focused', array('type' => 'theme_mod','default' => MPAT_DEFAULT_LAUNCHER_BORDER_COLOR_FOCUSED) );
	$wp_customize->add_control('launcher_font_style', array(
			'type' => 'select',
			'label' => 'Choose a Style for Launcher font:',
			'section' => 'mpat_launcher_settings',
			'choices' => array(
					'bolder' => 'Bolder',
					'bold' => 'Bold',
					'normal' => 'Normal',
					'lighter' => 'Lighter',
			),
	) );
	$wp_customize->add_control('launcher_font_float', array(
			'type' => 'select',
			'label' => 'Choose a position for Launcher font:',
			'section' => 'mpat_launcher_settings',
			'choices' => array(
					'center' => 'Center',
					'left' => 'Left',
					'right' => 'Right',
			),
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'launcher_font_color', array('label' => __( 'Edit Launcher Font Color', 'mpat' ),'section' => 'mpat_launcher_settings') ) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'launcher_bg_color', array('label' => __( 'Edit Launcher Background Font Color', 'mpat' ),'palette' => true,'section' => 'mpat_launcher_settings',) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'launcher_font_color_focused', array('label' => __( 'Edit Launcher Font Color - focused', 'mpat' ),'section' => 'mpat_launcher_settings') ) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'launcher_bg_color_focused', array('label' => __( 'Edit Launcher Background Font Color - focused', 'mpat' ),'palette' => true,'section' => 'mpat_launcher_settings') ) );
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'launcher_border_color_focused', array('label' => __( 'Edit Launcher Border Color - focused', 'mpat' ),'palette' => true,'section' => 'mpat_launcher_settings') ) );

	/* Video Player Settings */
	$wp_customize->add_section( 'mpat_playercontrols', array('title' => __( 'Video Player Settings', 'mpat' ), 'description' => __( 'Modify Global Video Player Settings' ), 'panel' => 'mpat_components') );
  $wp_customize->add_setting( 'playercontrols_height', array(
      'type' => 'theme_mod',
      'default' => '24',
  ) );
  $wp_customize->add_setting( 'playercontrols_bg_color', array(
      'type' => 'option',
      'default' => 'rgba(0, 0, 0, 1);'
  ) );
  $wp_customize->add_setting( 'playercontrols_highlight_color', array(
      'type' => 'option',
      'default' => 'rgba(100,100,100, 1)'
  ) );
  $wp_customize->add_setting( 'playercontrols_text_size', array(
      'type' => 'theme_mod',
      'default' => '14',
  ) );
 $wp_customize->add_control( 'playercontrols_height', array(
      'label' => __( 'Player Control Height in px' ),
      'section' => 'mpat_playercontrols',
      'type' => 'number'
  ) );
  $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'playercontrols_bg_color', array(
      'label' => __( 'Player Controls Background Color', 'mpat' ),
      'palette' => true,
      'section' => 'mpat_playercontrols'
  ) ) );
  $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'playercontrols_highlight_color', array(
      'label' => __( 'Player Controls Highlight Color', 'mpat' ),
      'palette' => true,
      'section' => 'mpat_playercontrols'
  ) ) );
  $wp_customize->add_control( 'playercontrols_text_size', array(
      'label' => __( 'Font Size in px' ),
      'section' => 'mpat_playercontrols',
      'type' => 'number'
  ) );

    /* Video Player Default Icons */
    $icons = array(
        'mpat_icons_video_loading' => array( 'filename' => 'video_loading.gif', 'label' => __('Video loading animation', 'mpat')),
        'mpat_icons_video_play' => array( 'filename' => 'icon_play_white.png', 'label' => __('Video play button', 'mpat')),
        'mpat_icons_video_pause' => array( 'filename' => 'icon_pause_white.png', 'label' => __('Video pause button', 'mpat')),
        'mpat_icons_video_stop' => array( 'filename' => 'icon_stop_white.png', 'label' => __('Video stop button', 'mpat')),
        'mpat_icons_video_forward' => array( 'filename' => 'icon_forward_white.png', 'label' => __('Video forward button', 'mpat')),
        'mpat_icons_video_rewind' => array( 'filename' => 'icon_rewind_white.png', 'label' => __('Video rewind button', 'mpat')),
        'mpat_icons_video_fullscreen' => array( 'filename' => 'icon_fullscreen_white.png', 'label' => __('Video fullscreen button', 'mpat')),
        'mpat_icons_video_fullscreenexit' => array( 'filename' => 'icon_fullscreen_exit_white.png', 'label' => __('Video exit fullscreen button', 'mpat')),
        'mpat_icons_video_arrowleft' => array( 'filename' => 'icon_arrow_left_white.png', 'label' => __('Video arrow left button', 'mpat')),
        'mpat_icons_video_arrowright' => array( 'filename' => 'icon_arrow_right_white.png', 'label' => __('Video arrow right button', 'mpat')),
        'mpat_icons_video_zoomin' => array( 'filename' => 'icon_zoom-in_white.png', 'label' => __('Video zoom in button', 'mpat')),
        'mpat_icons_video_zoomout' => array( 'filename' => 'icon_zoom-out_white.png', 'label' => __('Video zoom out button', 'mpat')),
        
      );
    foreach ($icons as $key => $values) {
      $wp_customize->add_setting($key, array( 
          'type' => 'option',
          'default' => get_template_directory_uri() . "/shared/assets/videoplayer/{$values['filename']}",
      ));
      // since stupid wordpress does not handle the default value in frontend we initialize
      // options with the default value, namely the "default" above is completely useless execpt in the customizer UI
      if (!get_option($key)) add_option($key, get_template_directory_uri() . "/shared/assets/videoplayer/{$values['filename']}");
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, array(
          'label' => __( $values['label'] ),
          'section' => 'mpat_playercontrols',
          'mime_type' => 'image',
      )));
    }

	
	/*********** Gallery **********/
	
	$wp_customize->add_section( 'mpat_gallery', array('title' => __( 'Gallery', 'mpat' ),'description' => __( 'Modify Global Gallery Settings' ), 'priority' => 202,'panel' => 'mpat_components') );
	
	$wp_customize->add_setting( 'mpat_gallery_background_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_BACKGROUND_COLOR));
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'mpat_gallery_background_color', array('label' => __( 'Gallery background color', 'mpat' ),'section' => 'mpat_gallery') ) );
	
	$wp_customize->add_setting( 'mpat_gallery_arrow_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_ARROW_COLOR));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mpat_gallery_arrow_color', array('label' => __( 'Gallery arrow color', 'mpat' ),'section' => 'mpat_gallery') ) );
	
	$wp_customize->add_setting( 'mpat_gallery_arrow_background_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_ARROW_BACKGROUND_COLOR));
	$wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'mpat_gallery_arrow_background_color', array('label' => __( 'Gallery arrow background color', 'mpat' ),'section' => 'mpat_gallery') ) );
	
	$wp_customize->add_setting( 'mpat_gallery_arrow_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_ARROW_SIZE));
	$wp_customize->add_control( 'mpat_gallery_arrow_size', array('label' => __( 'Gallery arrow size in px', 'mpat' ),'section' => 'mpat_gallery','type' => 'number'));
	
	$wp_customize->add_setting( 'mpat_gallery_arrow_padding', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_ARROW_PADDING));
	$wp_customize->add_control( 'mpat_gallery_arrow_padding', array('label' => __( 'Gallery arrow padding in px' , 'mpat'),'section' => 'mpat_gallery','type' => 'number'));
	
	$wp_customize->add_setting( 'mpat_gallery_arrow_border_radius', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_ARROW_BORDER_RADIUS));
	$wp_customize->add_control( 'mpat_gallery_arrow_border_radius', array('label' => __( 'Gallery arrow border radius in px', 'mpat' ),'section' => 'mpat_gallery','type' => 'number'));
	
	$wp_customize->add_setting( 'mpat_gallery_dot_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_DOT_COLOR));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mpat_gallery_dot_color', array('label' => __( 'Gallery dots color', 'mpat' ),'section' => 'mpat_gallery') ) );
	
	$wp_customize->add_setting( 'mpat_gallery_dot_color_focused', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_GALLERY_DOT_COLOR_FOCUSED));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mpat_gallery_dot_color_focused', array('label' => __( 'Gallery dot focused color ', 'mpat' ),'section' => 'mpat_gallery') ) );


  /*********** Link **********/
  $wp_customize->add_section( 'mpat_links', array(
    'title' => __( 'Links', 'mpat'),
    'description' => __( 'Modify Global Link Settings', 'mpat' ),
    'panel' => 'mpat_components',
    ) );
    $wp_customize->add_setting('link_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_SIZE));
    $wp_customize->add_setting('link_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_COLOR));
    $wp_customize->add_setting('link_background_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_BACKGROUND_COLOR));
    $wp_customize->add_setting('link_style', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_WEIGHT));
    $wp_customize->add_setting('link_decoration', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_DECORATION));
    $wp_customize->add_setting('link_color_focused', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_COLOR_FOCUSED));
    $wp_customize->add_setting('link_background_color_focused', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINK_BACKGROUND_COLOR_FOCUSED));
    
    $wp_customize->add_control( 'link_size', array(
            'label' => __( 'Link Size in px' , 'mpat'),
            'section' => 'mpat_links',
            'type' => 'number',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
            'label' => __( 'Edit Link Color', 'mpat' ),
            'section' => 'mpat_links',
    ) ) );
    $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'link_background_color', array(
            'label' => __( 'Edit Link Background Color', 'mpat' ),
            'palette' => true,
            'section' => 'mpat_links',
    ) ) );
    $wp_customize->add_control('link_style', array(
            'type' => 'select',
            'label' => 'Choose a Style for Links:',
            'section' => 'mpat_links',
            'choices' => array(
                    'bolder' => 'Bolder',
                    'bold' => 'Bold',
                    'normal' => 'Normal',
                    'lighter' => 'Lighter',
            ),
    ) );
    $wp_customize->add_control('link_decoration', array(
            'type' => 'select',
            'label' => 'Choose a Style for link decoration:',
            'section' => 'mpat_links',
            'choices' => array(
                    "none" => "None",
                    "underline" => "Underline",
                    "overline" => "Overline",
                    "line-through" => "Line-through",
            )
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color_focused', array(
            'label' => __( 'Edit Link Color - focused', 'mpat' ),
            'section' => 'mpat_links',
    ) ) );
    $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'link_background_color_focused', array(
            'label' => __( 'Edit Link Color - focused', 'mpat' ),
            'palette' => true,
            'section' => 'mpat_links',
    ) ) );

	/***************************************************************/
	/**					End	Components Settings	Panel		      **/
	/***************************************************************/
	
	
	/*********** AUDIO **********/

    /* ToDo FOKUS */
    $wp_customize->add_section( 'mpat_audio', array(
        'title' => __( 'Audio Settings', 'mpat' ),
        'description' => __( 'Modify Global Audio Settings soon', 'mpat' )
    ) );

    /*********** TEXT **********/
    $wp_customize->add_panel( 'mpat_font', array(
        'title' => __( 'Font Settings', 'mpat' ),
        'description' => __( 'Modify Global Font Settings', 'mpat'),
    ) );


    /* Text */
    $wp_customize->add_section( 'mpat_text', array(
        'title' => __( 'Text', 'mpat'),
        'description' => __( 'Modify Global Font Settings', 'mpat' ),
        'panel' => 'mpat_font'
    ) );
    $wp_customize->add_setting( 'font_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_SIZE) );
    $wp_customize->add_setting( 'line_height', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_LINE_HEIGHT) );
    $wp_customize->add_setting( 'font_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    
     $wp_customize->add_control( 'font_size', array(
        'label' => __( 'Font Size in px', 'mpat' ),
        'section' => 'mpat_text',
        'type' => 'number'
     ) );
     $wp_customize->add_control( 'line_height', array(
            'label' => __( 'Line height in px (usually 1.5 times the font size)', 'mpat' ),
            'section' => 'mpat_text',
            'type' => 'number'
     ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'font_color', array(
        'label' => __( 'Edit Font Color', 'mpat' ),
        'section' => 'mpat_text'
    ) ) );

    /*Headline*/
    $wp_customize->add_section( 'mpat_headline', array(
        'title' => __( 'Headlines', 'mpat'),
        'description' => __( 'Set Headline Settings for your App', 'mpat' ),
        'panel' => 'mpat_font'
    ) );

    $wp_customize->add_setting( 'hx_style', array('default' => MPAT_DEFAULT_HX_WEIGHT));
    $wp_customize->add_setting( 'h1_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H1_SIZE) );
    $wp_customize->add_setting( 'h2_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H2_SIZE) );
    $wp_customize->add_setting( 'h3_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H3_SIZE) );
    $wp_customize->add_setting( 'h4_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H4_SIZE) );
    $wp_customize->add_setting( 'h5_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H5_SIZE) );
    $wp_customize->add_setting( 'h6_size', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_H6_SIZE) );
    
    $wp_customize->add_setting( 'h1_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    $wp_customize->add_setting( 'h2_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    $wp_customize->add_setting( 'h3_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    $wp_customize->add_setting( 'h4_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    $wp_customize->add_setting( 'h5_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );
    $wp_customize->add_setting( 'h6_color', array('type' => 'theme_mod', 'default' => MPAT_DEFAULT_FONT_COLOR) );

    $wp_customize->add_control('hx_style', array(
        'type' => 'select',
        'label' => 'Choose a Style for Headlines:',
        'section' => 'mpat_headline',
        'choices' => array(
          'bolder' => 'Bolder',
            'bold' => 'Bold',
            'normal' => 'Normal',
            'lighter' => 'Lighter',
      ),
    ) );
    
    for ($i = 1; $i <=6; $i++) {
        $wp_customize->add_control( "h{$i}_size", array(
                'label' => __( "Headline {$i} Size in px", 'mpat' ),
                'section' => 'mpat_headline',
                'type' => 'number'
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "h{$i}_color", array(
                'label' => __( "Edit Headline {$i} Color", 'mpat' ),
                'section' => 'mpat_headline'
        ) ) );
    }

    /*********** Social Feed **********/

    /* ToDo IRT */
    $wp_customize->add_section( 'mpat_feed', array(
        'title' => __( 'Social Feed Settings', 'mpat' ),
        'description' => __( 'Modify Global Social Feed Settings soon', 'mpat' ),
    ) );

	/*********** Video **********/

	/* Video Player Controls */
	
	$wp_customize->add_section( 'mpat_playercontrols', array(
			'title' => __( 'Video Player Settings', 'mpat' ),
			'description' => __( 'Modify Global Video Player Settings' , 'mpat')
	) );
    $wp_customize->add_setting( 'playercontrols_height', array(
        'type' => 'theme_mod',
        'default' => '24',
    ) );
    $wp_customize->add_setting( 'playercontrols_bg_color', array(
        'type' => 'option',
        'default' => 'rgba(0, 0, 0, 1);'
    ) );
    $wp_customize->add_setting( 'playercontrols_highlight_color', array(
        'type' => 'option',
        'default' => 'rgba(100,100,100, 1)'
    ) );
    $wp_customize->add_setting( 'playercontrols_text_size', array(
        'type' => 'theme_mod',
        'default' => '14',
    ) );
   $wp_customize->add_control( 'playercontrols_height', array(
        'label' => __( 'Player Control Height in px', 'mpat'),
        'section' => 'mpat_playercontrols',
        'type' => 'number'
    ) );
    $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'playercontrols_bg_color', array(
        'label' => __( 'Player Controls Background Color', 'mpat'),
        'palette' => true,
        'section' => 'mpat_playercontrols'
    ) ) );
    $wp_customize->add_control( new MPAT_Customize_Alpha_Color_Control( $wp_customize, 'playercontrols_highlight_color', array(
        'label' => __( 'Player Controls Highlight Color', 'mpat'),
        'palette' => true,
        'section' => 'mpat_playercontrols'
    ) ) );
    $wp_customize->add_control( 'playercontrols_text_size', array(
        'label' => __( 'Font Size in px', 'mpat'),
        'section' => 'mpat_playercontrols',
        'type' => 'number'
    ) );


	/*********** Hotspot **********/

    /* ToDo IRT */
    $wp_customize->add_section( 'mpat_hotspot', array(
        'title' => __( 'HotSpot Settings', 'mpat' ),
        'description' => __( 'Modify Global HotSpot Settings soon', 'mpat' ),
    ) );
} //END of function mpat_customizer_register( $wp_customize )


function mpat_customizer_css()
{
    ?><style type="text/css">
        body {
            color: <?php echo get_theme_mod( 'font_color', MPAT_DEFAULT_FONT_COLOR); ?>;
            font-size: <?php echo get_theme_mod( 'font_size', MPAT_DEFAULT_FONT_SIZE ); ?>px;
            line-height: <?php echo get_theme_mod( 'line_height', MPAT_DEFAULT_LINE_HEIGHT ); ?>px;
        }

        h1, h2, h3, h4, h5, h6{
          font-weight: <?php echo get_theme_mod( 'hx_style', MPAT_DEFAULT_HX_WEIGHT); ?>;
        }

        h1{
          font-size: <?php echo get_theme_mod( 'h1_size', MPAT_DEFAULT_H1_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h1_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        h2{
          font-size: <?php echo get_theme_mod( 'h2_size', MPAT_DEFAULT_H2_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h2_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        h3{
          font-size: <?php echo get_theme_mod( 'h3_size', MPAT_DEFAULT_H3_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h3_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        h4{
          font-size: <?php echo get_theme_mod( 'h4_size', MPAT_DEFAULT_H4_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h4_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        h5{
          font-size: <?php echo get_theme_mod( 'h5_size', MPAT_DEFAULT_H5_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h5_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        h6{
          font-size: <?php echo get_theme_mod( 'h6_size', MPAT_DEFAULT_H6_SIZE ); ?>px;
          color: <?php echo get_theme_mod( 'h6_color', MPAT_DEFAULT_FONT_COLOR); ?>;
        }

        .page {
            background-color: <?php echo get_theme_mod('bg_color', MPAT_DEFAULT_BACKGROUND_COLOR) ?>;
            background-image: url(<?php echo get_theme_mod('bg_image', MPAT_DEFAULT_BACKGROUND_IMAGE) ?>);
        }

	    .highlight{
	        border-color: transparent;
	    	border-width: <?php echo get_theme_mod( 'focusedborder_width', MPAT_DEFAULT_BORDER_WIDTH); ?>px; 
	        border-style: <?php echo get_theme_mod( 'focusedborder_style', MPAT_DEFAULT_BORDER_STYLE); ?>; 
	        border-radius: <?php echo get_theme_mod( 'focusedborder_radius', MPAT_DEFAULT_BORDER_RADIUS); ?>px;
            top: -<?php echo get_theme_mod( 'focusedborder_width', MPAT_DEFAULT_BORDER_WIDTH); ?>px;
            right: -<?php echo get_theme_mod( 'focusedborder_width', MPAT_DEFAULT_BORDER_WIDTH); ?>px;
            bottom: -<?php echo get_theme_mod( 'focusedborder_width', MPAT_DEFAULT_BORDER_WIDTH); ?>px;
            left: -<?php echo get_theme_mod( 'focusedborder_width', MPAT_DEFAULT_BORDER_WIDTH); ?>px;
            
	    }
        .highlight.focused{
            border-color: <?php echo get_theme_mod( 'focusedborder_color', MPAT_DEFAULT_BORDER_COLOR); ?>;
        }


      .launcherElementLabel{
        text-align: <?php echo get_theme_mod( 'launcher_font_float' ); ?>;
        background-color: <?php echo get_theme_mod( 'launcher_bg_color' ); ?>;
      }
      .launcherElementLabel a{
        font-size: <?php echo get_theme_mod( 'launcher_font_size' ); ?>px;
        line-height: <?php echo get_theme_mod( 'launcher_line_height' ); ?>px;
        color: <?php echo get_theme_mod( 'launcher_font_color' ); ?>;
        font-weight: <?php echo get_theme_mod( 'launcher_font_style' ); ?>;
      }
      .launcherElement.focused{
        border-color: <?php echo get_theme_mod( 'launcher_border_color_focused' ); ?>;
      }
      .launcherElement.focused .launcherElementLabel{
        background-color: <?php echo get_theme_mod( 'launcher_bg_color_focused' ); ?>;
      }
      .launcherElement.focused .launcherElementLabel a{
        color: <?php echo get_theme_mod( 'launcher_font_color_focused' ); ?>;
      }


        .video-controls-container,.video360-controls-container{
            background-color: <?php echo get_option( 'playercontrols_bg_color' );?>;
            height: <?php echo get_theme_mod( 'playercontrols_height' ); ?>px;
        }
        .video-button.active,.button-360.active{
            background-color: <?php echo get_option( 'playercontrols_highlight_color' );?>;
        }
        .video-progressinfo .video-time-elapsed, .video-progressinfo .video-time-full,
        .video360-progressinfo .video360-time-elapsed, .video360-progressinfo .video360-time-full {
            line-height: <?php echo get_theme_mod( 'playercontrols_height' )?get_theme_mod( 'playercontrols_height' )+4:"" ?>px;
            font-size: <?php echo get_theme_mod( 'playercontrols_text_size' ); ?>px;
        }
        .page_arrow_down, .page_arrow_up{
          fill: <?php echo get_option( 'icon_arrow' );?>;
          opacity: <?php echo get_option( 'icon_arrow_opacity' );?>;
            left: <?php echo (640-(get_option( 'icon_arrow_width' )/2));?>px;
          width: <?php echo get_option( 'icon_arrow_width' );?>px;
            height: <?php echo get_option( 'icon_arrow_width' );?>px;
      }

      .link-background{
          background-color: <?php echo get_theme_mod( 'link_background_color', MPAT_DEFAULT_LINK_BACKGROUND_COLOR); ?>;
      }
      .link-background p{
          color: <?php echo get_theme_mod( 'link_color', MPAT_DEFAULT_LINK_COLOR); ?>;
          font-size: <?php echo get_theme_mod( 'link_size', MPAT_DEFAULT_LINK_SIZE); ?>px;
          font-weight: <?php echo get_theme_mod( 'link_style', MPAT_DEFAULT_LINK_WEIGHT); ?>;
          text-decoration: <?php echo get_theme_mod( 'link_decoration', MPAT_DEFAULT_LINK_DECORATION); ?>;
      }
      .link-background-focused{
          background-color: <?php echo get_theme_mod( 'link_background_color_focused', MPAT_DEFAULT_LINK_BACKGROUND_COLOR_FOCUSED); ?>;
      }
      .link-background-focused p{
          color: <?php echo get_option( 'link_color_focused', MPAT_DEFAULT_LINK_COLOR_FOCUSED); ?>;
      }

      .side-menu{
        background-color: <?php echo get_theme_mod( 'sidemenu_bg_color', MPAT_DEFAULT_SIDE_MENU_BG_COLOR );?>;
        font-size: <?php echo get_theme_mod( 'sidemenu_font_size', MPAT_DEFAULT_SIDE_MENU_FONT_SIZE);?>px;
        line-height: <?php echo get_theme_mod('sidemenu_line_height', MPAT_DEFAULT_SIDE_MENU_LINE_HEIGHT);?>px;
        color: <?php echo get_theme_mod( 'sidemenu_font_color', MPAT_DEFAULT_SIDE_MENU_FONT_COLOR );?>;
        width: <?php echo get_theme_mod( 'sidemenu_width', MPAT_DEFAULT_SIDE_MENU_WIDTH );?>px;
      }

      .side-menu .side-menu-item-active {
          background-color: <?php echo get_theme_mod( 'sidemenu_line_bg_color_active', MPAT_DEFAULT_SIDE_MENU_LINE_BG_COLOR_ACTIVE);?>;
          color: <?php echo get_theme_mod( 'sidemenu_font_color_active', MPAT_DEFAULT_SIDE_MENU_FONT_COLOR_ACTIVE ) ;?>;
      }

      .side-menu hr  {
        color: <?php echo get_theme_mod(  'sidemenu_separator_color', MPAT_DEFAULT_SIDE_MENU_SEPARATOR_COLOR );?>;
      }


      .page-element-content svg{
      fill: <?php echo get_option( 'iconset' );?>;
          opacity: <?php echo get_option( 'iconset_opacity' );?>;
    }

      .focused div svg{
      fill: <?php echo get_option( 'iconset_focused' );?>;
          opacity: <?php echo get_option( 'iconset_focused_opacity' );?>;
    }

    .active div svg{
      fill: <?php echo get_option( 'iconset_active' );?>;
          opacity: <?php echo get_option( 'iconset_active_opacity' );?>;
    }
    
    .menu-component .menu-item-active span{
        font-size: <?php echo get_theme_mod( 'general_menu_font_size_active', MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE_ACTIVE);?>px;
        font-weight: <?php echo get_theme_mod( 'general_menu_text_weight_active', MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT_ACTIVE); ?>;
        color: <?php echo get_theme_mod( 'general_menu_font_color_active', MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR_ACTIVE ) ;?>;
        background-color: <?php echo get_theme_mod( 'general_menu_background_color_active', MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR_ACTIVE);?>;
        line-height: <?php echo get_theme_mod('general_menu_line_height_active', MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT_ACTIVE);?>px;
    }
    
    .menu-component{
        font-size: <?php echo get_theme_mod( 'general_menu_font_size', MPAT_DEFAULT_GENERAL_MENU_FONT_SIZE);?>px;
        color: <?php echo get_theme_mod( 'general_menu_font_color', MPAT_DEFAULT_GENERAL_MENU_FONT_COLOR ) ;?>;
        line-height: <?php echo get_theme_mod('general_menu_line_height', MPAT_DEFAULT_GENERAL_MENU_LINE_HEIGHT);?>px;
        font-weight: <?php echo get_theme_mod( 'general_menu_text_weight', MPAT_DEFAULT_GENERAL_MENU_TEXT_WEIGHT); ?>;
        border-width: <?php echo get_theme_mod( 'general_menu_border_width', MPAT_DEFAULT_GENERAL_MENU_BORDER_WIDTH); ?>px;
        border-radius: <?php echo get_theme_mod( 'general_menu_border_radius', MPAT_DEFAULT_GENERAL_MENU_BORDER_RADIUS); ?>px;
        border-color: <?php echo get_theme_mod( 'general_menu_border_color', MPAT_DEFAULT_GENERAL_MENU_BORDER_COLOR ) ;?>;
        background-color: <?php echo get_theme_mod( 'general_menu_background_color', MPAT_DEFAULT_GENERAL_MENU_BACKGROUND_COLOR);?>;
        padding: <?php echo get_theme_mod( 'general_menu_padding', MPAT_DEFAULT_GENERAL_MENU_PADDING);?>px;
    }
    
    .gallery-component{
        background-color: <?php echo get_theme_mod( 'mpat_gallery_background_color', MPAT_DEFAULT_GALLERY_BACKGROUND_COLOR);?>; 
    }
    
    .gallery-arrow{
        font-size: <?php echo get_theme_mod( 'mpat_gallery_arrow_size', MPAT_DEFAULT_GALLERY_ARROW_SIZE);?>px;
        padding: <?php echo get_theme_mod( 'mpat_gallery_arrow_padding', MPAT_DEFAULT_GALLERY_ARROW_PADDING);?>px;
        border-radius: <?php echo get_theme_mod( 'mpat_gallery_arrow_border_radius', MPAT_DEFAULT_GALLERY_ARROW_BORDER_RADIUS);?>px;
        color: <?php echo get_theme_mod( 'mpat_gallery_arrow_color', MPAT_DEFAULT_GALLERY_ARROW_COLOR);?>;
        background-color: <?php echo get_theme_mod( 'mpat_gallery_arrow_background_color', MPAT_DEFAULT_GALLERY_ARROW_BACKGROUND_COLOR);?>;
    }
    
    .gallery-dot circle{
        fill: <?php echo get_theme_mod( 'mpat_gallery_dot_color', MPAT_DEFAULT_GALLERY_DOT_COLOR);?>;
    }
    .gallery-dot-focused circle{
        fill: <?php echo get_theme_mod( 'mpat_gallery_dot_color_focused', MPAT_DEFAULT_GALLERY_DOT_COLOR_FOCUSED);?>;
    }
    
    </style><?php
}


//MPAT Editor text preview
function mpat_editor_style()
{
    ?><style type="text/css">
        <?php
        // TODO imagecontent-preview is everthing but a class related to image preview.
        // Need to find better name (and better styles assignment, namely inherit...extends ?>
       .imagecontent-preview p {
            color:  <?php echo get_theme_mod( 'link_color', MPAT_DEFAULT_LINK_COLOR); ?>;
            background-color: <?php echo get_theme_mod( 'link_background_color', MPAT_DEFAULT_LINK_BACKGROUND_COLOR); ?>;
            font-size: <?php echo get_theme_mod( 'link_size', MPAT_DEFAULT_LINK_SIZE); ?>px ;
            font-weight: <?php echo get_theme_mod( 'link_style', MPAT_DEFAULT_LINK_WEIGHT); ?>;
            text-decoration: <?php echo get_theme_mod( 'link_decoration', MPAT_DEFAULT_LINK_DECORATION); ?>;
        }
  }
  </style><?php
}

//TODO: Add Video Player Controls changes
//TODO: Add Icon Set changes

//-----------------------------------------------------
// Frontend Changes
//-----------------------------------------------------

/* Comments */
function mpat_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    if ($comment->comment_approved == '1') :
        ?>
        <li>
            <article id="comment-<?php comment_ID() ?>">
                <?php echo get_avatar($comment); ?>
                <h4><?php comment_author_link() ?></h4>
                <time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?>
                        at <?php comment_time() ?></a></time>
                <?php comment_text() ?>
            </article>
        </li>
    <?php
    endif;
}

function mpat_content_type($mime_type, $post_id)
{
    header('Content-type: application/vnd.hbbtv.xhtml+xml; charset=utf-8');
    // Process content here
    return $mime_type;
}

show_admin_bar(false);

?>
