<?php
namespace RDR\V2\HeadlessCMS\Portal;

if ( ! defined( 'ABSPATH' ) ) { exit; }

abstract class ContentModule {
    protected function errors_html( $errors ) {
        if ( ! $errors ) { return ''; }
        $html = '<div class="rdr-flash rdr-flash-error" role="alert"><strong>Please correct the highlighted fields.</strong><ul>';
        foreach ( $errors as $error ) { $html .= '<li>' . esc_html( $error ) . '</li>'; }
        return $html . '</ul></div>';
    }

    protected function field( $name, $label, $value = '', $type = 'text', $required = false, $help = '', $error = '' ) {
        $id = 'rdr-' . sanitize_html_class( str_replace( array( '[', ']', '_' ), '-', $name ) );
        $attrs = $required ? ' required aria-required="true"' : '';
        if ( $error ) { $attrs .= ' aria-invalid="true" aria-describedby="' . esc_attr( $id . '-error' ) . '"'; }
        $html = '<div class="rdr-field' . ( $error ? ' has-error' : '' ) . '"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label>';
        $html .= '<input id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( is_scalar( $value ) ? (string) $value : '' ) . '"' . $attrs . '>';
        if ( $help ) { $html .= '<p class="rdr-help">' . esc_html( $help ) . '</p>'; }
        if ( $error ) { $html .= '<p class="rdr-field-error" id="' . esc_attr( $id . '-error' ) . '">' . esc_html( $error ) . '</p>'; }
        return $html . '</div>';
    }

    protected function textarea( $name, $label, $value = '', $rows = 5, $required = false, $help = '', $error = '' ) {
        $id = 'rdr-' . sanitize_html_class( str_replace( array( '[', ']', '_' ), '-', $name ) );
        $attrs = $required ? ' required aria-required="true"' : '';
        if ( $error ) { $attrs .= ' aria-invalid="true" aria-describedby="' . esc_attr( $id . '-error' ) . '"'; }
        $html = '<div class="rdr-field' . ( $error ? ' has-error' : '' ) . '"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label>';
        $html .= '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="' . absint( $rows ) . '"' . $attrs . '>' . esc_textarea( is_scalar( $value ) ? (string) $value : '' ) . '</textarea>';
        if ( $help ) { $html .= '<p class="rdr-help">' . esc_html( $help ) . '</p>'; }
        if ( $error ) { $html .= '<p class="rdr-field-error" id="' . esc_attr( $id . '-error' ) . '">' . esc_html( $error ) . '</p>'; }
        return $html . '</div>';
    }

    protected function select( $name, $label, $options, $value = '', $help = '', $required = false, $error = '' ) {
        $id = 'rdr-' . sanitize_html_class( str_replace( array( '[', ']', '_' ), '-', $name ) );
        $html = '<div class="rdr-field' . ( $error ? ' has-error' : '' ) . '"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . ( $required ? ' required' : '' ) . ( $error ? ' aria-invalid="true"' : '' ) . '>';
        foreach ( $options as $key => $text ) { $html .= '<option value="' . esc_attr( $key ) . '"' . selected( (string) $value, (string) $key, false ) . '>' . esc_html( $text ) . '</option>'; }
        $html .= '</select>';
        if ( $help ) { $html .= '<p class="rdr-help">' . esc_html( $help ) . '</p>'; }
        if ( $error ) { $html .= '<p class="rdr-field-error">' . esc_html( $error ) . '</p>'; }
        return $html . '</div>';
    }

    protected function checkbox( $name, $label, $checked = false, $help = '' ) {
        $html = '<div class="rdr-field"><label class="rdr-check"><input name="' . esc_attr( $name ) . '" type="checkbox" value="1"' . checked( ! empty( $checked ), true, false ) . '> <span>' . esc_html( $label ) . '</span></label>';
        if ( $help ) { $html .= '<p class="rdr-help">' . esc_html( $help ) . '</p>'; }
        return $html . '</div>';
    }

    protected function meta( $post, $key, $submitted = array() ) {
        return Support::field_value( $post, $key, $submitted );
    }

    protected function relation_select( $name, $label, $post_type, $selected = 0, $help = '' ) {
        return '<div class="rdr-field"><label>' . esc_html( $label ) . '</label><select name="' . esc_attr( $name ) . '">' . Support::post_options( $post_type, $selected, true ) . '</select>' . ( $help ? '<p class="rdr-help">' . esc_html( $help ) . '</p>' : '' ) . '</div>';
    }

    protected function list_repeater( $key, $label, $items ) {
        $items = is_array( $items ) ? $items : array();
        if ( ! $items ) { $items = array( '' ); }
        ob_start(); ?>
        <div class="rdr-field rdr-repeater" data-rdr-repeater="list" data-name="meta[<?php echo esc_attr( $key ); ?>][]">
            <label><?php echo esc_html( $label ); ?></label>
            <div data-rdr-repeater-rows>
                <?php foreach ( $items as $item ) : ?><div class="rdr-repeater-row"><input type="text" name="meta[<?php echo esc_attr( $key ); ?>][]" value="<?php echo esc_attr( $item ); ?>"><div class="rdr-row-actions"><button type="button" class="rdr-button rdr-button-quiet" data-rdr-up>↑</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-down>↓</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-remove>Remove</button></div></div><?php endforeach; ?>
            </div>
            <button type="button" class="rdr-button rdr-button-secondary" data-rdr-add>Add item</button>
        </div>
        <?php return ob_get_clean();
    }

    protected function structured_repeater( $key, $label, $items, $title_label, $body_label ) {
        $items = is_array( $items ) ? $items : array();
        if ( ! $items ) { $items = array( array( 'title' => '', 'body' => '' ) ); }
        ob_start(); ?>
        <div class="rdr-field rdr-repeater" data-rdr-repeater="structured" data-key="<?php echo esc_attr( $key ); ?>">
            <label><?php echo esc_html( $label ); ?></label>
            <div data-rdr-repeater-rows>
                <?php foreach ( $items as $index => $item ) : ?><div class="rdr-repeater-row rdr-repeater-structured"><input type="text" data-rdr-subfield="title" name="meta[<?php echo esc_attr( $key ); ?>][<?php echo absint( $index ); ?>][title]" value="<?php echo esc_attr( isset( $item['title'] ) ? $item['title'] : '' ); ?>" placeholder="<?php echo esc_attr( $title_label ); ?>"><textarea data-rdr-subfield="body" name="meta[<?php echo esc_attr( $key ); ?>][<?php echo absint( $index ); ?>][body]" rows="3" placeholder="<?php echo esc_attr( $body_label ); ?>"><?php echo esc_textarea( isset( $item['body'] ) ? $item['body'] : '' ); ?></textarea><div class="rdr-row-actions"><button type="button" class="rdr-button rdr-button-quiet" data-rdr-up>↑</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-down>↓</button><button type="button" class="rdr-button rdr-button-quiet" data-rdr-remove>Remove</button></div></div><?php endforeach; ?>
            </div>
            <button type="button" class="rdr-button rdr-button-secondary" data-rdr-add>Add item</button>
        </div>
        <?php return ob_get_clean();
    }
}
