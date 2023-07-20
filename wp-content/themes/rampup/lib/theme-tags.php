<?php

/**
 * Theme tags
 * @package WordPress
 * @subpackage I'LL

 */

/*------------------------------------------------------------------------------------
/* 全般設定
/*----------------------------------------------------------------------------------*/
// metaタグの表示判定
function is_rampup_active_meta_tage_settings()
{
	$active_meta_tage_settings = get_theme_mod('active_meta_tage_settings', true);
	return $active_meta_tage_settings;
}

// Keywordsの取得
function get_rampup_keywords()
{
	global $wp_query;
	$post = $wp_query->get_queried_object();
	$meta_post_keywords = post_custom('rampup_meta_keywords');
	$common_keywords = get_theme_mod('top_keywords', '');

	if (is_home() || is_front_page()) {
		$keywords = $common_keywords;
		if ($common_keywords == "") {
			$keywords = get_bloginfo('name');
		}
	} elseif (!empty($post->post_password)) {
		$keywords = __('Protected post.', 'rampup');
	} elseif (is_single()) {
		if (is_singular('post')) {
			$keywords = $meta_post_keywords;
			if ($meta_post_keywords == "") {
				$post_cat = get_the_category();
				$cat = $post_cat[0];
				$keywords = $cat->cat_name;
			}
		} else {
			$taxonomies = get_the_taxonomies();
			$taxonomy = key($taxonomies);
			if ($taxonomies) {
				$terms = get_the_terms(get_the_ID(), $taxonomy);
				$term = reset($terms);
				$keywords = $term->name;
			}
		}
	} elseif (is_page()) {
		$keywords = $meta_post_keywords;
		if ($meta_post_keywords == "") {
			$keywords = $common_keywords;
		}
		if ($meta_post_keywords == "" && $common_keywords == "") {
			$keywords = get_bloginfo('name');
		}
	} elseif (is_category()) {
		$keywords = single_cat_title('', '');
	} elseif (is_tag()) {
		$keywords = single_tag_title('', '');
	} elseif (is_tax()) {
		$keywords = single_cat_title('', '');
	} elseif (is_day()) {
		$keywords = get_the_time(__('Ymd', 'rampup'));
	} elseif (is_month()) {
		$keywords = get_the_time(__('Ym', 'rampup'));
	} elseif (is_year()) {
		$keywords = get_the_time(__('Y', 'rampup'));
	} elseif (is_search()) {
		$keywords = get_search_query();
	} elseif (is_404()) {
		$keywords = __('404 Error', 'rampup');
	} elseif (is_author()) {
		$keywords = get_the_author_meta('display_name', get_query_var('author'));
	}
	return $keywords;
}

// Keywordsの表示
function rampup_keywords()
{
	echo esc_html(strip_tags(get_rampup_keywords()));
}

// Descriptionの取得
function get_rampup_description()
{
	global $wp_query, $page, $paged;
	$post = $wp_query->get_queried_object();
	$meta_post_description = post_custom('rampup_meta_description', '');
	$top_description = get_theme_mod('top_description', '');

	if (is_front_page()) {
		if ($top_description == "") {
			$top_description = get_bloginfo('description'); // キャッチフレーズ
		} else {
			$top_description = get_theme_mod('top_description');
		}
		$resume = $top_description;
		if (is_paged()) {
			$resume = trim(get_bloginfo('name')) . " | " . __('List of post', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_home()) {
		$resume = __('List of post', 'rampup');
		if (is_paged()) {
			$resume = __('List of post', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_singular()) {
		if (!empty($post->post_password)) {
			$resume = __('There is no overview because this is a protected post.', 'rampup');
		} elseif ($meta_post_description) {
			$resume = $meta_post_description;
		} else {
			$content = $post->post_content;
			if ('' !== strpos($content, '<!--nextpage-->')) {
				$num = $page ? $page - 1 : 0;
				$split_contents = explode('<!--nextpage-->', $content);
				$content = $split_contents[$num];
			}
			$resume = mb_substr(strip_tags($content), 0, 120);
		}
	} elseif (is_category()) {
		$resume = trim(strip_tags(category_description()));
		if ($resume == "") {
			$resume = single_cat_title('', '') . " - " . __('Category of article list', 'rampup');
		}
		if (is_paged()) {
			$resume = single_cat_title('', '') . " - " . __('Category of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_tag()) {
		$resume = trim(strip_tags(tag_description()));
		if ($resume == "") {
			$resume = single_tag_title('', '') . " - " . __('Tag of article list', 'rampup');
		}
		if (is_paged()) {
			$resume = single_tag_title('', '') . " - " . __('Tag of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_tax()) {
		$resume = trim(strip_tags(tag_description()));
		if ($resume == "") {
			$resume = single_cat_title('', '') . " - " . __('Taxonomy of article list', 'rampup');
		}
		if (is_paged()) {
			$resume = single_cat_title('', '') . " - " . __('Taxonomy of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_year()) {
		$resume = get_the_time(__('Y', 'rampup')) . " - " . __('Year of article list', 'rampup');
		if (is_paged()) {
			$resume = get_the_time(__('Y', 'rampup')) . " - " . __('Year of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_month()) {
		$resume = get_the_time(__('Ym', 'rampup')) . " - " . __('Month of article list', 'rampup');
		if (is_paged()) {
			$resume = get_the_time(__('Ym', 'rampup')) . " - " . __('Month of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_date()) {
		$resume = get_the_time(__('Ymd', 'rampup')) . " - " . __('Day of article list', 'rampup');
		if (is_paged()) {
			$resume = get_the_time(__('Ymd', 'rampup')) . " - " . __('Day of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_author()) {
		$resume = get_the_author_meta('display_name', get_query_var('author')) . " - " . __('Author of article list', 'rampup');
		if (is_paged()) {
			$resume = get_the_author_meta('display_name', get_query_var('author')) . " - " . __('Author of article list', 'rampup') . " - " . sprintf(__('%s Page', 'rampup'), max($paged, $page));
		}
	} elseif (is_search()) {
		$resume = get_search_query() . " - " . __('Search Result of article list', 'rampup');
	} elseif (is_404()) {
		$resume = __('It looks like nothing was found at this location. Maybe try a search? or check URL.', 'rampup');
	}
	$resume = str_replace("\n", "", $resume);
	$resume = strip_shortcodes($resume);
	return $resume;
}

// Descriptionの表示
function rampup_description()
{
	echo esc_html(strip_tags(get_rampup_description()));
}


// Meta robotsの表示
function rampup_robots()
{
	$noindex = post_custom('rampup_noindex');
	$nofollow = post_custom('rampup_nofollow');

	if (!get_option('blog_public')) {
		return "";
	}

	if ($noindex == 1 && $nofollow == 1)
		$robots = '<meta name="robots" content="noindex, nofollow">' . "\n";
	elseif ($noindex == 1 && $nofollow == 0)
		$robots = '<meta name="robots" content="noindex">' . "\n";
	elseif ($noindex == 0 && $nofollow == 1)
		$robots = '<meta name="robots" content="nofollow">' . "\n";
	elseif ($noindex == 0 && $nofollow == 0)
		$robots = '';
	if (is_search() || is_404() || is_year() || is_month() || is_day() || is_tag() || is_attachment())
		$robots = '<meta name="robots" content="noindex, follow">' . "\n";
	echo $robots;
}

// Facebook OGPの表示
function rampup_facebook_opg()
{
	$display_facebook_opg = get_theme_mod('display_facebook_opg', '');
	if ($display_facebook_opg) {
		get_template_part('lib/modules/components/facebook-opg');
	}
}

// 現在URLの表示
function rampup_ogp_url()
{
	if (is_home() || is_front_page()) {
		$ogp_url = home_url();
	} else {
		$ogp_url = the_permalink();
	}
	echo esc_url($ogp_url);
}

// OPG titleの取得
function get_rampup_opg_title()
{
	$title = "";
	if (is_home() || is_front_page()) {
		$title =	get_bloginfo('name');
	} elseif (is_singular()) {
		$title = trim(get_the_title());
	} elseif (is_category()) {
		$title = single_cat_title('', '') . " - " . __('Category of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_tag()) {
		$title = single_tag_title('', '') . " - " . __('Tag of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_year()) {
		$title = get_the_time(__('Y', 'rampup')) . " - " . __('Year of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_month()) {
		$title = get_the_time(__('Ym', 'rampup')) . " - " . __('Month of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_date()) {
		$title = get_the_time(__('Ymd', 'rampup')) . " - " . __('Day of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_author()) {
		$title = get_the_author_meta('display_name', get_query_var('author')) . " - " . __('Author of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_search()) {
		$title = get_search_query() . " - " . __('Search Result of article list', 'rampup') . " | " . get_bloginfo('name');
	} elseif (is_404()) {
		$title = __('It looks like nothing was found at this location. Maybe try a search? or check URL.', 'rampup') . " | " . get_bloginfo('name');
	}
	return $title;
}

// OPG titleの表示
function rampup_opg_title()
{
	echo esc_html(strip_tags(get_rampup_opg_title()));
}

// Facebook OGP画像の表示
function rampup_facebook_opg_image()
{
	$default_image = get_theme_mod('facebook_opg_image', '');
	$thumbnail_image_id = get_post_thumbnail_id();
	$thumbnail_image = wp_get_attachment_image_src($thumbnail_image_id, 'full');
	if (is_singular()) {
		if (has_post_thumbnail()) {
			echo '<meta property="og:image" content="' . esc_url($thumbnail_image[0]) . '">' . "\n";
		} elseif ($default_image) {
			echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
		}
	} else { //単一記事ページページ以外の場合（アーカイブページやホームなど）
		if ($default_image) {
			echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
		}
	}
}

// Facebook Application IDの取得
function get_rampup_facebook_app_id()
{
	$facebook_app_id = get_theme_mod('facebook_app_id', '');
	return trim($facebook_app_id);
}

// JavaScript用Facebook SDKの表示
function rampup_fb_root()
{
	$facebook_app_id = esc_js(get_rampup_facebook_app_id());
	if ($facebook_app_id) {
		echo '<div id="fb-root"></div>' . "\n";
		$fb_root =	<<<fb_root
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.6&appId=$facebook_app_id";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
fb_root;
		echo $fb_root . "\n";
	}
}

// Twitterカードの表示
function rampup_twitter_card()
{
	$display_twitter_card = get_theme_mod('display_twitter_card', '');
	if ($display_twitter_card) {
		get_template_part('lib/modules/components/twitter-card');
	}
}

// Twitterカードの取得
function get_rampup_twitter_card_type()
{
	$twitter_card_type = get_theme_mod('twitter_card_type', 'summary');
	return strip_tags($twitter_card_type);
}

// TwitterIDの取得
function get_rampup_twitter_id()
{
	$twitter_id = get_theme_mod('twitter_id', '');
	return trim($twitter_id);
}

// Twitter OGP画像の表示
function rampup_twitter_opg_image()
{
	$default_image = get_theme_mod('twitter_opg_image', '');
	$thumbnail_image_id = get_post_thumbnail_id();
	$thumbnail_image = wp_get_attachment_image_src($thumbnail_image_id, 'full');
	if (is_singular()) {
		if (has_post_thumbnail()) {
			echo '<meta name="twitter:image" content="' . esc_url($thumbnail_image[0]) . '">' . "\n";
		} elseif ($default_image) {
			echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
		}
	} else {
		if ($default_image) {
			echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
		}
	}
}

// Twitter urlの取得
function get_rampup_twitter_profile_url()
{
	$twitter_profile_url = get_theme_mod('twitter_profile_url', '');
	return trim($twitter_profile_url);
}

// Facebook urlの取得
function get_rampup_facebook_profile_url()
{
	$facebook_profile_url = get_theme_mod('facebook_profile_url', '');
	return trim($facebook_profile_url);
}

// Instagram urlの取得
function get_rampup_instagram_profile_url()
{
	$instagram_profile_url = get_theme_mod('instagram_profile_url', '');
	return trim($instagram_profile_url);
}

// LINE urlの取得
function get_rampup_line_profile_url()
{
	$line_profile_url = get_theme_mod('line_profile_url', '');
	return trim($line_profile_url);
}

// YouTube urlの取得
function get_rampup_youtube_profile_url()
{
	$youtube_profile_url = get_theme_mod('youtube_profile_url', '');
	return trim($youtube_profile_url);
}

// <body>直後に挿入
function rampup_header_js()
{
	$insert_head = get_theme_mod('insert_head', '');
	if ($insert_head) {
		echo $insert_head . "\n";
	}
}
add_action('wp_head', 'rampup_header_js', 9999);

// <body>の直後に挿入
function rampup_body_js()
{
	$insert_body_after = get_theme_mod('insert_body_after', '');
	if ($insert_body_after) {
		echo $insert_body_after . "\n";
	}
}

// <footer>直前に挿入
function rampup_footer_js()
{
	$insert_footercod = get_theme_mod('insert_footercod', '');
	if ($insert_footercod) {
		echo $insert_footercod . "\n";
	}
}
add_action('wp_footer', 'rampup_footer_js', 9999);

/*------------------------------------------------------------------------------------
/* JSON-LDを用いたschema.orgの記述
/*----------------------------------------------------------------------------------*/
add_action('wp_head', 'insert_json_ld');
function insert_json_ld()
{
	if (is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
				$context = 'http://schema.org';
				$type = 'Article';
				$headline = get_the_title();
				$dataPublished = get_the_date('Y-n-j');
				$dateModified = get_the_modified_date('Y-n-j');
				$mainEntityOfPage = get_permalink();
				$image_type = 'ImageObject';
				$image_id = get_post_thumbnail_id();
				$image = wp_get_attachment_image_src($image_id, false);
				if ($image) {
					$image_url = $image[0];
					$image_width = $image[1];
					$image_height = $image[2];
				} else {
					$no_image_large = get_template_directory_uri() . '/lib/images/no-img/middle-no-img.png';
					$image_url = $no_image_large;
					$image_width = '1026';
					$image_height = '300';
				}
				$author_type = 'Person';
				$author_name = get_the_author();
				$publisher_type = 'Organization';
				$publisher_name = get_bloginfo('name');
				$display_header_logo = esc_url(get_theme_mod('display_header_logo', ''));
				if ($display_header_logo) {
					$logo_url = $display_header_logo;
				} else {
					$logo_url = get_template_directory_uri() . "/lib/images/no-img/rampup-logo.png";
				}
				$logo_width = 245;
				$logo_height = 50;

				$json = "
				\"@context\" : \"{$context}\",
				\"@type\" : \"{$type}\",
				\"headline\" : \"{$headline}\",
				\"datePublished\" : \"{$dataPublished}\",
				\"dateModified\" : \"{$dateModified}\",
				\"mainEntityOfPage\" : \"{$mainEntityOfPage}\",
				\"author\" : {
						 \"@type\" : \"{$author_type}\",
						 \"name\" : \"{$author_name}\"
						 },
				\"image\" : {
						 \"@type\" : \"{$image_type}\",
						 \"url\" : \"{$image_url}\",
						 \"width\" : \"{$image_width}\",
						 \"height\" : \"{$image_height}\"
						 },
				\"publisher\" : {
						 \"@type\" : \"{$publisher_type}\",
						 \"name\" : \"{$publisher_name}\",
						 \"logo\" : {
									\"@type\" : \"{$image_type}\",
									\"url\" : \"{$logo_url}\",
									\"width\" : \"{$logo_width}\",
									\"height\" : \"{$logo_height}\"
									}
						 }
				";
				echo '<script type="application/ld+json">{' . $json . '}</script>' . "\n";
			endwhile;
		endif;
	}
}

// 検索キーワードリストの表示
function rampup_search_keywords_lists()
{
	$display_search_keywords_lists_pc = get_theme_mod('display_search_keywords_lists_pc', '');
	$display_search_keywords_lists_mb = get_theme_mod('display_search_keywords_lists_mb', '');
	if ($display_search_keywords_lists_pc && !wp_is_mobile() || $display_search_keywords_lists_mb && wp_is_mobile()) {
		get_template_part('lib/modules/components/search-keywords-lists');
	}
}

/*------------------------------------------------------------------------------------
/* サブタイトルの表示
/*----------------------------------------------------------------------------------*/
function rampup_subtitle()
{
	$subtitle = post_custom('rampup_subtitle');
	if ($subtitle) {
		echo '<span class="entry-subtitle">' . esc_html($subtitle) . '</span>' . "\n";
	}
}

/*------------------------------------------------------------------------------------
/* パンくずリストの表示（コンテンツ設定）
/*----------------------------------------------------------------------------------*/
function get_rampup_breadcrumb()
{

	global $wp_query;

	$page_for_posts = get_option('page_for_posts'); // フロントページの表示>投稿ページ
	$microdata_li   = ' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"';
	$microdata_a    = ' itemprop="item"';
	$microdata_span = ' itemprop="name"';
	$postType       = get_post_type();
	$post_type_obj  = get_post_type_object($postType);

	$breadcrumb_home_name_type = get_theme_mod('breadcrumb_home_name_type', 'site_title');
	$breadcrumb_home_name = get_theme_mod('breadcrumb_home_name', __('Home', 'rampup'));

	if ($breadcrumb_home_name) {
		$name = get_theme_mod('breadcrumb_home_name', __('Home', 'rampup'));
	} else {
		$name = '<span class="display-none">Home</span>';
	}

	if ($breadcrumb_home_name_type == 'home') {
		$bread_home = $name;
	} else {
		$bread_home = get_bloginfo('name');
	}

	if ($post_type_obj) {
		$post_type_name = esc_html($post_type_obj->labels->name);
	}

	$bread_crumb_html = '<!--breadcrumb-->
	<div class="content-inner">
	<nav id="breadcrumb" class="rcrumbs clearfix">
	<ol itemscope itemtype="http://schema.org/BreadcrumbList">';

	$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . home_url('/') . '"><i class="fa fa-home"></i><span' . $microdata_span . '>' . $bread_home . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="1" /></li>';

	if ($page_for_posts && is_singular('post') || $page_for_posts && is_category() || $page_for_posts && is_tag() || $page_for_posts && is_date() || $page_for_posts && is_author()) {
		$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_permalink($page_for_posts) . '"><span' . $microdata_span . '>' . get_the_title($page_for_posts) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="2" /></li>';
	} elseif (is_single() && !is_singular(array('post', 'page', 'attachment')) || is_tax()) {
		$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . home_url() . '/' . $postType . '"><span' . $microdata_span . '>' . $post_type_name . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="2" /></li>';
	} elseif (is_post_type_archive()) {
		$bread_crumb_html .= '<li' . $microdata_li . '><span' . $microdata_span . '>' . $post_type_name . '</span></li>';
	}

	if (is_home()) {

		if ($page_for_posts) {
			$bread_crumb_html .= '<li' . $microdata_li . '><span' . $microdata_span . '>' . get_the_title($page_for_posts) . '</span><meta itemprop="position" content="2" /></li>';
		}
	} elseif (is_single()) {

		if (is_singular('post')) {
			$category      = get_the_category();
			$ancestors_cta = array_reverse(get_ancestors($category[0]->term_id, 'category', 'taxonomy'));
			$C = 0;
			array_push($ancestors_cta, $category[0]->term_id);
			foreach ($ancestors_cta as $cta_parent_term_id) {
				$C++;
				$cta_parent_obj    = get_term($cta_parent_term_id, 'category');
				$term_url          = get_term_link($cta_parent_obj->term_id, $cta_parent_obj->taxonomy);
				$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . $term_url . '"><span' . $microdata_span . '>' . esc_html($cta_parent_obj->name) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr(($page_for_posts == true) ? $C + 2 : $C + 1) . '" /></li>';
			}
		} else {
			$taxonomies = get_the_taxonomies();
			$taxonomy   = key($taxonomies);
			if ($taxonomies) :
				$terms = get_the_terms(get_the_ID(), $taxonomy);
				$term  = reset($terms);
				$C = 0;
				if ($term->parent != 0) {
					$ancestors = array_reverse(get_ancestors($term->term_id, $taxonomy));
					foreach ($ancestors as $ancestor) :
						$C++;
						$pan_term          = get_term($ancestor, $taxonomy);
						$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_term_link($ancestor, $taxonomy) . '"><span' . $microdata_span . '>' . esc_html($pan_term->name) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr($C + 2) . '" /></li>';
					endforeach;
				}
				$term_url          = get_term_link($term->term_id, $taxonomy);
				$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . $term_url . '"><span' . $microdata_span . '>' . esc_html($term->name) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr($C + 3) . '" /></li>';
			endif;
		}

		$bread_crumb_html .= '<li><span>' . get_the_title() . '</span></li>';
	} elseif (is_page()) {

		$post = $wp_query->get_queried_object();
		if ($post->post_parent == 0) {
			$bread_crumb_html .= '<li><span>' . get_the_title() . '</span></li>';
		} else {
			$ancestors = array_reverse(get_post_ancestors($post->ID));
			array_push($ancestors, $post->ID);
			foreach ($ancestors as $ancestor) {
				if ($ancestor != end($ancestors)) {
					$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_permalink($ancestor) . '"><span' . $microdata_span . '>' . strip_tags(apply_filters('single_post_title', get_the_title($ancestor))) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="2" /></li>';
				} else {
					$bread_crumb_html .= '<li><span>' . strip_tags(apply_filters('single_post_title', get_the_title($ancestor))) . '</span></li>';
				}
			}
		}
	} elseif (is_category()) {

		$cat = get_queried_object();

		if ($cat->parent != 0) :
			$C = 0;
			$ancestors_term = array_reverse(get_ancestors($cat->cat_ID, 'category'));
			foreach ($ancestors_term as $ancestor) :
				$C++;
				$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_category_link($ancestor) . '"><span' . $microdata_span . '>' . esc_html(get_cat_name($ancestor)) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr(($page_for_posts == true) ?  $C + 2 : $C + 1) . '" /></li>';
			endforeach;
		endif;

		$bread_crumb_html .= '<li><span>' . $cat->cat_name . '</span></li>';
	} elseif (is_tag()) {

		$bread_crumb_html .= '<li><span>' . single_tag_title('', false) . '</span></li>';
	} elseif (is_tax()) {
		$term        = $wp_query->queried_object->term_id;
		$term_parent = $wp_query->queried_object->parent;
		$taxonomy    = $wp_query->queried_object->taxonomy;
		if ($term_parent != 0) :
			$C = 0;
			$ancestors_term = array_reverse(get_ancestors($term, $taxonomy));
			foreach ($ancestors_term as $ancestor) :
				$C++;
				$pan_term = get_term($ancestor, $taxonomy);
				$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_term_link($ancestor, $taxonomy) . '"><span' . $microdata_span . '>' . esc_html($pan_term->name) . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr($C + 2) . '" /></li>';
			endforeach;
		endif;

		$bread_crumb_html .= '<li><span>' . esc_html(single_cat_title('', '', false)) . '</span></li>';
	} elseif (is_date()) {

		if (is_day()) {

			$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_year_link(get_the_time('Y')) . '"><span' . $microdata_span . '>' . get_the_time('Y') . __('Year', 'rampup') . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr(($page_for_posts == true) ? '3' : '2') . '" /></li>';
			$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '"><span' . $microdata_span . '>' . get_the_time('m') . __('Month', 'rampup') . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr(($page_for_posts == true) ? '4' : '3') . '" /></li>';
			$bread_crumb_html .= '<li><span>' . get_the_time('d') . __('Day', 'rampup') . '</span></li>';
		} elseif (is_month()) {

			$bread_crumb_html .= '<li' . $microdata_li . '><a' . $microdata_a . ' href="' . get_year_link(get_the_time('Y')) . '"><span' . $microdata_span . '>' . get_the_time('Y') . __('Year', 'rampup') . '</span></a><i class="fa fa-angle-right"></i><meta itemprop="position" content="' . esc_attr(($page_for_posts == true) ? '3' : '2') . '" /></li>';
			$bread_crumb_html .= '<li><span>' . get_the_time('m') . __('Month', 'rampup') . '</span></li>';
		} else {

			$bread_crumb_html .= '<li><span>' . get_the_time('Y') . __('Year', 'rampup') . '</span></li>';
		}
	} elseif (is_author()) {

		$bread_crumb_html .= '<li><span>' . get_the_author_meta('display_name') . '</span></li>';
	} elseif (is_404()) {
		$bread_crumb_html .= '<li><span>' . __('File not found', 'rampup') . '</span></li>';
	} elseif (is_search()) {
		$bread_crumb_html .= '<li><span>' . __('Search results', 'rampup') . '</span></li>';
	} elseif (is_attachment()) {
		$bread_crumb_html .= '<li><span>' . get_the_title() . '</span></li>';
	}

	$bread_crumb_html .= '</ol>
</nav>
</div>
<!--end breadcrumb-->';
	return $bread_crumb_html;
}

// 投稿ページに表示判定
function rampup_single_breadcrumb()
{
	$display_single_breadcrumb = get_theme_mod('display_single_breadcrumb', true);
	if ($display_single_breadcrumb) {
		echo get_rampup_breadcrumb();
	}
}

// アーカイブページ等に表示判定
function rampup_archive_breadcrumb()
{
	$display_archive_breadcrumb = get_theme_mod('display_archive_breadcrumb', true);
	if ($display_archive_breadcrumb && !is_front_page()) {
		echo get_rampup_breadcrumb();
	}
}

// 固定ページに表示判定
function rampup_page_breadcrumb()
{
	$display_page_breadcrumb = get_theme_mod('display_page_breadcrumb', true);
	if ($display_page_breadcrumb && !is_front_page()) {
		echo get_rampup_breadcrumb();
	}
}

/*------------------------------------------------------------------------------------
/* アイキャッチ画像 キャプションの表示（コンテンツ設定）
/*----------------------------------------------------------------------------------*/
function rampup_post_thumbnail_caption()
{
	$display_post_thumbnail_caption = get_theme_mod('display_post_thumbnail_caption', '');
	$caption = get_post(get_post_thumbnail_id())->post_excerpt;
	if ($display_post_thumbnail_caption && $caption) {
		echo '<div class="article-thumbnail-caption">' . $caption . '</div>';
	}
}

/*------------------------------------------------------------------------------------
/* 投稿タグ（コンテンツ設定）
/*----------------------------------------------------------------------------------*/
// 投稿日の表示判定
function is_rampup_display_entry_date()
{
	$display_entry_date = get_theme_mod('display_entry_date', true);
	return $display_entry_date;
}

// 更新日の表示判定
function is_rampup_display_update_date()
{
	$display_update_date = get_theme_mod('display_update_date', '');
	return $display_update_date;
}

// カテゴリーの表示判定
function is_rampup_display_category()
{
	$display_category = get_theme_mod('display_category', '');
	return $display_category;
}

// タグの表示判定
function is_rampup_display_tag()
{
	$display_tag = get_theme_mod('display_tag', '');
	return $display_tag;
}

// コメント数の表示判定
function is_rampup_display_comments_number()
{
	$display_comments_number = get_theme_mod('display_comments_number', '');
	return $display_comments_number;
}

// 投稿者名の表示判定
function is_rampup_display_author()
{
	$display_author = get_theme_mod('display_author', true);
	return $display_author;
}

// 投稿タグの表示
function rampup_entry_meta()
{
	global $post;

	echo '<ul class="post-meta clearfix">' . "\n";
	if (is_rampup_display_entry_date() && is_rampup_display_update_date()) {
		echo '<li><i class="fa fa-clock-o"></i><time class="date published" datetime="' . esc_attr(get_the_time('Y-m-d')) . '">' . esc_html(get_the_date()) . '</time></li>' . "\n";
	} elseif (is_rampup_display_entry_date() && !is_rampup_display_update_date()) {
		echo '<li><i class="fa fa-clock-o"></i><time class="date published updated" datetime="' . esc_attr(get_the_time('Y-m-d')) . '">' . esc_html(get_the_date()) . '</time></li>' . "\n";
	}
	if (is_rampup_display_update_date() && get_the_time('Y-m-d') != get_the_modified_date('Y-m-d')) {
		echo '<li><i class="fa fa-history"></i><time class="date updated" datetime="' . esc_attr(get_the_modified_date('Y-m-d')) . '">' . esc_html(get_the_modified_date()) . '</time></li>' . "\n";
	} elseif (!is_rampup_display_entry_date() && is_rampup_display_update_date()) {
		echo '<li><i class="fa fa-clock-o"></i><time class="date published updated" datetime="' . esc_attr(get_the_time('Y-m-d')) . '">' . esc_html(get_the_date()) . '</time></li>' . "\n";
	}

	$categories = array();
	if ($_categories = get_the_category()) {
		foreach ($_categories as $_category) {
			$categories[] = sprintf(
				'<a href="%s">%s</a>',
				get_category_link($_category->cat_ID),
				esc_html($_category->cat_name)
			);
		}
		if (is_rampup_display_category()) {
			echo '<li><i class="fa fa-clone"></i>' . implode(', ', $categories) . '</li>' . "\n";
		}
	}

	if ($tags_list = get_the_tag_list('', ', ')) {
		if (is_rampup_display_tag()) {
			echo '<li><i class="fa fa-tag"></i>' . $tags_list . '</li>' . "\n";
		}
	}

	if (is_rampup_display_comments_number() && !post_password_required() && get_comments_number()) {
		echo '<li>' . "\n";
		comments_popup_link(printf('<i class="fa fa-comments-o"></i><span class="screen-reader-text">%s</span>', get_the_title()));
		echo '</li>' . "\n";
	}

	if (is_rampup_display_author()) {
		echo '<li><i class="fa fa-user"></i><span class="vcard author"><span class="fn"><a href="' . esc_url(get_author_posts_url($post->post_author)) . '">' . esc_attr(get_the_author()) . '</a></span></span></li>' . "\n";
	} else {
		echo '<li class="display-none"><i class="fa fa-user"></i><span class="vcard author"><span class="fn"><a href="' . esc_url(get_author_posts_url($post->post_author)) . '">' . esc_attr(get_the_author()) . '</a></span></span></li>' . "\n";
	}
	echo '</ul >' . "\n";
}

/*------------------------------------------------------------------------------------
/* 関連記事（コンテンツ設定）
/*----------------------------------------------------------------------------------*/
// 前後記事の表示
function rampup_display_pre_nex()
{
	$display_pre_nex = get_theme_mod('display_pre_nex', true);
	if ($display_pre_nex) {
		get_template_part('lib/modules/components/post-navigation');
	}
}

// 前後記事のアイキャッチ表示判定
function is_rampup_display_pre_nex_thumbnail_img()
{
	$display_pre_nex_thumbnail_img = get_theme_mod('display_pre_nex_thumbnail_img', true);
	return $display_pre_nex_thumbnail_img;
}

if (is_rampup_display_pre_nex_thumbnail_img()) :
	function rampup_add_img_pre_nex($output, $format, $link, $post, $adjacent)
	{
		if (has_post_thumbnail($post)) {
			$thumbnail_img = get_the_post_thumbnail($post, 'square-thumbnail');
		} else {
			$thumbnail_img = '<img src="' . get_template_directory_uri() . "/lib/images/no-img/square-no-img.png" . '" alt="no image" />';
		}
		$output = str_replace('<span class="nav-title clearfix">', '<span class="nav-title clearfix">' . $thumbnail_img, $output);
		return $output;
	}

	add_filter('next_post_link', 'rampup_add_img_pre_nex', 10, 5);
	add_filter('previous_post_link', 'rampup_add_img_pre_nex', 10, 5);
endif;

// 関連記事の表示
function rampup_related_post()
{
	$display_related_post = get_theme_mod('display_related_post', true);
	if ($display_related_post) {
		get_template_part('lib/modules/components/related-post');
	}
}

/*------------------------------------------------------------------------------------
/* 保護ページのタイトルから「保護中：」を削除
/*----------------------------------------------------------------------------------*/
add_filter('protected_title_format', 'edit_rampup_protected_title');
function edit_rampup_protected_title()
{

	$title = get_theme_mod('protected_title', __('Protected:', 'rampup'));

	if (!get_theme_mod('delete_protected_title')) {
		return '' . esc_html($title) . ' %s';
	} else {
		return '%s';
	}
}

add_filter('the_password_form', 'rampup_password_form');
function rampup_password_form($post = 0)
{
	$post = get_post($post);
	$label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
	$message = get_theme_mod('protected_message', __('This content is password protected. To view it please enter your password below:', 'rampup'));
	$output = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" class="post-password-form" method="post">
	<p>' . nl2br(wp_kses_post($message)) . '</p>
	<div class="text-center"><label for="' . $label . '">' . __('Password:') . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label><input type="submit" name="Submit" value="' . esc_attr_x('Enter', 'post password form') . '" /></div></form>
	';
	return $output;
}

/*------------------------------------------------------------------------------------
/* 検索結果の範囲
/*----------------------------------------------------------------------------------*/
// 検査結果を投稿記事のみにする
function rampup_search_filter($query)
{
	$search_results_post = get_theme_mod('search_results_post', true);
	if ($search_results_post && !is_admin() && $query->is_main_query() && $query->is_search()) {
		$query->set('post_type', 'post');
	}
}
add_action('pre_get_posts', 'rampup_search_filter');

/*------------------------------------------------------------------------------------
/* Facebook Messenger設定
/*----------------------------------------------------------------------------------*/
function rampup_facebook_messenger()
{
	$fm_frontpage = get_theme_mod('display_facebook_messenger_frontpage', '');
	$fm_single = get_theme_mod('display_facebook_messenger_single', '');
	$fm_page = get_theme_mod('display_facebook_messenger_page', '');
	$fm_archive = get_theme_mod('display_facebook_messenger_archive', '');
	$fm_lp = get_theme_mod('display_facebook_messenger_lp', '');
	$fm_mobile = get_theme_mod('display_facebook_messenger_mobile', '');
	$fm_code = get_theme_mod('insert_facebook_messenger_code', '');

	if ($fm_mobile && wp_is_mobile()) {
		if (is_front_page() && $fm_frontpage && $fm_code || is_home() && $fm_frontpage && $fm_code || is_single() && $fm_single && $fm_code || is_page() && !is_page_template('templates/lp.php') && $fm_page && $fm_code || is_archive() && $fm_archive && $fm_code || is_page_template('templates/lp.php') && $fm_lp  && $fm_code) {
			echo $fm_code . "\n";
		}
	} elseif (!wp_is_mobile()) {
		if (is_front_page() && $fm_frontpage && $fm_code || is_home() && $fm_frontpage && $fm_code || is_single() && $fm_single && $fm_code || is_page() && !is_page_template('templates/lp.php') && $fm_page && $fm_code || is_archive() && $fm_archive && $fm_code || is_page_template('templates/lp.php') && $fm_lp  && $fm_code) {
			echo $fm_code . "\n";
		}
	}
}

/*------------------------------------------------------------------------------------
/* Archiveページの説明文表示
/*----------------------------------------------------------------------------------*/
function get_rampup_archive_description()
{
	if (is_category()) {
		$description = trim(category_description());
		if ($description == "") {
			$description = __('Category of article list', 'rampup');
		}
	} elseif (is_tag()) {
		$description = trim(tag_description());
		if ($description == "") {
			$description = __('Tag of article list', 'rampup');
		}
	} elseif (is_archive()) {
		$description = __('Article list', 'rampup');
	} elseif (is_author()) {
		$description = __('Author of article list', 'rampup');
	}
	return $description;
}

function rampup_archive_description()
{
	echo get_rampup_archive_description();
}

remove_filter('pre_term_description', 'wp_filter_kses');

/*------------------------------------------------------------------------------------
/* iframeのレスポンシブ対応
/*----------------------------------------------------------------------------------*/
function rampup_iframe_in_div($the_content)
{
	if (is_singular()) {
		//YouTube動画
		$the_content = preg_replace('/<iframe[^>]+?youtube\.com[^<]+?<\/iframe>/is', '<div class="responsive-wrap">${0}</div>', $the_content);
		//GoogleMap
		$the_content = preg_replace('/<iframe[^>]+?google\.com\/maps\/embed[^<]+?<\/iframe>/is', '<div class="responsive-wrap">${0}</div>', $the_content);
		//slideshare
		$the_content = preg_replace('/<iframe[^>]+?slideshare\.net[^<]+?<\/iframe>/is', '<div class="responsive-wrap">${0}</div>', $the_content);
		//vimeo
		$the_content = preg_replace('/<iframe[^>]+?player\.vimeo\.com[^<]+?<\/iframe>/is', '<div class="responsive-wrap">${0}</div>', $the_content);
	}
	return $the_content;
}
add_filter('the_content', 'rampup_iframe_in_div');

/*------------------------------------------------------------------------------------
/* ページスピード設定
/*----------------------------------------------------------------------------------*/
// HTMLの圧縮
function rampup_html_compress_start()
{
	$html_minified = get_theme_mod('html_minified', '');
	if ($html_minified) {
		$ob_start = ob_start();
		return $ob_start;
	}
}

function rampup_html_compress_end()
{
	$html_minified = get_theme_mod('html_minified', '');
	if ($html_minified) {
		$html_compress = ob_get_clean();
		$html_compress = str_replace("\t", '', $html_compress);
		$html_compress = str_replace("\r", '', $html_compress);
		$html_compress = str_replace("\n", '', $html_compress);
		$html_compress = preg_replace('/<!--[\s\S]*?-->/', '', $html_compress);
		echo $html_compress;
	}
}

// jQueryのフッター移動
function rampup_enqueue_script()
{
	$jquery_bottom = get_theme_mod('jquery_bottom', '');
	if ($jquery_bottom) {
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), '', true); //head内でjQueryが必要な場合は無効
	} else {
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), '', false);
	}
}

// font-awesome.css 読み込み遅延の判定
function is_replace_font_awesom_tag()
{
	$font_awesome_lazyload = get_theme_mod('font_awesome_lazyload', '');
	return $font_awesome_lazyload;
}

// font-awesome.css 読み込み遅延
if (is_replace_font_awesom_tag()) {
	function replace_font_awesome_tag($tag, $handle)
	{
		if ('font-awesome' !== $handle) {
			return $tag;
		}
		return str_replace('stylesheet', 'subresource', $tag);
	}

	add_filter('style_loader_tag', 'replace_font_awesome_tag', 10, 2);
}

/*------------------------------------------------------------------------------------
/* CSS統合/圧縮
/*----------------------------------------------------------------------------------*/
define('TEMPLA', get_template_directory());
define('STYLE', get_stylesheet_directory());
require_once(ABSPATH . 'wp-admin/includes/file.php'); // WP_Filesystemの使用

// 圧縮版style.cssの読み込み切り替え
if (!function_exists('rampup_enqueue_style')) :
	function rampup_enqueue_style()
	{
		$css_minified = get_theme_mod('css_minified', '');
		$stop_animation = get_theme_mod('stop_animation', '');
		if ($css_minified) {
			wp_enqueue_style('rampup-style-min', get_template_directory_uri() . '/style-min.css');
		} else {
			wp_enqueue_style('rampup-style', get_stylesheet_uri());
			if (!$stop_animation) {
				wp_enqueue_style('animate', get_template_directory_uri() . '/lib/css/animate.min.css');
			}
		}
	}
endif;

if (!function_exists('rampup_css_compression')) :
	function rampup_css_compression()
	{
		$stop_animation = get_theme_mod('stop_animation', '');
		$parent_css = TEMPLA . '/style.css';
		$animate_css = TEMPLA . '/lib/css/animate.min.css';

		$css = '';

		if (WP_Filesystem()) { // WP_Filesystemの初期化
			global $wp_filesystem; // $wp_filesystemオブジェクトの呼び出し
			if (is_file($parent_css)) $css .= $wp_filesystem->get_contents($parent_css);
			if (is_file($animate_css) && !$stop_animation) $css .= $wp_filesystem->get_contents($animate_css);
		}

		// CSS 圧縮
		if (class_exists('CSSmin')) {
			$minify = new CSSmin();
			if (method_exists($minify, "run")) {
				$css = trim($minify->run($css));
			}

			// 圧縮後のCSSファイル保存
			$style_min = TEMPLA . '/style-min.css';

			if (WP_Filesystem()) { // WP_Filesystemの初期化
				global $wp_filesystem; // $wp_filesystemオブジェクトの呼び出し
				// $wp_filesystemオブジェクトのメソッドとしてファイルに書き込む
				$wp_filesystem->put_contents($style_min, $css);
			}

			return;
		}
	}
	add_action('customize_save_after', 'rampup_css_compression', 10, 1);
endif;
