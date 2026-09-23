<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Meta;
use RDR\V2\HeadlessCMS\Plugin;
use RDR\V2\HeadlessCMS\PostTypes;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Services extends ContentModule {
    public function list_page() {
        $services = get_posts(array('post_type'=>PostTypes::SERVICE,'post_status'=>array('publish','draft','pending','private'),'posts_per_page'=>50,'orderby'=>'menu_order title','order'=>'ASC'));
        ob_start(); ?>
        <section class="rdr-card rdr-table-card">
            <?php if ( $services ) : ?><div class="rdr-table-wrap"><table class="rdr-table"><thead><tr><th>Service</th><th>Canonical Slug</th><th>Publication</th><th>Order</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
            <?php foreach ( $services as $post ) : ?><tr><td><strong><?php echo esc_html($post->post_title); ?></strong></td><td><code><?php echo esc_html($post->post_name); ?></code></td><td><span class="rdr-badge"><?php echo esc_html(Support::portal_state_options()[Support::current_portal_state($post)]); ?></span></td><td><?php echo absint(get_post_meta($post->ID,'_rdr_display_order',true)); ?></td><td><?php echo esc_html(Support::format_date($post->post_modified)); ?></td><td><a class="rdr-link" href="<?php echo esc_url(Support::url('services/edit/'.$post->ID)); ?>">Edit</a></td></tr><?php endforeach; ?>
            </tbody></table></div><?php else : ?><div class="rdr-empty"><h2>No Service records found</h2><p>Activation seeding should create the four canonical identities. No arbitrary Add Service workflow is provided here.</p></div><?php endif; ?>
        </section>
        <section class="rdr-card"><h2>Primary Service identity contract</h2><div class="rdr-code-list"><?php foreach ( Plugin::primary_services() as $slug=>$title ) : ?><div><strong><?php echo esc_html($title); ?></strong><code><?php echo esc_html($slug); ?></code></div><?php endforeach; ?></div><p class="rdr-help">The normal Portal does not expose an Add Service action. Canonical primary slugs are read-only here and remain protected by the CMS core.</p></section>
        <?php $content=ob_get_clean(); Shell::render('Services','services',$content,array('subtitle'=>'Edit the four approved Service records without changing their canonical identities.')); }

    public function edit_page( $id ) {
        $post=get_post(absint($id));
        if(!$post instanceof \WP_Post || PostTypes::SERVICE!==$post->post_type){Shell::simple_error(404,'Service not found','The requested Service does not exist or has the wrong content type.');}
        if(!current_user_can('edit_post',$post->ID)){Shell::simple_error(403,'Access denied','You do not have permission to edit this Service.');}
        $errors=array(); $submitted=array();
        if('POST'===$_SERVER['REQUEST_METHOD']){$result=$this->handle_save($post); if(!empty($result['redirect'])){wp_safe_redirect($result['redirect']);exit;} $errors=$result['errors'];$submitted=$result['submitted'];}
        $meta=$submitted&&isset($submitted['meta'])&&is_array($submitted['meta'])?$submitted['meta']:array();
        $title=$submitted?(isset($submitted['title'])?$submitted['title']:''):$post->post_title;
        $state=$submitted?(isset($submitted['portal_state'])?$submitted['portal_state']:'draft'):Support::current_portal_state($post);
        ob_start(); ?>
        <?php echo $this->errors_html($errors); ?>
        <form method="post" class="rdr-form rdr-editor-form" data-rdr-unsaved>
        <?php wp_nonce_field('rdr_portal_service_save','rdr_portal_nonce'); ?>
        <section class="rdr-card"><h2>Service Identity & Positioning</h2><div class="rdr-form-grid">
        <?php echo $this->field('title','Service Title',$title,'text',true); ?>
        <div class="rdr-field"><label>Canonical Slug</label><input type="text" value="<?php echo esc_attr($post->post_name); ?>" readonly aria-readonly="true"><p class="rdr-help">Locked in the normal Portal workflow.</p></div>
        <?php echo $this->select('portal_state','Publishing State',Support::portal_state_options(),$state); ?>
        <?php echo $this->field('meta[_rdr_display_order]','Display Order',$this->meta($post,'_rdr_display_order',$meta),'number'); ?>
        </div>
        <?php echo $this->textarea('meta[_rdr_short_description]','Short Description',$this->meta($post,'_rdr_short_description',$meta),4); ?>
        <?php echo $this->textarea('meta[_rdr_positioning]','Positioning',$this->meta($post,'_rdr_positioning',$meta),4); ?>
        <?php echo $this->field('meta[_rdr_hero_title]','Hero Title',$this->meta($post,'_rdr_hero_title',$meta),'text'); ?>
        <?php echo $this->textarea('meta[_rdr_hero_content]','Hero Content',$this->meta($post,'_rdr_hero_content',$meta),6); ?>
        </section>
        <section class="rdr-card"><h2>Media</h2><?php echo Support::media_field('meta[_rdr_hero_media_id]','Hero / Supporting Media',$this->meta($post,'_rdr_hero_media_id',$meta)); ?></section>
        <section class="rdr-card"><h2>Structured Content</h2>
        <?php echo $this->list_repeater('_rdr_capabilities','Capabilities',$this->meta($post,'_rdr_capabilities',$meta)); ?>
        <?php echo $this->list_repeater('_rdr_deliverables','Deliverables',$this->meta($post,'_rdr_deliverables',$meta)); ?>
        <?php echo $this->list_repeater('_rdr_use_cases','Use Cases',$this->meta($post,'_rdr_use_cases',$meta)); ?>
        <?php echo $this->list_repeater('_rdr_formats','Formats / Types',$this->meta($post,'_rdr_formats',$meta)); ?>
        <?php echo $this->structured_repeater('_rdr_process_steps','Process Steps',$this->meta($post,'_rdr_process_steps',$meta),'Step title','Step explanation'); ?>
        <?php echo $this->structured_repeater('_rdr_faq','FAQ',$this->meta($post,'_rdr_faq',$meta),'Question','Answer'); ?>
        <?php echo $this->textarea('meta[_rdr_why_raju]','Why Raju',$this->meta($post,'_rdr_why_raju',$meta),7); ?>
        <?php echo $this->checkbox('meta[_rdr_featured]','Featured Service',$this->meta($post,'_rdr_featured',$meta)); ?>
        </section>
        <section class="rdr-card"><h2>SEO</h2><div class="rdr-form-grid"><?php echo $this->field('meta[_rdr_seo_title]','SEO Title',$this->meta($post,'_rdr_seo_title',$meta)); ?><?php echo Support::media_field('meta[_rdr_og_image_id]','OG Image',$this->meta($post,'_rdr_og_image_id',$meta)); ?></div><?php echo $this->textarea('meta[_rdr_meta_description]','Meta Description',$this->meta($post,'_rdr_meta_description',$meta),4); ?></section>
        <div class="rdr-sticky-actions"><a class="rdr-button rdr-button-secondary" href="<?php echo esc_url(Support::url('services')); ?>">Cancel</a><button class="rdr-button rdr-button-primary" type="submit" data-rdr-save>Save Service</button><span class="rdr-save-state" data-rdr-save-state aria-live="polite"></span></div>
        </form>
        <?php $content=ob_get_clean(); Shell::render('Edit Service','services',$content,array('breadcrumbs'=>array(array('label'=>'Services','url'=>Support::url('services')),array('label'=>'Edit'))));
    }

    private function handle_save($post){$submitted=wp_unslash($_POST);$errors=array();
        if(!isset($_POST['rdr_portal_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rdr_portal_nonce'])),'rdr_portal_service_save'))$errors[]='Security check failed. Reload and try again.';
        if(!current_user_can('edit_post',$post->ID))$errors[]='You do not have permission to save this Service.';
        $title=isset($submitted['title'])?sanitize_text_field($submitted['title']):''; if(''===$title)$errors[]='Service title is required.';
        $state=isset($submitted['portal_state'])?sanitize_key($submitted['portal_state']):'draft'; if(!array_key_exists($state,Support::portal_state_options()))$errors[]='Publishing state is invalid.';
        if(!array_key_exists($post->post_name,Plugin::primary_services()))$errors[]='This Service does not match one of the four locked primary identities.';
        if($errors)return array('errors'=>$errors,'submitted'=>$submitted);
        $mapped=Support::state_to_storage($state); $result=wp_update_post(array('ID'=>$post->ID,'post_title'=>$title,'post_status'=>$mapped['post_status'],'post_name'=>$post->post_name),true); if(is_wp_error($result))return array('errors'=>array('WordPress could not save the Service: '.$result->get_error_message()),'submitted'=>$submitted);
        $meta=isset($submitted['meta'])&&is_array($submitted['meta'])?$submitted['meta']:array(); $meta[Meta::PUBLICATION_STATE]=$mapped['rdr_state']; $fields=Meta::service_fields(true); unset($fields['_rdr_hero_copy']); Support::save_meta_fields($post->ID,$fields,$meta);
        return array('redirect'=>Support::url('services/edit/'.$post->ID).'?rdr_notice=saved');
    }
}
