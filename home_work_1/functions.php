<?php

/**
 * home_work_ functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package home_work_
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function home_work__setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on home_work_, use a find and replace
		* to change 'home_work_' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('home_work_', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'home_work_'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'home_work__custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'home_work__setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function home_work__content_width()
{
	$GLOBALS['content_width'] = apply_filters('home_work__content_width', 640);
}
add_action('after_setup_theme', 'home_work__content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function home_work__widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'home_work_'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'home_work_'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'home_work__widgets_init');

/**
 * Enqueue scripts and styles.
 */
function home_work__scripts()
{
	wp_enqueue_style('bootstrap_css', '//cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('home_work_-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('home_work_-style', 'rtl', 'replace');

	wp_enqueue_script('home_work_-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
	wp_enqueue_script('bootstrap_js', '//cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script('core_js', get_template_directory_uri() . '/js/core.js', array('jquery'), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'home_work__scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}


/* ----------------------------- //SECTION MY FUNC ----------------------------- */
//DEBUG FUNC
function debug($data)
{
	echo '<br>';
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
	echo '<br>';
}

//NOTE API REQUEST
function parseHeaders($headers)
{
	$head = array();
	foreach ($headers as $k => $v) {
		$t = explode(':', $v, 2);
		if (isset($t[1]))
			$head[trim($t[0])] = trim($t[1]);
		else {
			$head[] = $v;
			if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
				$head['reponse_code'] = intval($out[1]);
		}
	}
	return $head;
}

function api_request()
{
	//HEADERS
	$context = stream_context_create([
		'http' => [
			'method' => 'GET',
			'header' => 'Content-type: application/json; charset=UTF-8'
		]
	]);

	//take yesterday date
	$date = new DateTime(the_date());
	$date->modify('-1 day');
	$date->format('Ymd');
	//ТОЛЬКО ДЛЯ ПРИМЕРА, ЧТО БЫ ЗАПОЛННИТЬ ФАЙЛ ВЧЕРАШНИМИ ДАННЫМИ
	$api_source_yesterday =  file_get_contents('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?date=' . $date->format('Ymd') . '&json', false, $context);
	file_put_contents('file.txt', $api_source_yesterday);
	//Get API RESPONSE
	$api_source =  file_get_contents('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json', false, $context);
	$parset_response = parseHeaders($http_response_header);
	if (200 == $parset_response["reponse_code"]) {
		return json_decode($api_source);
	} else {
		$text = file_get_contents('file.txt');
		return json_decode($text);
	}
}