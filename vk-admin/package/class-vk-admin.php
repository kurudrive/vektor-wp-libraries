<?php

/*
このファイルの元ファイルは
https://github.com/vektor-inc/vektor-wp-libraries
にあります。
修正の際は上記リポジトリのデータを修正してください。
編集権限を持っていない方で何か修正要望などありましたら
各プラグインのリポジトリにプルリクエストで結構です。
*/

if ( ! class_exists( 'Vk_Admin' ) )
{

// ダッシュボード表示用のメタボックス読み込み
// require_once( 'class-vk-admin-info.php' );

class Vk_Admin {

	public static $version = '1.2.1';

	static function init(){
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_common_css' ) );
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'dashboard_widget'), 1 );
	}

	static function admin_directory_url (){
		$vk_admin_url = plugin_dir_url( __FILE__ );
		return $vk_admin_url;
	}

	static function admin_common_css (){
		wp_enqueue_style( 'vk-admin-style', self::admin_directory_url().'css/vk_admin.css', array(), self::$version, 'all' );
	}

	static function admin_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script( 'vk-admin-js', self::admin_directory_url().'js/vk_admin.js', array( 'jquery' ), self::$version );
	}

	// 管理画面用のjsを読み込むページを配列で指定する
	// $admin_pages は vk-admin-config.php に記載
	static function admin_scripts( $admin_pages ){
		foreach ($admin_pages as $key => $value) {
			$hook = 'admin_print_styles-'.$value;
			add_action( $hook, array( __CLASS__, 'admin_enqueue_scripts' ) );
		}
	}


	/*--------------------------------------------------*/
	/*  admin_banner
	/*--------------------------------------------------*/
	/*  get_news_body_rss
	/*--------------------------------------------------*/
	/*  admin _ Dashboard Widget
	/*--------------------------------------------------*/
	/*  admin _ sub
	/*--------------------------------------------------*/
	/*  admin _ page_frame
	/*--------------------------------------------------*/

	/*--------------------------------------------------*/
	/*  admin_banner
	/*--------------------------------------------------*/
	public static function admin_banner() {
		$banner = '';
		$dir_url = self::admin_directory_url();
		$lang = ( get_locale() == 'ja' ) ? 'ja' : 'en' ;

		$banner .= '<div class="vk-admin-banner">';
		$banner .= '<div class="vk-admin-banner-grid">';

		// プラグイン VK Aost Author Display を有効化していない人にバナーを表示
		if ( !is_plugin_active('vk-post-author-display/post-author-display.php') ){
			$banner .= '<a href="//wordpress.org/plugins/vk-post-author-display/" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/post_author_display_bnr_'.$lang .'.jpg" alt="VK Post Author
			Display" /></a>';
		}

		// 現在のテーマを取得
		$theme = wp_get_theme()->get('Template');

		// Lightningを使用していない人にLightningのバナーを表示
		if ( $theme != 'lightning' ) {
			if ( $lang == 'ja' ) {
				$banner .= '<a href="//lightning.nagoya/ja/" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/lightning_bnr_ja.jpg" alt="lightning_bnr_ja" /></a>';
			} else {
				$banner .= '<a href="//lightning.nagoya/" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/lightning_bnr_en.jpg" alt="lightning_bnr_en" /></a>';
			}
		} // if ( $theme != 'lightning' ) {

		if ( $lang == 'ja' && $theme != 'bill-vektor' ) {
				$banner .= '<a href="//billvektor.com" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/billvektor_banner.png" alt="見積書・請求書管理用WordPressテーマ" /></a>';
		}

		if ( $lang == 'ja' && !is_plugin_active('lightning-skin-jpnstyle/lightning_skin_jpnstyle.php') ){
				$banner .= '<a href="//lightning.nagoya/ja/plugins/ex_plugin/lightning-jpnstyle" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/jpnstyle-bnr.jpg" alt="" /></a>';
		}

		if ( $lang == 'ja' && !is_plugin_active('vk-all-in-one-expansion-unit/vkExUnit.php') ){
				$banner .= '<a href="https://ex-unit.nagoya/ja/" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/ExUnit_bnr.png" alt="" /></a>';
		}

		$banner .= '</div>';

		$banner .= '<a href="//www.vektor-inc.co.jp" class="vektor_logo" target="_blank" class="admin_banner"><img src="'.$dir_url.'images/vektor_logo.png" alt="Vektor,Inc." /></a>';

		$banner .= '</div>';

		echo apply_filters( 'vk_admin_banner_html' , $banner );
	}

	/*--------------------------------------------------*/
	/*  get_news_body_api
	/*--------------------------------------------------*/

	public static function news_from_rest_api()
	{

		$html = '<h3 class="vk-metabox-sub-title">';
		$html .= 'Vektor WordPress Information';
		$html .= '<a href="https://www.vektor-inc.co.jp/info-cat/vk-wp-info/" target="_blank" class="vk-metabox-more-link">記事一覧<span aria-hidden="true" class="dashicons dashicons-external"></span></a>';
		$html .= '</h3>';
		$html .= '<ul id="vk-wp-info" class="vk-metabox-post-list"></ul>';

		$html .= '<h3 class="vk-metabox-sub-title">';
		$html .= 'Vektor WordPress Blog';
		$html .= '<a href="https://www.vektor-inc.co.jp/category/wordpress-info/" target="_blank" class="vk-metabox-more-link">記事一覧<span aria-hidden="true" class="dashicons dashicons-external"></span></a>';
		$html .= '</h3>';
		$html .= '<ul id="vk-wp-blog" class="vk-metabox-post-list"></ul>';

		$html .= '<h3 class="vk-metabox-sub-title">';
		$html .= __( 'Vektor WordPress フォーラム' );
		$html .= '<a href="http://forum.bizvektor.com/" target="_blank" class="vk-metabox-more-link">記事一覧<span aria-hidden="true" class="dashicons dashicons-external"></span></a>';
		$html .= '</h3>';
		$html .= '<ul id="vk-wp-forum" class="vk-metabox-post-list"></ul>';

		$html = apply_filters( 'vk_admin_news_html' , $html );
		echo $html;
		?>

		<script>
		/*-------------------------------------------*/
		/* REST API でお知らせを取得
		/*-------------------------------------------*/
		;(function($){
		jQuery(function() {

				$.getJSON( "https://vektor-inc.co.jp/wp-json/wp/v2/info?info-cat=111&per_page=2",
				function(results) {
						// 取得したJSONの内容をループする
						$.each(results, function(i, item) {
							// 日付のデータを取得
							var date = new Date(item.date_gmt);
							var formate_date = date.toLocaleDateString();
							// JSONの内容の要素を</ul>の前に出力する
							$("ul#vk-wp-info").append('<li><span class="date">'+ formate_date +'</span><a href="' + item.link + '" target="_blank">' + item.title.rendered + '</a></li>');
						});
				});

				$.getJSON( "https://www.vektor-inc.co.jp/wp-json/wp/v2/posts/?categories=55&per_page=3",
				function(results) {
						// 取得したJSONの内容をループする
						$.each(results, function(i, item) {
							// 日付のデータを取得
							var date = new Date(item.date_gmt);
							var formate_date = date.toLocaleDateString();
							// JSONの内容の要素を</ul>の前に出力する
							$("ul#vk-wp-blog").append('<li><span class="date">'+ formate_date +'</span><a href="' + item.link + '" target="_blank">' + item.title.rendered + '</a></li>');
						});
				});

				$.getJSON( "http://forum.bizvektor.com/wp-json/wp/v2/topics/?per_page=5",
				function(results) {
						$.each(results, function(i, item) {
							var date = new Date(item.date_gmt);
							var formate_date = date.toLocaleDateString();
							 $("ul#vk-wp-forum").append('<li><a href="' + item.link + '" target="_blank">' + item.title.rendered + '</a></li>');
						});
				});

		});
		})(jQuery);
		</script>
		<?php
	}

	/*--------------------------------------------------*/
	/*  get_news_body_rss
	/*	RSS方針で現在は不使用
	/*--------------------------------------------------*/
	public static function get_news_body_rss() {

		$output = '';

		include_once( ABSPATH . WPINC . '/feed.php' );

		if ( 'ja' == get_locale() ) {
			$exUnit_feed_url = apply_filters( 'vkAdmin_news_RSS_URL_ja', 'https://ex-unit.nagoya/ja/feed' );
			// $exUnit_feed_url = apply_filters( 'vkAdmin_news_RSS_URL_ja', 'https://www.vektor-inc.co.jp/feed/?category_name=internship' );
		} else {
			$exUnit_feed_url = apply_filters( 'vkAdmin_news_RSS_URL', 'https://ex-unit.nagoya/feed' );
		}

		$my_feeds = array(
			array( 'feed_url' => $exUnit_feed_url ),
		);

		foreach ( $my_feeds as $feed ) {
			$rss = fetch_feed( $feed['feed_url'] );

			if ( ! is_wp_error( $rss ) ) {
				$output = '';

				$maxitems = $rss->get_item_quantity( 5 ); //number of news to display (maximum)
				$rss_items = $rss->get_items( 0, $maxitems );
				$output .= '<div class="rss-widget">';
				$output .= '<h4 class="adminSub_title">'.apply_filters( 'vk-admin-sub-title-text', 'Information' ).'</h4>';
				$output .= '<ul>';

				if ( $maxitems == 0 ) {
					$output .= '<li>';
					$output .= __( 'Sorry, there is no post', 'vkExUnit' );
					$output .= '</li>';
				} else {
					foreach ( $rss_items as $item ) {
						$test_date 	= $item->get_local_date();
						$content 	= $item->get_content();

						if ( isset( $test_date ) && ! is_null( $test_date ) ) {
							$item_date = $item->get_date( get_option( 'date_format' ) ) . '<br />'; } else {
							$item_date = ''; }

							$output .= '<li style="color:#777;">';
							$output .= $item_date;
							$output .= '<a href="' . esc_url( $item->get_permalink() ) . '" title="' . $item_date . '" target="_blank">';
							$output .= esc_html( $item->get_title() );
							$output .= '</a>';
							$output .= '</li>';
					}
				}

				$output .= '</ul>';
				$output .= '</div>';
			}

		} // if ( ! is_wp_error( $rss ) ) {

		return $output;
	}

	/*--------------------------------------------------*/
	/*  admin _ Dashboard Widget
	/*--------------------------------------------------*/
	public static function dashboard_widget() {
		global $vk_admin_textdomain;
		wp_add_dashboard_widget(
			'vk_dashboard_widget',
			__( 'Vektor WordPress Information',$vk_admin_textdomain ),
			array( __CLASS__, 'dashboard_widget_body' )
		);
	}

	public static function dashboard_widget_body() {
		Vk_Admin::news_from_rest_api();
		Vk_Admin::admin_banner();
	}

	/*--------------------------------------------------*/
	/*  admin _ sub
	/*--------------------------------------------------*/
	// 2016.08.07 ExUnitの有効化ページでは直接 admin_subを呼び出しているので注意
	public static function admin_sub() {
		$adminSub = '<div class="adminSub scrTracking">'."\n";
		$adminSub .= '<div class="infoBox">'.Vk_Admin::get_news_body().'</div>'."\n";
		$adminSub .= '<div class="vk-admin-banner">'.Vk_Admin::admin_banner().'</div>'."\n";
		$adminSub .= '</div><!-- [ /.adminSub ] -->'."\n";
		return $adminSub;
	}

	/*--------------------------------------------------*/
	/*  admin _ page_frame
	/*--------------------------------------------------*/
	public static function admin_page_frame( $get_page_title, $the_body_callback, $get_logo_html = '' , $get_menu_html = '', $get_layout = 'column_3' ) { ?>
		<div class="wrap vk_admin_page">

			<div class="adminMain <?php echo $get_layout;?>">

				<?php if ( $get_layout == 'column_3' ) : ?>
				<div id="adminContent_sub" class="scrTracking">
					<div class="pageLogo"><?php echo $get_logo_html; ?></div>
					<?php if ( $get_page_title ) : ?>
					<h2 class="page_title"><?php echo $get_page_title;?></h2>
					<?php endif; ?>
					<div class="vk_option_nav">
						<ul>
						<?php echo $get_menu_html; ?>
						</ul>
					</div>
				</div><!-- [ /#adminContent_sub ] -->
				<?php endif; ?>

				<?php if ( $get_layout == 'column_2' ) : ?>
					<div class="pageLogo"><?php echo $get_logo_html; ?></div>
					<?php if ( $get_page_title ) : ?>
						<h1 class="page_title"><?php echo $get_page_title;?></h1>
					<?php endif; ?>
				<?php endif; ?>

				<div id="adminContent_main">
				<?php call_user_func_array( $the_body_callback, array() );?>
				</div><!-- [ /#adminContent_main ] -->

			</div><!-- [ /.adminMain ] -->

			<?php echo Vk_Admin::admin_sub();?>

		</div><!-- [ /.vkExUnit_admin_page ] -->
	<?php
	}

	public function __construct(){

	}
}
} // if ( ! class_exists( 'Vk_Admin' ) )

Vk_Admin::init();
$Vk_Admin = new Vk_Admin();
