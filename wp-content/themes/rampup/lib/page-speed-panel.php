<?php
/**
* Theme I'LL customizer page speed panel
* @package WordPress
* @subpackage I'LL

*/

	/*------------------------------------------------------------------------------------
	/* Page Speed Settings
	/*----------------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'ill_page_speed_settings', array(
	'priority' => 75,
	'capability' => 'edit_theme_options',
	'title' => __( 'Page speed settings', 'ill' ),
	) );

		/*------------------------------------------------------------------------------------
		/* Optimized Jquery
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'ill_jquery_optimized', array (
			'title' => __( 'Optimized jQuery', 'ill' ),
			'priority' => 1,
			'panel' => 'ill_page_speed_settings',
		) );

			// Put jquery at the bottom
			$wp_customize->add_setting( 'jquery_bottom', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'ill_customize_sanitize_checkbox',
			) );
			$wp_customize->add_control( 'jquery_bottom', array(
				'label'	 => __( 'Put jQuery at the bottom', 'ill' ),
				'section' => 'ill_jquery_optimized',
				'settings' => 'jquery_bottom',
				'type' => 'checkbox',
				'priority' => 1,
			) );

		/*------------------------------------------------------------------------------------
		/* Optimized CSS
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'ill_css_optimized', array (
			'title' => __( 'Optimized CSS', 'ill' ),
			'priority' => 2,
			'panel' => 'ill_page_speed_settings',
		) );

			// Minified style.css
			$wp_customize->add_setting( 'css_minified', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'ill_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'css_minified', array(
				'label' => __( 'Use binding style.css', 'ill' ),
				'description' => __( 'When adding CSS to style sheet, please turn off CSS compression.After adding CSS, please turn on CSS compression.', 'ill' ),
				'section' => 'ill_css_optimized',
				'settings' => 'css_minified',
				'type' => 'checkbox',
				'priority' => 1,
			) );

			// Lazy Load font-awesome.css
			$wp_customize->add_setting( 'font_awesome_lazyload', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'ill_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'font_awesome_lazyload', array(
				'label' => __( 'Lazy Load font-awesome.css', 'ill' ),
				'section' => 'ill_css_optimized',
				'settings' => 'font_awesome_lazyload',
				'type' => 'checkbox',
				'priority' => 2,
			) );

		/*------------------------------------------------------------------------------------
		/* Optimized HTML
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'ill_html_optimized', array (
			'title' => __( 'Optimized HTML', 'ill' ),
			'priority' => 3,
			'panel' => 'ill_page_speed_settings',
		) );

			// Minified HTML
			$wp_customize->add_setting( 'html_minified', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'ill_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'html_minified', array(
				'label' => __( 'Minified HTML', 'ill' ),
				'section' => 'ill_html_optimized',
				'settings' => 'html_minified',
				'type' => 'checkbox',
				'priority' => 1,
			) );
