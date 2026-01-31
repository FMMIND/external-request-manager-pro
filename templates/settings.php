<?php
/**
 * Settings Template
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap erm-pro-wrap">
    <div class="erm-header">
        <h1><?php echo esc_html__('Settings', 'erm-pro'); ?></h1>
        <p class="erm-subtitle"><?php echo esc_html__('Configure External Requests Manager', 'erm-pro'); ?></p>
    </div>
    
    <div class="erm-settings-container">
        <form method="post" action="options.php" class="erm-settings-form">
            <?php
            settings_fields(ERM_PRO_OPTION_GROUP);
            do_settings_sections('erm-pro-settings');
            submit_button(esc_html__('Save Settings', 'erm-pro'), 'primary');
            ?>
        </form>
        
        <!-- Maintenance Section -->
        <div class="erm-settings-section erm-maintenance-section">
            <h2><?php echo esc_html__('Maintenance', 'erm-pro'); ?></h2>
            
            <div class="erm-maintenance-box">
                <h3><?php echo esc_html__('Clear Logs', 'erm-pro'); ?></h3>
                <p><?php echo esc_html__('Manage your request logs:', 'erm-pro'); ?></p>
                
                <div class="erm-maintenance-options">
                    <button type="button" class="button button-secondary" id="erm-clear-except-btn">
                        <span class="dashicons dashicons-trash"></span>
                        <?php echo esc_html__('Clear All Except Blocked', 'erm-pro'); ?>
                    </button>
                    
                    <button type="button" class="button button-link-delete" id="erm-clear-all-btn">
                        <span class="dashicons dashicons-trash"></span>
                        <?php echo esc_html__('Clear All & Unblock', 'erm-pro'); ?>
                    </button>
                </div>
            </div>
            
            <div class="erm-maintenance-box">
                <h3><?php echo esc_html__('Database Info', 'erm-pro'); ?></h3>
                <p>
                    <?php 
                    global $wpdb;
                    $table = $wpdb->prefix . ERM_PRO_TABLE_REQUESTS;
                    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
                    printf(
                        esc_html__('Total requests logged: %d', 'erm-pro'),
                        number_format($count)
                    );
                    ?>
                </p>
            </div>
        </div>
        
        <!-- About Section -->
        <div class="erm-settings-section erm-about-section">
            <h2><?php echo esc_html__('About', 'erm-pro'); ?></h2>
            <p>
                <strong><?php echo esc_html__('External Request Manager Pro', 'erm-pro'); ?></strong> v<?php echo esc_html(ERM_PRO_VERSION); ?>
            </p>
            <p>
                <?php echo esc_html__('A professional WordPress plugin for monitoring and managing external HTTP requests.', 'erm-pro'); ?>
            </p>
            <p>
                <a href="https://github.com/yourusername/external-request-manager-pro" target="_blank" class="button button-secondary">
                    <span class="dashicons dashicons-admin-links"></span>
                    <?php echo esc_html__('GitHub Repository', 'erm-pro'); ?>
                </a>
            </p>
        </div>
    </div>
    
    <!-- Back to Dashboard -->
    <div class="erm-footer">
        <a href="<?php echo admin_url('admin.php?page=erm-pro-logs'); ?>" class="button">
            <span class="dashicons dashicons-arrow-left-alt"></span>
            <?php echo esc_html__('Back to Dashboard', 'erm-pro'); ?>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clearExceptBtn = document.getElementById('erm-clear-except-btn');
    const clearAllBtn = document.getElementById('erm-clear-all-btn');
    
    if (clearExceptBtn) {
        clearExceptBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('<?php esc_attr_e('Clear all allowed logs? Blocked entries will remain.', 'erm-pro'); ?>')) {
                ermClearLogs('except_blocked');
            }
        });
    }
    
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('<?php esc_attr_e('Clear ALL logs including blocked? This cannot be undone.', 'erm-pro'); ?>')) {
                ermClearLogs('all');
            }
        });
    }
});

function ermClearLogs(mode) {
    jQuery.post(ermProData.ajaxUrl, {
        action: 'erm_clear_logs',
        nonce: ermProData.nonce,
        mode: mode
    }, function(response) {
        if (response.success) {
            alert(response.data.message);
            location.reload();
        } else {
            alert(response.data.message || '<?php esc_attr_e('Error', 'erm-pro'); ?>');
        }
    });
}
</script>
