<?php
/**
 * Fired during plugin deactivation
 *
 * @package AhoMinna
 */

class AhoMinna_Deactivator {
    /**
     * Plugin deactivation tasks
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Note: We don't delete tables or data on deactivation
        // Only on uninstall (handled by uninstall.php)
    }
}
