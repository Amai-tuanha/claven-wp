<?php
/**
* Admin customize
* @package WordPress
* @subpackage I'LL

*/
//投稿画面の機能追加

/*------------------------------------------------------------------------------------
/* Meta description 文字数のカウント
/*----------------------------------------------------------------------------------*/
$active_meta_tage_settings = get_theme_mod( 'active_meta_tage_settings', true );

if ( $active_meta_tage_settings ):
function meta_description_counter() {
	?>
	<script type="text/javascript">
		jQuery( document ).ready(function($) {
			if( 'post' == $('#post_type').val() || 'page' == $('#post_type').val() ) {
			meta_description_count_field( "#ill_meta_description" );
			}
		});

		function meta_description_count_field( target ) {
			jQuery( target ).after( "<span class=\"meta_description_word_counter\" style='display:block; margin:0 15px 0 0;'></span>" );
			jQuery( target ).bind({
			keyup: function() {
			meta_description_set_counter();
			},
			change: function() {
			meta_description_set_counter();
		}
		});

		meta_description_set_counter();
		function meta_description_set_counter(){
			jQuery( "span.meta_description_word_counter" ).text( "<?php _e( 'word counter:', 'ill' ); ?>"+jQuery( target ).val().length );
			};
		}
	</script>
	<?php
}
add_action( 'admin_head-post.php', 'meta_description_counter' );
add_action( 'admin_head-post-new.php', 'meta_description_counter' );
endif;

/*------------------------------------------------------------------------------------
/* SEO設定
/*----------------------------------------------------------------------------------*/
function add_seo_setting() {
	add_meta_box( 'seo_setting', __( 'SEO setting', 'ill' ), 'seo_setting_form', 'page', 'normal', 'high' );
	add_meta_box( 'seo_setting', __( 'SEO setting', 'ill' ), 'seo_setting_form', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_seo_setting' );

//フォーム表示
function seo_setting_form() {
	global $post;
	$active_meta_tage_settings = get_theme_mod( 'active_meta_tage_settings', true );

	wp_nonce_field( wp_create_nonce(__FILE__), 'my_nonce' );

	if( $active_meta_tage_settings) {
	echo '<label>' . __( 'meta keywords' , 'ill' ) . '</label><br>';
	echo '<input type="text" name="ill_meta_keywords" id="ill_meta_keywords" value="' . esc_html( get_post_meta( $post->ID, 'ill_meta_keywords', true ) ) . '" size="20" style="width:50%" />';
	echo '<p>' . __( 'Set the peculiar keyword indicating this page contents.(Option)' , 'ill') . '<br>';
	echo __( 'During Meta keyword ,(separated by single-byte comma). (Example) keyword 1,keyword 2' , 'ill' ) . '</p>';

	echo '<label>' . __( 'meta description' , 'ill' ) . '</label><br>';
	echo '<textarea name="ill_meta_description" id="ill_meta_description" cols="60" rows="4" style="width:99%">' . esc_html( get_post_meta( $post->ID, 'ill_meta_description', true) ) . '</textarea>';
	echo '<p>' . __( 'Set the summarized writing indicating page contents .(Option)' , 'ill' ) . '<br />';
	echo	__( 'In the case of non-input, 120 characters are extracted from the post.' , 'ill' ) . '</p><br />';

	$noindex_value = get_post_meta( $post->ID, 'ill_noindex', true );

	if( $noindex_value	== 1 ) {
		$noindex_checked	= "checked";
		} else { $noindex_checked	 = "/";
	}

	echo '<label><input type="checkbox" name="ill_noindex" id="ill_noindex" value="1"' .	$noindex_checked . '/></label>' ;
	echo __( 'noindex' , 'ill') . '<br />';
	echo '<p>' . __( 'Discourage search engines from indexing this page' , 'ill' ) . '</p>';

	$nofollow_value = get_post_meta( $post->ID, 'ill_nofollow', true );

	if( $nofollow_value == 1 ) {
		$nofollow_checked = "checked";
		} else { $nofollow_checked = "/";
	}

	echo '<label><input type="checkbox" name="ill_nofollow" id="ill_nofollow" value="1"' . $nofollow_checked . '/></label>' ;
	echo	__( 'nofollow' , 'ill') . '<br />';
	echo '<p>' . __( 'Discourage search engines from following this page' , 'ill' ) . '</p></label>';

		} else { echo '<p>' . __( 'SEO setting function is turns off.' , 'ill' ) . '</p></label>';
	}

}

//入力内容の更新処理
function seo_setting_save($post_id) {

	$my_nonce = isset( $_POST[ 'my_nonce' ] ) ? $_POST[ 'my_nonce' ] : null;

	if( !wp_verify_nonce ( $my_nonce, wp_create_nonce (__FILE__ ) ) ) { return $post_id; }
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) { return $post_id; }
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	$meta_keywords = isset( $_POST[ 'ill_meta_keywords' ] ) ? $_POST[ 'ill_meta_keywords' ] : null;

	if( get_post_meta ( $post_id, 'ill_meta_keywords') == "" ) {
		add_post_meta ( $post_id, 'ill_meta_keywords', $meta_keywords, true );
		} elseif ( $meta_keywords != get_post_meta( $post_id, 'ill_meta_keywords', true ) ) {
		update_post_meta( $post_id, 'ill_meta_keywords', $meta_keywords );
		} elseif ( $meta_keywords == "" ) {
		delete_post_meta( $post_id, 'ill_meta_keywords', get_post_meta( $post_id, 'ill_meta_keywords', true ) );
	}

	$meta_description = isset( $_POST[ 'ill_meta_description' ] ) ? $_POST[ 'ill_meta_description' ] : null;

	if( get_post_meta ( $post_id, 'ill_meta_description' ) == "" ) {
		add_post_meta ( $post_id, 'ill_meta_description', $meta_description, true );
		} elseif ( $meta_description != get_post_meta( $post_id, 'ill_meta_description', true ) ) {
		update_post_meta( $post_id, 'ill_meta_description', $meta_description );
		} elseif ( $meta_description == "" ) {
		delete_post_meta( $post_id, 'ill_meta_description', get_post_meta( $post_id, 'ill_meta_description', true ) );
	}

	$meta_noindex = isset( $_POST[ 'ill_noindex' ] ) ? $_POST[ 'ill_noindex' ] : null;

	if( get_post_meta ( $post_id, 'ill_noindex' ) == "" ) {
		add_post_meta ( $post_id, 'ill_noindex', $meta_noindex, true );
		} elseif ( $meta_noindex != get_post_meta( $post_id, 'ill_noindex', true ) ) {
		update_post_meta( $post_id, 'ill_noindex', $meta_noindex );
		} elseif ( $meta_noindex == "" ) {
		delete_post_meta( $post_id, 'ill_noindex', get_post_meta( $post_id, 'ill_noindex', true ) );
	}

	$meta_nofollow = isset( $_POST[ 'ill_nofollow' ] ) ? $_POST[ 'ill_nofollow' ] : null;

	if( get_post_meta ( $post_id, 'ill_nofollow' ) == "" ) {
		add_post_meta ( $post_id, 'ill_nofollow', $meta_nofollow, true );
		} elseif ( $meta_nofollow != get_post_meta( $post_id, 'ill_nofollow', true ) ) {
		update_post_meta( $post_id, 'ill_nofollow', $meta_nofollow );
		} elseif ( $meta_nofollow == "" ) {
		delete_post_meta( $post_id, 'ill_nofollow', get_post_meta( $post_id, 'ill_nofollow', true ) );
	}

}
add_action( 'save_post', 'seo_setting_save' );

/*------------------------------------------------------------------------------------
/* カスタムCSS設定
/*----------------------------------------------------------------------------------*/
function add_custom_css_setting() {
	add_meta_box( 'custom_css', __( 'Custom CSS setting', 'ill' ), 'custom_css_setting_form', 'page', 'normal', 'high' );
	add_meta_box( 'custom_css', __( 'Custom CSS setting', 'ill' ), 'custom_css_setting_form', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_custom_css_setting' );

//フォーム表示
function custom_css_setting_form() {
	global $post;

	wp_nonce_field( wp_create_nonce(__FILE__), 'my_nonce' );

	echo '<textarea name="ill_custom_css_setting" id="ill_custom_css_setting" cols="60" rows="4" style="width:99%">' . esc_html( get_post_meta( $post->ID, 'ill_custom_css_setting', true) ) . '</textarea>';
	echo '<p>' . __( 'Enter CSS code of this page. Style tag is not required. Code example: .example { font-size: 20px; color: #000; }' , 'ill' ) . '</p>';

}

//入力内容の更新処理
function custom_css_setting_save($post_id) {

	$my_nonce = isset( $_POST[ 'my_nonce' ] ) ? $_POST[ 'my_nonce' ] : null;

	if( !wp_verify_nonce ( $my_nonce, wp_create_nonce (__FILE__ ) ) ) { return $post_id; }
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) { return $post_id; }
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

		$custom_css_setting = isset( $_POST[ 'ill_custom_css_setting' ] ) ? $_POST[ 'ill_custom_css_setting' ] : null;

		if( get_post_meta ( $post_id, 'ill_custom_css_setting' ) == "" ) {
			add_post_meta ( $post_id, 'ill_custom_css_setting', $custom_css_setting, true );
			} elseif ( $custom_css_setting != get_post_meta( $post_id, 'ill_custom_css_setting', true ) ) {
			update_post_meta( $post_id, 'ill_custom_css_setting', $custom_css_setting );
			} elseif ( $custom_css_setting == "" ) {
			delete_post_meta( $post_id, 'ill_custom_css_setting', get_post_meta( $post_id, 'ill_custom_css_setting', true ) );
		}
}
add_action( 'save_post', 'custom_css_setting_save' );
