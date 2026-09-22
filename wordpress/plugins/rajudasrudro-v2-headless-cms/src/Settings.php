<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Settings {
    const SITE_OPTION  = 'rdr_v2_site_settings';
    const ABOUT_OPTION = 'rdr_v2_about_content';

    public function hooks() {
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_menu', array( $this, 'register_pages' ) );
        add_filter( 'option_page_capability_rdr_v2_about_group', array( $this, 'about_capability' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media' ) );
    }

    public function enqueue_media() {
        $page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
        if ( in_array( $page, array( 'rdr-v2-about', 'rdr-v2-site-settings' ), true ) ) {
            wp_enqueue_media();
        }
    }

    public function about_capability() {
        return 'edit_posts';
    }

    public function register_settings() {
        register_setting(
            'rdr_v2_site_settings_group',
            self::SITE_OPTION,
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_site_settings' ),
                'default'           => array(),
                'show_in_rest'      => false,
            )
        );

        register_setting(
            'rdr_v2_about_group',
            self::ABOUT_OPTION,
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_about' ),
                'default'           => array(),
                'show_in_rest'      => false,
            )
        );
    }

    public function register_pages() {
        add_menu_page(
            'RDR V2 CMS',
            'RDR V2 CMS',
            'edit_posts',
            'rdr-v2-cms',
            array( $this, 'render_overview' ),
            'dashicons-rest-api',
            58
        );

        add_submenu_page( 'rdr-v2-cms', 'About', 'About', 'edit_posts', 'rdr-v2-about', array( $this, 'render_about' ) );
        add_submenu_page( 'rdr-v2-cms', 'Site Settings', 'Site Settings', 'manage_options', 'rdr-v2-site-settings', array( $this, 'render_site_settings' ) );
    }

    public function render_overview() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'rdr-v2-headless-cms' ) );
        }
        ?>
        <div class="wrap">
            <h1>RDR V2 Headless CMS</h1>
            <p>This plugin manages structured content for the future Astro build. WordPress manages content; Astro remains responsible for public presentation.</p>
            <ul>
                <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . PostTypes::PROJECT ) ); ?>">Work / Projects</a></li>
                <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . PostTypes::SERVICE ) ); ?>">Services</a></li>
                <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . PostTypes::REVIEW ) ); ?>">Reviews</a></li>
                <li><a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">Insights / Posts</a></li>
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=rdr-v2-about' ) ); ?>">About</a></li>
                <?php if ( current_user_can( 'manage_options' ) ) : ?>
                    <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=rdr-v2-site-settings' ) ); ?>">Site Settings</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php
    }

    public function render_site_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'rdr-v2-headless-cms' ) );
        }
        $value = $this->site();
        ?>
        <div class="wrap">
            <h1>RDR V2 — Site Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'rdr_v2_site_settings_group' ); ?>
                <table class="form-table" role="presentation">
                    <?php $this->text_row( self::SITE_OPTION, 'public_email', 'Public contact email', $value, 'email' ); ?>
                    <?php $this->text_row( self::SITE_OPTION, 'fiverr_url', 'Fiverr profile URL', $value, 'url' ); ?>
                    <?php $this->text_row( self::SITE_OPTION, 'response_expectation', 'Response expectation', $value ); ?>
                    <?php $this->text_row( self::SITE_OPTION, 'linkedin_url', 'LinkedIn URL', $value, 'url' ); ?>
                    <?php $this->media_row( self::SITE_OPTION, 'default_social_image_id', 'Default social image', $value ); ?>
                </table>
                <p class="description">Contact delivery/webhook secrets are intentionally not stored here.</p>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php $this->media_picker_script(); ?>
        <?php
    }

    public function render_about() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'rdr-v2-headless-cms' ) );
        }
        $value = $this->about();
        ?>
        <div class="wrap">
            <h1>RDR V2 — About Content</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'rdr_v2_about_group' ); ?>
                <table class="form-table" role="presentation">
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'intro', 'Concise introduction', $value ); ?>
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'story', 'Professional story', $value, 8 ); ?>
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'pillars', 'Specialization / pillars', $value, 5, 'One item per line.' ); ?>
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'principles', 'Working principles', $value, 5, 'One item per line.' ); ?>
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'expectations', 'Client expectations', $value, 5, 'One item per line.' ); ?>
                    <?php $this->textarea_row( self::ABOUT_OPTION, 'personal_note', 'Personal / professional note', $value, 5 ); ?>
                    <?php $this->media_row( self::ABOUT_OPTION, 'portrait_media_id', 'Portrait media', $value ); ?>
                    <?php $this->text_row( self::ABOUT_OPTION, 'featured_project_ids', 'Featured Project IDs', $value, 'text', 'Comma-separated IDs.' ); ?>
                    <?php $this->text_row( self::ABOUT_OPTION, 'featured_service_ids', 'Featured Service IDs', $value, 'text', 'Comma-separated IDs.' ); ?>
                    <?php $this->text_row( self::ABOUT_OPTION, 'featured_review_ids', 'Featured Review IDs', $value, 'text', 'Comma-separated IDs.' ); ?>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php $this->media_picker_script(); ?>
        <?php
    }

    private function text_row( $option, $key, $label, $values, $type = 'text', $description = '' ) {
        $raw = isset( $values[ $key ] ) ? $values[ $key ] : '';
        if ( is_array( $raw ) ) {
            $raw = implode( ',', array_map( 'absint', $raw ) );
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr( $option . '-' . $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
            <td>
                <input class="regular-text" id="<?php echo esc_attr( $option . '-' . $key ); ?>" name="<?php echo esc_attr( $option . '[' . $key . ']' ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $raw ); ?>" />
                <?php if ( $description ) : ?><p class="description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
            </td>
        </tr>
        <?php
    }

    private function media_row( $option, $key, $label, $values ) {
        $raw = isset( $values[ $key ] ) ? absint( $values[ $key ] ) : 0;
        $id = $option . '-' . $key;
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></th>
            <td>
                <input id="<?php echo esc_attr( $id ); ?>" data-rdr-settings-media type="number" min="0" name="<?php echo esc_attr( $option . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( $raw ); ?>" />
                <button type="button" class="button" data-rdr-settings-media-pick>Select from Media Library</button>
                <button type="button" class="button-link-delete" data-rdr-settings-media-clear>Clear</button>
            </td>
        </tr>
        <?php
    }

    private function media_picker_script() {
        ?>
        <script>
        document.addEventListener('click', function (event) {
          var pick = event.target.closest('[data-rdr-settings-media-pick]');
          var clear = event.target.closest('[data-rdr-settings-media-clear]');
          if (!pick && !clear) return;
          var cell = (pick || clear).closest('td');
          var input = cell ? cell.querySelector('[data-rdr-settings-media]') : null;
          if (!input) return;
          if (clear) { input.value = ''; return; }
          if (!window.wp || !wp.media) return;
          var frame = wp.media({ title: 'Select media', button: { text: 'Use this media' }, multiple: false });
          frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            input.value = attachment.id || '';
          });
          frame.open();
        });
        </script>
        <?php
    }

    private function textarea_row( $option, $key, $label, $values, $rows = 5, $description = '' ) {
        $raw = isset( $values[ $key ] ) ? $values[ $key ] : '';
        if ( is_array( $raw ) ) {
            $raw = implode( "\n", $raw );
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr( $option . '-' . $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
            <td>
                <textarea class="large-text" rows="<?php echo esc_attr( $rows ); ?>" id="<?php echo esc_attr( $option . '-' . $key ); ?>" name="<?php echo esc_attr( $option . '[' . $key . ']' ); ?>"><?php echo esc_textarea( $raw ); ?></textarea>
                <?php if ( $description ) : ?><p class="description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public function sanitize_site_settings( $input ) {
        $input = is_array( $input ) ? $input : array();
        return array(
            'public_email'            => isset( $input['public_email'] ) ? sanitize_email( $input['public_email'] ) : '',
            'fiverr_url'              => isset( $input['fiverr_url'] ) ? esc_url_raw( $input['fiverr_url'] ) : '',
            'response_expectation'    => isset( $input['response_expectation'] ) ? sanitize_text_field( $input['response_expectation'] ) : '',
            'linkedin_url'            => isset( $input['linkedin_url'] ) ? esc_url_raw( $input['linkedin_url'] ) : '',
            'default_social_image_id' => isset( $input['default_social_image_id'] ) ? absint( $input['default_social_image_id'] ) : 0,
        );
    }

    public function sanitize_about( $input ) {
        $input = is_array( $input ) ? $input : array();
        return array(
            'intro'                => isset( $input['intro'] ) ? sanitize_textarea_field( $input['intro'] ) : '',
            'story'                => isset( $input['story'] ) ? wp_kses_post( $input['story'] ) : '',
            'pillars'              => Meta::sanitize_text_list( isset( $input['pillars'] ) ? $input['pillars'] : array() ),
            'principles'           => Meta::sanitize_text_list( isset( $input['principles'] ) ? $input['principles'] : array() ),
            'expectations'         => Meta::sanitize_text_list( isset( $input['expectations'] ) ? $input['expectations'] : array() ),
            'personal_note'        => isset( $input['personal_note'] ) ? sanitize_textarea_field( $input['personal_note'] ) : '',
            'portrait_media_id'    => isset( $input['portrait_media_id'] ) ? absint( $input['portrait_media_id'] ) : 0,
            'featured_project_ids' => Meta::sanitize_id_array( isset( $input['featured_project_ids'] ) ? $input['featured_project_ids'] : array() ),
            'featured_service_ids' => Meta::sanitize_id_array( isset( $input['featured_service_ids'] ) ? $input['featured_service_ids'] : array() ),
            'featured_review_ids'  => Meta::sanitize_id_array( isset( $input['featured_review_ids'] ) ? $input['featured_review_ids'] : array() ),
        );
    }

    public function site() {
        $value = get_option( self::SITE_OPTION, array() );
        return is_array( $value ) ? $value : array();
    }

    public function about() {
        $value = get_option( self::ABOUT_OPTION, array() );
        return is_array( $value ) ? $value : array();
    }
}
