<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Settings as CoreSettings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class SiteSettings extends ContentModule {
    private $settings;
    public function __construct(CoreSettings $settings){$this->settings=$settings;}
    public function render(){if(!current_user_can('manage_options'))Shell::simple_error(403,'Access denied','Site Settings require the manage_options capability.');$errors=array();$submitted=array();if('POST'===$_SERVER['REQUEST_METHOD']){$result=$this->save();if(!empty($result['redirect'])){wp_safe_redirect($result['redirect']);exit;}$errors=$result['errors'];$submitted=$result['submitted'];}$value=$submitted&&isset($submitted['site'])&&is_array($submitted['site'])?$submitted['site']:$this->settings->site();ob_start();?>
    <?php echo $this->errors_html($errors); ?><form method="post" class="rdr-form rdr-editor-form" data-rdr-unsaved><?php wp_nonce_field('rdr_portal_site_settings_save','rdr_portal_nonce'); ?>
    <section class="rdr-card"><h2>Safe Public Site Settings</h2><div class="rdr-form-grid"><?php echo $this->field('site[public_email]','Public Contact Email',$value['public_email']??'','email'); ?><?php echo $this->field('site[fiverr_url]','Fiverr URL',$value['fiverr_url']??'','url'); ?><?php echo $this->field('site[linkedin_url]','LinkedIn URL',$value['linkedin_url']??'','url'); ?></div><?php echo $this->field('site[response_expectation]','Response Expectation',$value['response_expectation']??''); ?><?php echo Support::media_field('site[default_social_image_id]','Default Social Image',$value['default_social_image_id']??0); ?><div class="rdr-callout"><strong>Secret isolation</strong><p>Contact delivery webhooks, API keys, SMTP passwords and environment secrets are intentionally not exposed in this Portal.</p></div></section>
    <div class="rdr-sticky-actions"><button class="rdr-button rdr-button-primary" type="submit" data-rdr-save>Save Site Settings</button><span class="rdr-save-state" data-rdr-save-state aria-live="polite"></span></div></form>
    <?php $content=ob_get_clean();Shell::render('Site Settings','settings',$content,array('subtitle'=>'Manage only approved public site content.'));}
    private function save(){ $submitted=wp_unslash($_POST);$errors=array();if(!isset($_POST['rdr_portal_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rdr_portal_nonce'])),'rdr_portal_site_settings_save'))$errors[]='Security check failed. Reload and try again.';if(!current_user_can('manage_options'))$errors[]='You do not have permission to save Site Settings.';$input=isset($submitted['site'])&&is_array($submitted['site'])?$submitted['site']:array();if(!empty($input['public_email'])&&!is_email($input['public_email']))$errors[]='Public Contact Email is invalid.';foreach(array('fiverr_url','linkedin_url') as $key){if(!empty($input[$key])&&!wp_http_validate_url($input[$key]))$errors[]='One of the profile URLs is invalid.';}if($errors)return array('errors'=>$errors,'submitted'=>$submitted);$clean=$this->settings->sanitize_site_settings($input);update_option(CoreSettings::SITE_OPTION,$clean,false);return array('redirect'=>Support::url('settings').'?rdr_notice=saved');}
}
