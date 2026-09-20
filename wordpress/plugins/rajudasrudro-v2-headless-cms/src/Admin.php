<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Admin {
    /** @var Meta */
    private $meta;
    /** @var Settings */
    private $settings;

    public function __construct( Meta $meta, Settings $settings ) {
        $this->meta = $meta;
        $this->settings = $settings;
    }

    public function hooks() {
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 2 );
        add_filter( 'post_type_labels_post', array( $this, 'label_posts_as_insights' ) );
        add_filter( 'wp_insert_post_data', array( $this, 'protect_primary_service_slugs' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media' ) );
        add_action( 'admin_footer-post.php', array( $this, 'media_picker_script' ) );
        add_action( 'admin_footer-post-new.php', array( $this, 'media_picker_script' ) );
    }

    public function protect_primary_service_slugs( $data, $postarr ) {
        if ( PostTypes::SERVICE !== ( isset( $data['post_type'] ) ? $data['post_type'] : '' ) ) {
            return $data;
        }
        $post_id = isset( $postarr['ID'] ) ? absint( $postarr['ID'] ) : 0;
        if ( ! $post_id ) {
            return $data;
        }
        $existing = get_post( $post_id );
        if ( ! $existing instanceof \WP_Post ) {
            return $data;
        }
        if ( array_key_exists( $existing->post_name, Plugin::primary_services() ) ) {
            $data['post_name'] = $existing->post_name;
        }
        return $data;
    }

    public function enqueue_media() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( ! $screen || ! in_array( $screen->post_type, array( PostTypes::PROJECT, PostTypes::SERVICE, 'post' ), true ) ) {
            return;
        }
        wp_enqueue_media();
    }

    public function label_posts_as_insights( $labels ) {
        $labels->name = 'Insights / Posts';
        $labels->menu_name = 'Insights / Posts';
        $labels->singular_name = 'Insight Article';
        return $labels;
    }

    public function register_meta_boxes() {
        add_meta_box( 'rdr-project-details', 'Project Summary & Case Study', array( $this, 'render_project_box' ), PostTypes::PROJECT, 'normal', 'high' );
        add_meta_box( 'rdr-service-details', 'Service Structured Content', array( $this, 'render_service_box' ), PostTypes::SERVICE, 'normal', 'high' );
        add_meta_box( 'rdr-review-details', 'Review Details', array( $this, 'render_review_box' ), PostTypes::REVIEW, 'normal', 'high' );
        add_meta_box( 'rdr-article-details', 'RDR V2 Article Metadata', array( $this, 'render_article_box' ), 'post', 'normal', 'default' );
    }

    private function nonce_field() {
        wp_nonce_field( 'rdr_v2_save_meta', 'rdr_v2_meta_nonce' );
    }

    public function render_project_box( $post ) {
        $this->nonce_field();
        $this->textarea( $post, '_rdr_short_summary', 'Short summary', 3 );
        $this->media_id( $post, '_rdr_feature_media_id', 'Feature / poster media attachment ID' );
        $this->media_ids( $post, '_rdr_gallery_media_ids', 'Gallery media' );
        $this->post_select( $post, '_rdr_related_service_id', 'Related Service', PostTypes::SERVICE, true );
        $this->checkbox( $post, '_rdr_featured', 'Featured project' );
        $this->select( $post, Meta::PUBLICATION_STATE, 'Publication eligibility', Meta::publication_states() );
        $this->number( $post, '_rdr_display_order', 'Display order' );

        echo '<hr><h3>Optional Case Study</h3>';
        $this->checkbox( $post, '_rdr_case_study_enabled', 'Case Study available' );
        $this->select( $post, '_rdr_case_status', 'Case Study state', array( 'none' => 'None', 'available' => 'Available', 'development-preview' => 'Development preview', 'migration' => 'Migration / review' ) );
        $this->editor_like( $post, '_rdr_case_overview', 'Overview' );
        $this->editor_like( $post, '_rdr_case_challenge', 'Challenge' );
        $this->editor_like( $post, '_rdr_case_approach', 'Approach' );
        $this->textarea( $post, '_rdr_case_deliverables', 'Deliverables', 5, 'One deliverable per line.' );
        $this->editor_like( $post, '_rdr_case_creative', 'Creative / variations' );
        $this->editor_like( $post, '_rdr_case_outcomes', 'Outcome / results', 'Enter verified outcomes only. No result is pre-seeded.' );
        $this->post_select( $post, '_rdr_related_review_id', 'Related verified Review', PostTypes::REVIEW, true );
        $this->text( $post, '_rdr_related_project_ids', 'Related Project IDs', 'Comma-separated IDs.' );
        $this->seo_fields( $post );
    }

    public function render_service_box( $post ) {
        $this->nonce_field();
        $this->textarea( $post, '_rdr_short_description', 'Short description', 3 );
        $this->textarea( $post, '_rdr_positioning', 'Positioning', 3 );
        $this->editor_like( $post, '_rdr_hero_copy', 'Hero copy' );
        $this->media_id( $post, '_rdr_hero_media_id', 'Hero / supporting media attachment ID' );
        $this->textarea( $post, '_rdr_capabilities', 'Capabilities', 5, 'One item per line.' );
        $this->textarea( $post, '_rdr_deliverables', 'Deliverables', 5, 'One item per line.' );
        $this->textarea( $post, '_rdr_use_cases', 'Use cases', 5, 'One item per line.' );
        $this->textarea( $post, '_rdr_formats', 'Formats / types', 5, 'One item per line.' );
        $this->structured_rows( $post, '_rdr_process_steps', 'Process steps', 'Step title', 'Step explanation' );
        $this->structured_rows( $post, '_rdr_faq', 'FAQ', 'Question', 'Answer' );
        $this->editor_like( $post, '_rdr_why_raju', 'Why Raju' );
        $this->checkbox( $post, '_rdr_featured', 'Featured service' );
        $this->select( $post, Meta::PUBLICATION_STATE, 'Publication eligibility', Meta::publication_states() );
        $this->number( $post, '_rdr_display_order', 'Display order' );
        $this->seo_fields( $post );
        echo '<p><strong>Locked primary slugs:</strong> ai-ugc-video-ads, ai-video-production, ai-spokesperson-videos, web-design-development. Seeded identities are drafts and are never overwritten on reactivation.</p>';
    }

    public function render_review_box( $post ) {
        $this->nonce_field();
        $this->text( $post, '_rdr_reviewer_name', 'Reviewer display name / username' );
        $this->textarea( $post, '_rdr_review_text', 'Review text / accepted public summary', 6 );
        $this->number( $post, '_rdr_rating', 'Rating (optional, 1–5)', '', '0.1', '1', '5' );
        $this->text( $post, '_rdr_source', 'Source' );
        $this->text( $post, '_rdr_source_url', 'Source URL', '', 'url' );
        $this->text( $post, '_rdr_context', 'Context' );
        $this->text( $post, '_rdr_country', 'Verified country / location' );
        $this->text( $post, '_rdr_review_date', 'Verified review date', 'YYYY-MM-DD; leave empty if not verified.', 'date' );
        $this->checkbox( $post, '_rdr_featured', 'Featured review' );
        $this->post_select( $post, '_rdr_related_service_id', 'Related Service (optional)', PostTypes::SERVICE, true );
        $this->post_select( $post, '_rdr_related_project_id', 'Related Project (optional)', PostTypes::PROJECT, true );
        $this->select( $post, Meta::PUBLICATION_STATE, 'Publication eligibility', Meta::publication_states() );
        $this->number( $post, '_rdr_display_order', 'Display order' );
        echo '<p class="description">Missing rating/date remains missing. Do not infer Project or Service relationships from review wording.</p>';
    }

    public function render_article_box( $post ) {
        $this->nonce_field();
        $this->checkbox( $post, '_rdr_featured', 'Featured article' );
        $this->select( $post, Meta::PUBLICATION_STATE, 'Publication eligibility', Meta::publication_states() );
        $this->text( $post, '_rdr_related_service_ids', 'Related Service IDs', 'Comma-separated IDs.' );
        $this->text( $post, '_rdr_related_project_ids', 'Related Project IDs', 'Comma-separated IDs.' );
        $this->seo_fields( $post );
        echo '<p class="description">Article body uses the native WordPress editor. Public API exposure still requires post_status=publish and Publication eligibility=Public eligible.</p>';
    }

    public function save_post_meta( $post_id, $post ) {
        if ( ! $post instanceof \WP_Post ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
            return;
        }
        if ( ! isset( $_POST['rdr_v2_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdr_v2_meta_nonce'] ) ), 'rdr_v2_save_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = array();
        if ( PostTypes::PROJECT === $post->post_type ) {
            $fields = Meta::project_fields();
        } elseif ( PostTypes::SERVICE === $post->post_type ) {
            $fields = Meta::service_fields();
        } elseif ( PostTypes::REVIEW === $post->post_type ) {
            $fields = Meta::review_fields();
        } elseif ( 'post' === $post->post_type ) {
            $fields = Meta::article_fields();
        }

        foreach ( $fields as $key => $schema ) {
            $input_key = $this->input_key( $key );
            $raw = isset( $_POST[ $input_key ] ) ? wp_unslash( $_POST[ $input_key ] ) : null;

            if ( 'boolean' === $schema['type'] ) {
                $raw = isset( $_POST[ $input_key ] ) ? 1 : 0;
            }

            if ( '_rdr_process_steps' === $key || '_rdr_faq' === $key ) {
                $raw = isset( $_POST[ $input_key ] ) ? wp_unslash( $_POST[ $input_key ] ) : '[]';
            }

            $value = call_user_func( $schema['sanitize'], $raw );
            update_post_meta( $post_id, $key, $value );
        }
    }

    private function input_key( $meta_key ) {
        return 'rdr' . str_replace( '_rdr', '', $meta_key );
    }

    private function get_meta( $post, $key ) {
        return get_post_meta( $post->ID, $key, true );
    }

    private function text( $post, $key, $label, $description = '', $type = 'text' ) {
        $value = $this->get_meta( $post, $key );
        if ( is_array( $value ) ) {
            $value = implode( ',', $value );
        }
        printf( '<p><label><strong>%1$s</strong><br><input class="widefat" type="%2$s" name="%3$s" value="%4$s"></label>%5$s</p>', esc_html( $label ), esc_attr( $type ), esc_attr( $this->input_key( $key ) ), esc_attr( $value ), $description ? '<br><span class="description">' . esc_html( $description ) . '</span>' : '' );
    }

    private function textarea( $post, $key, $label, $rows = 5, $description = '' ) {
        $value = $this->get_meta( $post, $key );
        if ( is_array( $value ) ) {
            $value = implode( "\n", $value );
        }
        printf( '<p><label><strong>%1$s</strong><br><textarea class="widefat" rows="%2$d" name="%3$s">%4$s</textarea></label>%5$s</p>', esc_html( $label ), absint( $rows ), esc_attr( $this->input_key( $key ) ), esc_textarea( $value ), $description ? '<br><span class="description">' . esc_html( $description ) . '</span>' : '' );
    }

    private function editor_like( $post, $key, $label, $description = '' ) {
        $this->textarea( $post, $key, $label, 6, $description );
    }

    private function number( $post, $key, $label, $description = '', $step = '1', $min = '0', $max = '' ) {
        $value = $this->get_meta( $post, $key );
        if ( '_rdr_rating' === $key && 0 === (float) $value ) { $value = ''; }
        $max_attr = '' !== $max ? ' max="' . esc_attr( $max ) . '"' : '';
        printf( '<p><label><strong>%1$s</strong><br><input type="number" name="%2$s" value="%3$s" step="%4$s" min="%5$s"%6$s></label>%7$s</p>', esc_html( $label ), esc_attr( $this->input_key( $key ) ), esc_attr( $value ), esc_attr( $step ), esc_attr( $min ), $max_attr, $description ? '<br><span class="description">' . esc_html( $description ) . '</span>' : '' );
    }

    private function checkbox( $post, $key, $label ) {
        $checked = (bool) $this->get_meta( $post, $key );
        printf( '<p><label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label></p>', esc_attr( $this->input_key( $key ) ), checked( $checked, true, false ), esc_html( $label ) );
    }

    private function select( $post, $key, $label, $options ) {
        $value = (string) $this->get_meta( $post, $key );
        if ( '' === $value && Meta::PUBLICATION_STATE === $key ) {
            $value = 'public';
        }
        echo '<p><label><strong>' . esc_html( $label ) . '</strong><br><select name="' . esc_attr( $this->input_key( $key ) ) . '">';
        foreach ( $options as $option_value => $option_label ) {
            echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
        }
        echo '</select></label></p>';
    }

    private function media_id( $post, $key, $label ) {
        $value = absint( $this->get_meta( $post, $key ) );
        $input_id = 'rdr-media-' . $post->ID . '-' . trim( str_replace( '_', '-', $key ), '-' );
        echo '<p><label for="' . esc_attr( $input_id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        echo '<input id="' . esc_attr( $input_id ) . '" data-rdr-media-id type="number" min="0" name="' . esc_attr( $this->input_key( $key ) ) . '" value="' . esc_attr( $value ) . '"> ';
        echo '<button type="button" class="button" data-rdr-media-pick>Select from Media Library</button> ';
        echo '<button type="button" class="button-link-delete" data-rdr-media-clear>Clear</button>';
        echo '<br><span class="description">Stores only the WordPress attachment ID; the REST DTO normalizes safe public media fields.</span></p>';
    }

    private function media_ids( $post, $key, $label ) {
        $value = Meta::sanitize_id_array( $this->get_meta( $post, $key ) );
        $input_id = 'rdr-media-multi-' . $post->ID . '-' . trim( str_replace( '_', '-', $key ), '-' );
        echo '<p><label for="' . esc_attr( $input_id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        echo '<input class="widefat" id="' . esc_attr( $input_id ) . '" data-rdr-media-ids type="text" name="' . esc_attr( $this->input_key( $key ) ) . '" value="' . esc_attr( implode( ',', $value ) ) . '"> ';
        echo '<button type="button" class="button" data-rdr-media-multi>Select from Media Library</button> ';
        echo '<button type="button" class="button-link-delete" data-rdr-media-clear>Clear</button>';
        echo '<br><span class="description">Stores WordPress attachment IDs only; choose one or more media items.</span></p>';
    }

    public function media_picker_script() {
        ?>
        <script>
        document.addEventListener('click', function (event) {
          var pick = event.target.closest('[data-rdr-media-pick]');
          var multi = event.target.closest('[data-rdr-media-multi]');
          var clear = event.target.closest('[data-rdr-media-clear]');
          if (!pick && !multi && !clear) return;
          var row = (pick || multi || clear).closest('p');
          var input = row ? row.querySelector('[data-rdr-media-id], [data-rdr-media-ids]') : null;
          if (!input) return;
          if (clear) { input.value = ''; return; }
          if (!window.wp || !wp.media) return;
          var frame = wp.media({ title: 'Select media', button: { text: 'Use selected media' }, multiple: !!multi });
          frame.on('select', function () {
            if (multi) {
              var ids = frame.state().get('selection').map(function (item) { return item.toJSON().id; }).filter(Boolean);
              input.value = ids.join(',');
              return;
            }
            var attachment = frame.state().get('selection').first().toJSON();
            input.value = attachment.id || '';
          });
          frame.open();
        });
        </script>
        <?php
    }

    private function post_select( $post, $key, $label, $post_type, $optional = false ) {
        $value = absint( $this->get_meta( $post, $key ) );
        $items = get_posts( array( 'post_type' => $post_type, 'post_status' => array( 'publish', 'draft', 'pending', 'private' ), 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
        echo '<p><label><strong>' . esc_html( $label ) . '</strong><br><select name="' . esc_attr( $this->input_key( $key ) ) . '">';
        if ( $optional ) {
            echo '<option value="">— None —</option>';
        }
        foreach ( $items as $item ) {
            echo '<option value="' . esc_attr( $item->ID ) . '" ' . selected( $value, $item->ID, false ) . '>' . esc_html( $item->post_title . ' (#' . $item->ID . ')' ) . '</option>';
        }
        echo '</select></label></p>';
    }

    private function structured_rows( $post, $key, $label, $title_label, $body_label ) {
        $rows = $this->get_meta( $post, $key );
        $rows = is_array( $rows ) ? $rows : array();
        $json = wp_json_encode( $rows );
        echo '<div class="rdr-structured-field">';
        echo '<p><strong>' . esc_html( $label ) . '</strong></p>';
        echo '<div class="rdr-structured-rows" data-rdr-rows data-title-label="' . esc_attr( $title_label ) . '" data-body-label="' . esc_attr( $body_label ) . '"></div>';
        echo '<input type="hidden" data-rdr-rows-value name="' . esc_attr( $this->input_key( $key ) ) . '" value="' . esc_attr( $json ) . '">';
        echo '<button type="button" class="button" data-rdr-add-row>Add row</button>';
        echo '</div>';
        $this->structured_rows_script_once();
    }

    private function structured_rows_script_once() {
        static $printed = false;
        if ( $printed ) {
            return;
        }
        $printed = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
          document.querySelectorAll('[data-rdr-rows]').forEach(function (root) {
            var field = root.closest('.rdr-structured-field');
            var hidden = field.querySelector('[data-rdr-rows-value]');
            var add = field.querySelector('[data-rdr-add-row]');
            var rows = [];
            try { rows = JSON.parse(hidden.value || '[]'); } catch (e) { rows = []; }
            function sync() {
              hidden.value = JSON.stringify(rows.map(function (_, index) {
                var row = root.querySelector('[data-index="' + index + '"]');
                return row ? {
                  title: row.querySelector('[data-title]').value,
                  body: row.querySelector('[data-body]').value
                } : null;
              }).filter(Boolean));
            }
            function render() {
              root.innerHTML = '';
              rows.forEach(function (rowData, index) {
                var row = document.createElement('div');
                row.dataset.index = index;
                row.style.cssText = 'display:grid;grid-template-columns:minmax(140px,1fr) minmax(220px,2fr) auto;gap:8px;margin:8px 0;align-items:start';
                var title = document.createElement('input');
                title.type = 'text'; title.className = 'widefat'; title.dataset.title = '1';
                title.placeholder = root.dataset.titleLabel || 'Title'; title.value = rowData.title || '';
                var body = document.createElement('textarea');
                body.className = 'widefat'; body.rows = 2; body.dataset.body = '1';
                body.placeholder = root.dataset.bodyLabel || 'Body'; body.value = rowData.body || '';
                var remove = document.createElement('button');
                remove.type = 'button'; remove.className = 'button'; remove.textContent = 'Remove';
                remove.addEventListener('click', function () { rows.splice(index, 1); render(); sync(); });
                title.addEventListener('input', sync); body.addEventListener('input', sync);
                row.appendChild(title); row.appendChild(body); row.appendChild(remove); root.appendChild(row);
              });
              sync();
            }
            add.addEventListener('click', function () { rows.push({title:'',body:''}); render(); });
            render();
          });
        });
        </script>
        <?php
    }

    private function seo_fields( $post ) {
        echo '<hr><h3>SEO</h3>';
        $this->text( $post, '_rdr_seo_title', 'SEO title' );
        $this->textarea( $post, '_rdr_meta_description', 'Meta description', 3 );
        $this->media_id( $post, '_rdr_og_image_id', 'OG image attachment ID' );
    }
}
