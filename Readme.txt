=== SVG Safe Uploader ===
Contributors: masato shibuya(Image-box Co., Ltd.)
Tags: svg, upload, security, sanitizer, media
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

PHPのDOMDocumentを利用した、軽量・高セキュリティなSVGアップロード有効化プラグインです。

== Description ==

WordPress標準では制限されているSVGファイルのアップロードを許可し、同時にセキュリティ上のリスク（XSS攻撃など）を排除するためのサニタイズ処理を行います。
外部ライブラリに依存せず、WordPressの内部判定を補正するアプローチを採用しているため、他の画像ファイルへの干渉を抑えつつ安定したプレビュー表示が可能です。

主な機能：
* SVGファイルのアップロード許可（MIMEタイプ追加）
* アップロード時の自動サニタイズ（DOMDocumentによるXML洗浄）
* 危険なタグ（script等）およびイベント属性（onclick等）の除去
* XXE攻撃（外部実体参照）の防止
* メディアライブラリでのプレビュー表示最適化
* WordPress内部判定の補正による表示遅延の解消

== Installation ==

1. `svg-safe-uploader` フォルダを `/wp-content/plugins/` ディレクトリにアップロードします。
2. `includes/class-svg-sanitizer.php` が正しく配置されていることを確認してください。
3. WordPressの「プラグイン」メニューから有効化します。
4. 有効化後、すぐにメディアライブラリからSVGファイルをアップロードできるようになります。

== Screenshots ==

1. メディアライブラリ：SVGファイルが他の画像と同様にプレビュー表示されます。

== Changelog ==

= 1.0.0 =
* 初版リリース。
* 基本的なサニタイズ機能の実装。

== Frequently Asked Questions ==

= アップロードしたSVGに仕込まれたスクリプトはどうなりますか？ =
アップロードの瞬間にプラグインがファイルを解析し、不許可なタグやJavaScriptを含む属性を自動的に削除します。サーバーに保存される時点で、ファイルは安全な状態に書き換えられます。

= ブラウザを更新しないとプレビューが表示されない場合は？ =
本プラグインはWordPressのシステム判定を補正し、可能な限り即時反映するよう設計されています。もし表示されない場合は、サーバーの.htaccess等でMIMEタイプが正しく設定されているか確認してください。