# SVG Safe Uploader

A lightweight, high-security SVG upload enabler for WordPress. It sanitizes SVG files using PHP's `DOMDocument` to prevent XSS and XXE attacks, while ensuring stable media library previews via native WordPress hooks.

[日本語の解説は英語の後にあります]

---

## Key Features

- **Security First**: Automatically sanitizes SVG files upon upload. It removes malicious tags like `<script>`, event attributes (e.g., `onclick`), and prevents XXE (XML External Entity) attacks.
- **Native Preview Support**: Fixes the common "broken preview" issue in the WordPress Media Library by utilizing the `file_is_displayable_image` hook.
- **Zero Dependencies**: Built with native PHP (`DOMDocument`, `SimpleXML`) and WordPress hooks. No heavy external libraries required.
- **Conflict-Free**: Designed to work seamlessly without affecting other image formats like JPEG or PNG.

## Installation

1. Upload the `svg-safe-uploader` folder to your `/wp-content/plugins/` directory.
2. Ensure `includes/class-svg-sanitizer.php` is present.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Technical Details

- **Sanitization**: Uses a whitelist-based approach to strip dangerous elements from the XML structure.
- **MIME Type Handling**: Properly registers `image/svg+xml` to ensure server-side compatibility.

---

## 主な機能（日本語）

PHPのDOMDocumentを利用した、軽量・高セキュリティなSVGアップロード有効化プラグインです。

- **高度なサニタイズ**: アップロード時にXMLを解析し、スクリプトタグやイベント属性を除去してXSS攻撃を防ぎます。また、XXE（外部実体参照）攻撃対策も施されています。
- **プレビュー表示の最適化**: WordPress標準のフック（`file_is_displayable_image`）を補完することで、メディアライブラリでの即時プレビューを実現しました。
- **外部ライブラリ不要**: サーバー環境を選ばず、軽量に動作します。
- **他ファイルへの干渉なし**: 他の画像形式（JPG/PNG）の挙動を邪魔しないクリーンな設計です。

## インストール

1. `svg-safe-uploader` フォルダを `/wp-content/plugins/` にアップロードします。
2. 管理画面の「プラグイン」から有効化してください。