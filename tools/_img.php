<?php
require dirname(__DIR__) . '/app/public/wp-load.php';
global $wpdb;
$id = $wpdb->get_var("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE '%product55.jpg%' LIMIT 1");
echo "  attachment id: " . ($id ?: '(NOT an attachment)') . "\n";
if ($id) {
    $meta = wp_get_attachment_metadata($id);
    echo "  registered sizes: " . implode(', ', array_keys($meta['sizes'] ?? [])) . "\n";
    echo "  srcset would be: " . substr((string) wp_get_attachment_image_srcset($id, 'full'), 0, 200) . "\n";
}
echo "  theme image sizes: " . implode(', ', get_intermediate_image_sizes()) . "\n";
