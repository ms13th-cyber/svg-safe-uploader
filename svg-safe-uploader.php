<?php
/**
 * Plugin Name: SVG Safe Uploader
 * Description: SVGファイルのアップロードを許可し、安全にサニタイズします。
 * Version: 1.0.0
 * Tested up to: 6.9.4
 * Requires PHP: 8.3.23
 * Author: masato shibuya(Image-box Co., Ltd.)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-svg-sanitizer.php';

class SVG_Safe_Uploader {

    public function __construct() {
        // MIMEタイプ追加
        add_filter( 'upload_mimes', [ $this, 'add_svg_mime_types' ] );

        // サニタイズ
        add_filter( 'wp_handle_upload_prefilter', [ $this, 'sanitize_svg_upload' ] );

        // 最小限の管理画面修正
        add_action( 'admin_head', [ $this, 'minimal_svg_css' ] );
        add_filter( 'wp_prepare_attachment_for_js', [ $this, 'fix_svg_js_type' ], 10, 3 );

		add_filter( 'file_is_displayable_image', [ $this, 'svg_is_displayable' ], 10, 2 );
		add_filter( 'wp_check_filetype_and_ext', [ $this, 'fix_svg_mime_type' ], 10, 4 );
	}

    public function add_svg_mime_types( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
    }

	/**
	 * SVGを「表示可能な画像」としてシステムに認識させる
	 */
	public function svg_is_displayable( $result, $path ) {
		if ( $result ) return $result;
		$extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		if ( 'svg' === $extension || 'svgz' === $extension ) {
			return true;
		}
		return $result;
	}

	/**
	 * アップロード時のファイルタイプ判定を厳密に行う
	 */
	public function fix_svg_mime_type( $data, $file, $filename, $mimes ) {
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );
		if ( 'svg' === $ext ) {
			$data['type'] = 'image/svg+xml';
			$data['ext']  = 'svg';
		}
		return $data;
	}

    public function sanitize_svg_upload( $file ) {
        if ( $file['type'] === 'image/svg+xml' ) {
            $sanitizer = new SVG_Safe_Sanitizer();
            $sanitizer->sanitize( $file['tmp_name'] );
        }
        return $file;
    }

    // SVGのみをターゲットにした最小限のCSS
    public function minimal_svg_css() {
        echo '<style>
            .thumbnail img[src$=".svg"], [data-mime="image/svg+xml"] img {
                width: 100% !important;
                height: auto !important;
            }
        </style>';
    }

    // JS側で「画像」として扱わせる最小限の処理
    public function fix_svg_js_type( $response, $attachment, $meta ) {
        if ( $response['mime'] === 'image/svg+xml' ) {
            $response['type'] = 'image';
        }
        return $response;
    }
}

new SVG_Safe_Uploader();


require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

$updateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/ms13th-cyber/svg-safe-uploader/',
    __FILE__,
    'svg-safe-uploader'
);

$updateChecker->setBranch('main');