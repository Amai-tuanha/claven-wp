<?php
/**
* Theme I'LL customizer page speed panel
* @package WordPress
* @subpackage I'LL

*/

	/*------------------------------------------------------------------------------------
	/* Page Speed Settings
	/*----------------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'rampup_page_speed_settings', array(
	'priority' => 75,
	'capability' => 'edit_theme_options',
	'title' => __( 'Page speed settings', 'rampup' ),
	) );

		/*------------------------------------------------------------------------------------
		/* Optimized Jquery
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'rampup_jquery_optimized', array (
			'title' => __( 'Optimized jQuery', 'rampup' ),
			'priority' => 1,
			'panel' => 'rampup_page_speed_settings',
		) );

			// Put jquery at the bottom
			$wp_customize->add_setting( 'jquery_bottom', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'rampup_customize_sanitize_checkbox',
			) );
			$wp_customize->add_control( 'jquery_bottom', array(
				'label'	 => __( 'Put jQuery at the bottom', 'rampup' ),
				'section' => 'rampup_jquery_optimized',
				'settings' => 'jquery_bottom',
				'type' => 'checkbox',
				'priority' => 1,
			) );

		/*------------------------------------------------------------------------------------
		/* Optimized CSS
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'rampup_css_optimized', array (
			'title' => __( 'Optimized CSS', 'rampup' ),
			'priority' => 2,
			'panel' => 'rampup_page_speed_settings',
		) );

			// Minified style.css
			$wp_customize->add_setting( 'css_minified', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'rampup_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'css_minified', array(
				'label' => __( 'Use binding style.css', 'rampup' ),
				'description' => __( 'When adding CSS to style sheet, please turn off CSS compression.After adding CSS, please turn on CSS compression.', 'rampup' ),
				'section' => 'rampup_css_optimized',
				'settings' => 'css_minified',
				'type' => 'checkbox',
				'priority' => 1,
			) );

			// Lazy Load font-awesome.css
			$wp_customize->add_setting( 'font_awesome_lazyload', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'rampup_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'font_awesome_lazyload', array(
				'label' => __( 'Lazy Load font-awesome.css', 'rampup' ),
				'section' => 'rampup_css_optimized',
				'settings' => 'font_awesome_lazyload',
				'type' => 'checkbox',
				'priority' => 2,
			) );

		/*------------------------------------------------------------------------------------
		/* Optimized HTML
		/*----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'rampup_html_optimized', array (
			'title' => __( 'Optimized HTML', 'rampup' ),
			'priority' => 3,
			'panel' => 'rampup_page_speed_settings',
		) );

			// Minified HTML
			$wp_customize->add_setting( 'html_minified', array(
				'default' => '',
				'type' => 'theme_mod',
				'sanitize_callback' => 'rampup_customize_sanitize_text',
			) );
			$wp_customize->add_control( 'html_minified', array(
				'label' => __( 'Minified HTML', 'rampup' ),
				'section' => 'rampup_html_optimized',
				'settings' => 'html_minified',
				'type' => 'checkbox',
				'priority' => 1,
			) );
