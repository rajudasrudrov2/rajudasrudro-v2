<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Meta;
use RDR\V2\HeadlessCMS\PostTypes;
use RDR\V2\HeadlessCMS\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Projects extends ContentModule {
    public function list_page() {
        $search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
        $state = isset( $_GET['state'] ) ? sanitize_key( wp_unslash( $_GET['state'] ) ) : '';
        $category = isset( $_GET['category'] ) ? absint( $_GET['category'] ) : 0;
        $paged = max( 1, isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 );
        $args = array(
            'post_type'      => PostTypes::PROJECT,
            'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
            'posts_per_page' => 20,
            'paged'          => $paged,
            's'              => $search,
            'orderby'        => 'modified',
            'order'          => 'DESC',
        );
        if ( $state ) {
            if ( 'draft' === $state ) {
                $args['post_status'] = array( 'draft', 'pending', 'private' );
            } else {
                $mapped = Support::state_to_storage( $state );
                $args['post_status'] = $mapped['post_status'];
                $args['meta_query'] = array( array( 'key' => Meta::PUBLICATION_STATE, 'value' => $mapped['rdr_state'] ) );
            }
        }
        if ( $category ) {
            $args['tax_query'] = array( array( 'taxonomy' => Taxonomies::PROJECT_CATEGORY, 'field' => 'term_id', 'terms' => array( $category ) ) );
        }
        $query = new \WP_Query( $args );
        $terms = get_terms( array( 'taxonomy' => Taxonomies::PROJECT_CATEGORY, 'hide_empty' => false ) );
        ob_start(); ?>
        <section class="rdr-card">
            <form method="get" action="<?php echo esc_url( Support::url( 'work' ) ); ?>" class="rdr-filters">
                <div class="rdr-field"><label for="rdr-work-search">Search</label><input id="rdr-work-search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Project title or content"></div>
                <div class="rdr-field"><label for="rdr-work-state">Publication state</label><select id="rdr-work-state" name="state"><option value="">All states</option><?php foreach ( Support::portal_state_options() as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>"<?php selected( $state, $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></div>
                <div class="rdr-field"><label for="rdr-work-category">Category</label><select id="rdr-work-category" name="category"><option value="0">All categories</option><?php if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) : ?><option value="<?php echo absint( $term->term_id ); ?>"<?php selected( $category, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option><?php endforeach; endif; ?></select></div>
                <button class="rdr-button rdr-button-secondary" type="submit">Filter</button>
            </form>
        </section>
        <section class="rdr-card rdr-table-card">
            <?php if ( $query->have_posts() ) : ?>
            <div class="rdr-table-wrap"><table class="rdr-table"><thead><tr><th>Project</th><th>Category</th><th>Related Service</th><th>Featured</th><th>Publication</th><th>Case Study</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
            <?php foreach ( $query->posts as $post ) :
                $cats = wp_get_post_terms( $post->ID, Taxonomies::PROJECT_CATEGORY, array( 'fields' => 'names' ) );
                $service_id = absint( get_post_meta( $post->ID, '_rdr_related_service_id', true ) );
                $service = $service_id ? get_post( $service_id ) : null;
                $case_enabled = (bool) get_post_meta( $post->ID, '_rdr_case_study_enabled', true );
                $case_status = get_post_meta( $post->ID, '_rdr_case_status', true );
            ?>
            <tr><td><strong><?php echo esc_html( $post->post_title ? $post->post_title : '(Untitled)' ); ?></strong><small>#<?php echo absint( $post->ID ); ?></small></td><td><?php echo esc_html( ! is_wp_error( $cats ) && $cats ? implode( ', ', $cats ) : '—' ); ?></td><td><?php echo esc_html( $service instanceof \WP_Post ? $service->post_title : '—' ); ?></td><td><?php echo get_post_meta( $post->ID, '_rdr_featured', true ) ? 'Yes' : 'No'; ?></td><td><span class="rdr-badge"><?php echo esc_html( Support::portal_state_options()[ Support::current_portal_state( $post ) ] ); ?></span></td><td><?php echo $case_enabled ? esc_html( $case_status ? $case_status : 'enabled' ) : 'Disabled'; ?></td><td><?php echo esc_html( Support::format_date( $post->post_modified ) ); ?></td><td><a class="rdr-link" href="<?php echo esc_url( Support::url( 'work/edit/' . $post->ID ) ); ?>">Edit</a></td></tr>
            <?php endforeach; ?>
            </tbody></table></div>
            <?php else : ?><div class="rdr-empty"><span class="dashicons dashicons-portfolio" aria-hidden="true"></span><h2>No projects found</h2><p>Create a project or adjust the current filters.</p></div><?php endif; ?>
            <?php $this->pagination( $query, 'work', array( 's' => $search, 'state' => $state, 'category' => $category ) ); ?>
        </section>
        <?php
        $content = ob_get_clean();
        $actions = '<a class="rdr-button rdr-button-primary" href="' . esc_url( Support::url( 'work/add' ) ) . '">Add Project</a>';
        Shell::render( 'Work / Projects', 'work', $content, array( 'subtitle' => 'Manage portfolio projects and their optional Case Study detail.', 'actions' => $actions ) );
    }

    public function add_page() { $this->form_page( null ); }

    public function edit_page( $id ) {
        $post = get_post( absint( $id ) );
        if ( ! $post instanceof \WP_Post || PostTypes::PROJECT !== $post->post_type ) { Shell::simple_error( 404, 'Project not found', 'The requested Project does not exist or has the wrong content type.' ); }
        if ( ! current_user_can( 'edit_post', $post->ID ) ) { Shell::simple_error( 403, 'Access denied', 'You do not have permission to edit this Project.' ); }
        $this->form_page( $post );
    }

    private function form_page( $post ) {
        $is_edit = $post instanceof \WP_Post;
        if ( ! current_user_can( $is_edit ? 'edit_post' : 'edit_posts', $is_edit ? $post->ID : 0 ) ) { Shell::simple_error( 403, 'Access denied', 'You do not have permission to manage Projects.' ); }
        $errors = array(); $field_errors = array();
        $submitted = array();
        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $result = $this->handle_save( $post );
            if ( ! empty( $result['redirect'] ) ) { wp_safe_redirect( $result['redirect'] ); exit; }
            $errors = $result['errors']; $field_errors = $result['field_errors']; $submitted = $result['submitted'];
        }
        $title = $submitted ? ( isset( $submitted['title'] ) ? $submitted['title'] : '' ) : ( $is_edit ? $post->post_title : '' );
        $slug = $submitted ? ( isset( $submitted['slug'] ) ? $submitted['slug'] : '' ) : ( $is_edit ? $post->post_name : '' );
        $content_value = $submitted ? ( isset( $submitted['content'] ) ? $submitted['content'] : '' ) : ( $is_edit ? $post->post_content : '' );
        $meta = $submitted && isset( $submitted['meta'] ) && is_array( $submitted['meta'] ) ? $submitted['meta'] : array();
        $portal_state = $submitted ? ( isset( $submitted['portal_state'] ) ? $submitted['portal_state'] : 'draft' ) : ( $is_edit ? Support::current_portal_state( $post ) : 'draft' );
        $selected_categories = $submitted ? ( isset( $submitted['project_categories'] ) ? (array) $submitted['project_categories'] : array() ) : ( $is_edit ? wp_get_post_terms( $post->ID, Taxonomies::PROJECT_CATEGORY, array( 'fields' => 'ids' ) ) : array() );
        ob_start(); ?>
        <?php echo $this->errors_html( $errors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <form method="post" class="rdr-form rdr-editor-form" data-rdr-unsaved>
            <?php wp_nonce_field( 'rdr_portal_project_save', 'rdr_portal_nonce' ); ?>
            <section class="rdr-card"><h2>Project Summary</h2><div class="rdr-form-grid">
                <?php echo $this->field( 'title', 'Title', $title, 'text', true, '', isset( $field_errors['title'] ) ? $field_errors['title'] : '' ); ?>
                <?php echo $this->field( 'slug', 'Slug', $slug, 'text', false, 'Leave blank to generate it from the title.' ); ?>
                <?php echo $this->textarea( 'meta[_rdr_short_summary]', 'Short Summary', $this->meta( $post, '_rdr_short_summary', $meta ), 4 ); ?>
                <?php echo $this->checkbox( 'meta[_rdr_featured]', 'Featured Project', $this->meta( $post, '_rdr_featured', $meta ) ); ?>
                <?php echo $this->field( 'meta[_rdr_display_order]', 'Display Order', $this->meta( $post, '_rdr_display_order', $meta ), 'number' ); ?>
                <?php echo $this->select( 'portal_state', 'Publishing State', Support::portal_state_options(), $portal_state, 'Published is the only state eligible for the public rdr/v1 API when other safety rules pass.' ); ?>
            </div><?php echo $this->textarea( 'content', 'Main Description / Overview', $content_value, 10 ); ?></section>

            <section class="rdr-card"><h2>Classification</h2><div class="rdr-form-grid"><div class="rdr-field"><label>Project Categories</label><div class="rdr-check-grid"><?php echo Support::category_options( $selected_categories ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div><?php echo $this->relation_select( 'meta[_rdr_related_service_id]', 'Related Service', PostTypes::SERVICE, $this->meta( $post, '_rdr_related_service_id', $meta ) ); ?></div></section>

            <section class="rdr-card"><h2>Media</h2><div class="rdr-form-grid"><?php echo Support::media_field( 'meta[_rdr_feature_media_id]', 'Feature / Poster Media', $this->meta( $post, '_rdr_feature_media_id', $meta ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo Support::media_field( 'meta[_rdr_gallery_media_ids]', 'Gallery', $this->meta( $post, '_rdr_gallery_media_ids', $meta ), true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></section>

            <section class="rdr-card"><div class="rdr-card-head"><div><h2>Optional Case Study</h2><p>Case Study remains part of the Project record. Disabling it does not delete entered content.</p></div></div>
                <div class="rdr-form-grid"><?php echo $this->checkbox( 'meta[_rdr_case_study_enabled]', 'Enable Case Study', $this->meta( $post, '_rdr_case_study_enabled', $meta ) ); ?><?php echo $this->select( 'meta[_rdr_case_status]', 'Case Study Status', array( 'none'=>'None','available'=>'Available','development-preview'=>'Development Preview','migration'=>'Migration / Review' ), $this->meta( $post, '_rdr_case_status', $meta ) ); ?></div>
                <?php echo $this->textarea( 'meta[_rdr_case_overview]', 'Overview', $this->meta( $post, '_rdr_case_overview', $meta ), 6 ); ?>
                <?php echo $this->textarea( 'meta[_rdr_case_challenge]', 'Challenge', $this->meta( $post, '_rdr_case_challenge', $meta ), 6 ); ?>
                <?php echo $this->textarea( 'meta[_rdr_case_approach]', 'Approach', $this->meta( $post, '_rdr_case_approach', $meta ), 6 ); ?>
                <?php echo $this->list_repeater( '_rdr_case_deliverables', 'Deliverables', $this->meta( $post, '_rdr_case_deliverables', $meta ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $this->textarea( 'meta[_rdr_case_creative]', 'Creative / Variations', $this->meta( $post, '_rdr_case_creative', $meta ), 6 ); ?>
                <?php echo $this->textarea( 'meta[_rdr_case_outcomes]', 'Outcomes / Results', $this->meta( $post, '_rdr_case_outcomes', $meta ), 6, false, 'Enter verified outcomes only.' ); ?>
                <div class="rdr-form-grid"><?php echo $this->relation_select( 'meta[_rdr_related_review_id]', 'Related Review', PostTypes::REVIEW, $this->meta( $post, '_rdr_related_review_id', $meta ) ); ?><div class="rdr-field"><label>Related Work</label><?php echo Support::post_multiselect( 'meta[_rdr_related_project_ids]', PostTypes::PROJECT, $this->meta( $post, '_rdr_related_project_ids', $meta ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
            </section>

            <section class="rdr-card"><h2>SEO</h2><div class="rdr-form-grid"><?php echo $this->field( 'meta[_rdr_seo_title]', 'SEO Title', $this->meta( $post, '_rdr_seo_title', $meta ) ); ?><?php echo Support::media_field( 'meta[_rdr_og_image_id]', 'OG Image', $this->meta( $post, '_rdr_og_image_id', $meta ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php echo $this->textarea( 'meta[_rdr_meta_description]', 'Meta Description', $this->meta( $post, '_rdr_meta_description', $meta ), 4 ); ?></section>

            <div class="rdr-sticky-actions"><a class="rdr-button rdr-button-secondary" href="<?php echo esc_url( Support::url( 'work' ) ); ?>">Cancel</a><button class="rdr-button rdr-button-primary" type="submit" data-rdr-save><?php echo $is_edit ? 'Save Project' : 'Create Project'; ?></button><span class="rdr-save-state" data-rdr-save-state aria-live="polite"></span></div>
        </form>
        <?php
        $content = ob_get_clean();
        Shell::render( $is_edit ? 'Edit Project' : 'Add Project', 'work', $content, array( 'breadcrumbs' => array( array( 'label'=>'Work / Projects','url'=>Support::url('work') ), array( 'label'=>$is_edit?'Edit':'Add' ) ) ) );
    }

    private function handle_save( $post ) {
        $is_edit = $post instanceof \WP_Post;
        $submitted = wp_unslash( $_POST );
        $errors = array(); $field_errors = array();
        if ( ! isset( $_POST['rdr_portal_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdr_portal_nonce'] ) ), 'rdr_portal_project_save' ) ) { return array( 'errors'=>array('Security check failed. Reload the page and try again.'), 'field_errors'=>array(), 'submitted'=>$submitted ); }
        if ( $is_edit ? ! current_user_can( 'edit_post', $post->ID ) : ! current_user_can( 'edit_posts' ) ) { return array( 'errors'=>array('You do not have permission to save this Project.'), 'field_errors'=>array(), 'submitted'=>$submitted ); }
        $title = isset( $submitted['title'] ) ? sanitize_text_field( $submitted['title'] ) : '';
        if ( '' === $title ) { $errors[] = 'Project title is required.'; $field_errors['title'] = 'Enter a Project title.'; }
        $state = isset( $submitted['portal_state'] ) ? sanitize_key( $submitted['portal_state'] ) : 'draft';
        if ( ! array_key_exists( $state, Support::portal_state_options() ) ) { $errors[] = 'Publishing state is invalid.'; }
        $meta = isset( $submitted['meta'] ) && is_array( $submitted['meta'] ) ? $submitted['meta'] : array();
        $service = Support::validate_relation( isset( $meta['_rdr_related_service_id'] ) ? $meta['_rdr_related_service_id'] : 0, PostTypes::SERVICE, true );
        if ( false === $service ) { $errors[] = 'Related Service is invalid.'; }
        $review = Support::validate_relation( isset( $meta['_rdr_related_review_id'] ) ? $meta['_rdr_related_review_id'] : 0, PostTypes::REVIEW, true );
        if ( false === $review ) { $errors[] = 'Related Review is invalid.'; }
        $related_projects = Support::validate_relation_ids( isset( $meta['_rdr_related_project_ids'] ) ? $meta['_rdr_related_project_ids'] : array(), PostTypes::PROJECT );
        if ( $errors ) { return array( 'errors'=>$errors, 'field_errors'=>$field_errors, 'submitted'=>$submitted ); }
        $mapped = Support::state_to_storage( $state );
        $postarr = array( 'post_type'=>PostTypes::PROJECT, 'post_title'=>$title, 'post_name'=>isset($submitted['slug'])?sanitize_title($submitted['slug']):'', 'post_content'=>isset($submitted['content'])?wp_kses_post($submitted['content']):'', 'post_status'=>$mapped['post_status'] );
        if ( $is_edit ) { $postarr['ID'] = $post->ID; $post_id = wp_update_post( $postarr, true ); } else { $post_id = wp_insert_post( $postarr, true ); }
        if ( is_wp_error( $post_id ) ) { return array( 'errors'=>array('WordPress could not save the Project: '.$post_id->get_error_message()), 'field_errors'=>array(), 'submitted'=>$submitted ); }
        $meta['_rdr_related_service_id'] = $service;
        $meta['_rdr_related_review_id'] = $review;
        $meta['_rdr_related_project_ids'] = array_values( array_diff( $related_projects, array( absint( $post_id ) ) ) );
        $meta[ Meta::PUBLICATION_STATE ] = $mapped['rdr_state'];
        Support::save_meta_fields( $post_id, Meta::project_fields(), $meta );
        Support::update_categories( $post_id, isset( $submitted['project_categories'] ) ? $submitted['project_categories'] : array() );
        return array( 'redirect'=>Support::url( 'work/edit/' . $post_id ) . '?rdr_notice=' . ( $is_edit ? 'saved' : 'created' ) );
    }

    private function pagination( $query, $module, $params ) {
        if ( $query->max_num_pages <= 1 ) { return; }
        echo '<nav class="rdr-pagination" aria-label="Pagination">';
        for ( $i=1; $i <= $query->max_num_pages; $i++ ) {
            $params['paged']=$i; $url=add_query_arg(array_filter($params,static function($v){return ''!==$v && 0!==$v;}),Support::url($module));
            echo '<a class="' . ( $i === max(1,isset($_GET['paged'])?absint($_GET['paged']):1) ? 'is-current' : '' ) . '" href="' . esc_url( $url ) . '">' . absint( $i ) . '</a>';
        }
        echo '</nav>';
    }
}
