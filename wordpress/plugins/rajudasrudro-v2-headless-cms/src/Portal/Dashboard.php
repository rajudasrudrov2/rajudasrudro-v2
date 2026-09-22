<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\PostTypes;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Dashboard {
    public function render() {
        $counts = array(
            'Projects' => wp_count_posts( PostTypes::PROJECT ),
            'Services' => wp_count_posts( PostTypes::SERVICE ),
            'Reviews'  => wp_count_posts( PostTypes::REVIEW ),
            'Articles' => wp_count_posts( 'post' ),
        );
        ob_start();
        ?>
        <section class="rdr-stat-grid" aria-label="Content counts">
            <?php foreach ( $counts as $label => $object ) :
                $total = 0;
                if ( is_object( $object ) ) {
                    foreach ( get_object_vars( $object ) as $status => $count ) {
                        if ( ! in_array( $status, array( 'trash', 'auto-draft', 'inherit' ), true ) ) { $total += absint( $count ); }
                    }
                }
            ?>
            <article class="rdr-card rdr-stat"><span><?php echo esc_html( $label ); ?></span><strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong><small>Actual WordPress records</small></article>
            <?php endforeach; ?>
        </section>
        <section class="rdr-card">
            <div class="rdr-card-head"><div><h2>Quick actions</h2><p>Common content-management tasks.</p></div></div>
            <div class="rdr-action-grid">
                <a class="rdr-action-card" href="<?php echo esc_url( Support::url( 'work/add' ) ); ?>"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span><strong>Add Project</strong><span>Create a Work / Project record</span></a>
                <a class="rdr-action-card" href="<?php echo esc_url( Support::url( 'reviews/add' ) ); ?>"><span class="dashicons dashicons-star-filled" aria-hidden="true"></span><strong>Add Review</strong><span>Add verified review content</span></a>
                <a class="rdr-action-card" href="<?php echo esc_url( Support::url( 'insights/add' ) ); ?>"><span class="dashicons dashicons-welcome-write-blog" aria-hidden="true"></span><strong>Add Article</strong><span>Create an Insights post</span></a>
                <a class="rdr-action-card" href="<?php echo esc_url( Support::url( 'about' ) ); ?>"><span class="dashicons dashicons-id-alt" aria-hidden="true"></span><strong>Edit About</strong><span>Update About singleton content</span></a>
                <?php if ( current_user_can( 'manage_options' ) ) : ?><a class="rdr-action-card" href="<?php echo esc_url( Support::url( 'settings' ) ); ?>"><span class="dashicons dashicons-admin-settings" aria-hidden="true"></span><strong>Site Settings</strong><span>Update safe public site settings</span></a><?php endif; ?>
            </div>
        </section>
        <?php
        $content = ob_get_clean();
        Shell::render( 'Dashboard', 'dashboard', $content, array( 'subtitle' => 'Manage structured website content without using the default WordPress editor for normal workflows.' ) );
    }
}
