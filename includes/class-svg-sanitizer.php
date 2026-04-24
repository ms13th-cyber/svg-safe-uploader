<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SVG_Safe_Sanitizer {
    private $allowed_tags = [
        'svg', 'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon',
        'text', 'tspan', 'defs', 'g', 'symbol', 'use', 'view', 'desc', 'title',
        'clippath', 'mask', 'lineargradient', 'radialgradient', 'stop', 'style', 'image'
    ];

    public function sanitize( $file_path ) {
        $content = file_get_contents( $file_path );
        if ( ! $content ) return;

        libxml_use_internal_errors( true );
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;

        if ( ! @$dom->loadXML( $content, LIBXML_NONET | LIBXML_NOENT ) ) {
            libxml_clear_errors();
            return;
        }

        $this->process_node( $dom->documentElement );

        $sanitized_svg = $dom->saveXML( $dom->documentElement );
        file_put_contents( $file_path, $sanitized_svg );
        libxml_clear_errors();
    }

    private function process_node( $node ) {
        if ( ! $node || $node->nodeType !== XML_ELEMENT_NODE ) return;

        if ( ! in_array( strtolower( $node->tagName ), $this->allowed_tags ) ) {
            $node->parentNode->removeChild( $node );
            return;
        }

        if ( $node->hasAttributes() ) {
            $attrs_to_remove = [];
            foreach ( $node->attributes as $attr ) {
                $name = strtolower( $attr->name );
                $value = strtolower( $attr->value );
                if ( strpos( $name, 'on' ) === 0 || strpos( $value, 'javascript:' ) !== false ) {
                    $attrs_to_remove[] = $attr->name;
                }
            }
            foreach ( $attrs_to_remove as $attr_name ) {
                $node->removeAttribute( $attr_name );
            }
        }

        if ( $node->hasChildNodes() ) {
            $children = iterator_to_array( $node->childNodes );
            foreach ( $children as $child ) {
                $this->process_node( $child );
            }
        }
    }
}