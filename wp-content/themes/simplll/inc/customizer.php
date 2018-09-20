<?php
/**
 * simplll Theme Customizer
 *
 * @package simplll
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function simplll_customize_register( $wp_customize ) {
	
	
	
	
	/*******************************************
	SITE IDENTITY
	********************************************/
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'simplll_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'simplll_customize_partial_blogdescription',
		) );
	}
	/*******************************************
	SITE IDENTITY
	********************************************/
	
	
	
	
	/*******************************************
	COLORS
	********************************************/
	
	// body text color
	$txtcolors[] = array(
		'slug'=>'body_text_color', 
		'default' => '#404040',
		'label' => __( 'Body Text Color', 'simplll' )
	);
	 
	// link color
	$txtcolors[] = array(
		'slug'=>'link_color', 
		'default' => '#00bcd4',
		'label' => __( 'Link Color', 'simplll' )
	);
	 
	// link color ( hover )
	$txtcolors[] = array(
		'slug'=>'hover_link_color', 
		'default' => '#0040ff',
		'label' => __( 'Link Color (on hover)', 'simplll' )
	);
	
	
	// add the settings and controls for each color
	foreach( $txtcolors as $txtcolor ) {
	 
		// SETTINGS
		$wp_customize->add_setting(
			$txtcolor['slug'], array(
				'default' => $txtcolor['default'],
				'type' => 'theme_mod', 
				'capability' =>  'edit_theme_options',
				'transport'   => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$txtcolor['slug'], 
				array('label' => $txtcolor['label'], 
				'section' => 'colors',
				'settings' => $txtcolor['slug'])
			)
		);
		 
	} // end foreach
	
	/*******************************************
	COLORS
	********************************************/
	
	
	function simplll_sanitize($value) {
		return absint($value);
	}
	
	
	/*******************************************
	TYPOGRAPHY
	********************************************/
	$wp_customize->add_section( 'typography', array (
        'title'    => __( 'Typography', 'simplll' ),
        'priority' => 25,
    ) );

    $wp_customize->add_setting( 'html_font_size', array(
        'default' => '16',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
		'sanitize_callback' => 'simplll_sanitize',
    ) );

    $wp_customize->add_control( 'html_font_size', array(
        'type' => 'range',
        'priority' => 10,
        'section' => 'typography',
        'label' => __( 'Font Size', 'simplll' ),
        'description' => 'base font size',
		'input_attrs' => array(
				'min' => 8,
				'max' => 60,
				'step' => 1,
				'style' => 'width: 100%',
				),
		));
	/*******************************************
	TYPOGRAPHY
	********************************************/
	
	
}
add_action( 'customize_register', 'simplll_customize_register' );



/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function simplll_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function simplll_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function simplll_customize_preview_js() {
	wp_enqueue_script( 'simplll-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'simplll_customize_preview_js' );





/*******************************************
CUSTOM OPTIONS
********************************************/
function simplll_customize_options() {
	
	
	
	// html font size
	$html_font_size = get_theme_mod('html_font_size');
	
	// body text color
	$body_text_color = get_theme_mod( 'body_text_color' );
	 
	// link color
	$link_color = get_theme_mod( 'link_color' );
	 
	// link hover color
	$hover_link_color = get_theme_mod( 'hover_link_color' );
	
	
	/****************************************
	STYLING
	****************************************/
	?>
    
	<style>
	
		@media screen and (min-width: 992px) {
			
			/* html font size */
			html { 
				font-size:  <?php echo esc_attr($html_font_size); ?>px
				}

		}
		
		
		/* body text color */
		body, button, input, select, optgroup, textarea { 
			color:  <?php echo esc_attr($body_text_color); ?>
			}
		.site-title a, .comment-respond, thead, .cat-links a, button, input[type="button"], input[type="reset"], input[type="submit"] { 
			border-color: <?php echo esc_attr($body_text_color); ?>
			}
		.single h1.entry-title:after, .page h1.entry-title:after, hr { 
			background: <?php echo esc_attr($body_text_color); ?> 
			}
	
		/* links color */
		a { 
			color:  <?php echo esc_attr($link_color); ?>;
			}
		 
		/* hover links color */
		a:hover {
			color:  <?php echo esc_attr($hover_link_color); ?>;
			}
	 
	</style>
		 
	<?php
	
	}
add_action( 'wp_head', 'simplll_customize_options' );

/*******************************************
CUSTOM OPTIONS
********************************************/