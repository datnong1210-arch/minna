<?php
/**
 * Admin Settings View
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Save settings
if (isset($_POST['ahominna_settings_submit']) && check_admin_referer('ahominna_settings_nonce')) {
    update_option('ahominna_enable_audio', isset($_POST['enable_audio']) ? 1 : 0);
    update_option('ahominna_enable_video', isset($_POST['enable_video']) ? 1 : 0);
    update_option('ahominna_items_per_page', intval($_POST['items_per_page']));
    
    echo '<div class="notice notice-success"><p>' . __('Settings saved.', 'ahominna') . '</p></div>';
}

$enable_audio = get_option('ahominna_enable_audio', 1);
$enable_video = get_option('ahominna_enable_video', 1);
$items_per_page = get_option('ahominna_items_per_page', 10);
?>

<div class="wrap ahominna-settings">
    <h1><?php _e('AhoMINNA Settings', 'ahominna'); ?></h1>
    
    <form method="post" class="ahominna-settings-form">
        <?php wp_nonce_field('ahominna_settings_nonce'); ?>
        
        <h2><?php _e('General Settings', 'ahominna'); ?></h2>
        
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Enable Audio', 'ahominna'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_audio" value="1" <?php checked($enable_audio, 1); ?> />
                        <?php _e('Enable audio playback in lessons', 'ahominna'); ?>
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php _e('Enable Video', 'ahominna'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_video" value="1" <?php checked($enable_video, 1); ?> />
                        <?php _e('Enable video playback in lessons', 'ahominna'); ?>
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="items_per_page"><?php _e('Items Per Page', 'ahominna'); ?></label>
                </th>
                <td>
                    <input type="number" name="items_per_page" id="items_per_page" value="<?php echo esc_attr($items_per_page); ?>" min="1" max="100" />
                    <p class="description"><?php _e('Number of lessons to display per page in archive', 'ahominna'); ?></p>
                </td>
            </tr>
        </table>
        
        <h2><?php _e('Plugin Information', 'ahominna'); ?></h2>
        
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Version', 'ahominna'); ?></th>
                <td><?php echo AHOMINNA_VERSION; ?></td>
            </tr>
            
            <tr>
                <th scope="row"><?php _e('Plugin Directory', 'ahominna'); ?></th>
                <td><code><?php echo AHOMINNA_PLUGIN_DIR; ?></code></td>
            </tr>
            
            <tr>
                <th scope="row"><?php _e('Plugin URL', 'ahominna'); ?></th>
                <td><code><?php echo AHOMINNA_PLUGIN_URL; ?></code></td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="ahominna_settings_submit" class="button button-primary" value="<?php _e('Save Settings', 'ahominna'); ?>" />
        </p>
    </form>
</div>
