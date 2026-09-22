<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Media {
    public function normalize( $attachment_id ) {
        $attachment_id = absint( $attachment_id );
        if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) ) {
            return null;
        }

        $src = wp_get_attachment_image_src( $attachment_id, 'full' );
        if ( ! is_array( $src ) || empty( $src[0] ) ) {
            return null;
        }

        $mime = get_post_mime_type( $attachment_id );
        return array(
            'id'       => $attachment_id,
            'url'      => esc_url_raw( $src[0] ),
            'alt'      => (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
            'width'    => isset( $src[1] ) ? (int) $src[1] : 0,
            'height'   => isset( $src[2] ) ? (int) $src[2] : 0,
            'mimeType' => $mime ? (string) $mime : '',
        );
    }

    public function normalize_many( $ids ) {
        $items = array();
        foreach ( Meta::sanitize_id_array( $ids ) as $id ) {
            $media = $this->normalize( $id );
            if ( null !== $media ) {
                $items[] = $media;
            }
        }
        return $items;
    }
}
