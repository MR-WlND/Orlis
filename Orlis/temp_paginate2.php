<?php
$views = [
    'd:/Orlis/Orlis/resources/views/admin/users/index.blade.php' => 'users',
    'd:/Orlis/Orlis/resources/views/admin/products/index.blade.php' => 'products',
    'd:/Orlis/Orlis/resources/views/admin/posts/index.blade.php' => 'posts',
    'd:/Orlis/Orlis/resources/views/admin/orders/index.blade.php' => 'orders',
    'd:/Orlis/Orlis/resources/views/admin/admins/index.blade.php' => 'admins',
];

foreach ($views as $file => $var) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $pattern = '/\{\{ \$\(\'vendor\.pagination\.admin\'\) \}\}/s';
        $replacement = "{{ $" . $var . "->links('vendor.pagination.admin') }}";
        $new_content = preg_replace($pattern, $replacement, $content);
        if ($new_content !== null && $new_content !== $content) {
            file_put_contents($file, $new_content);
            echo "Fixed $file\n";
        }
    }
}

// Check tickets and variants
$other_views = [
    'd:/Orlis/Orlis/resources/views/admin/tickets/index.blade.php' => 'tickets',
    'd:/Orlis/Orlis/resources/views/admin/products/variants/index.blade.php' => 'variants',
];

foreach ($other_views as $file => $var) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Did it replace tickets? Let's fix if it did {{ $('vendor.pagination.admin') }}
        $pattern = '/\{\{ \$\(\'vendor\.pagination\.admin\'\) \}\}/s';
        $replacement = "{{ $" . $var . "->links('vendor.pagination.admin') }}";
        $new_content = preg_replace($pattern, $replacement, $content);
        
        // Also if it wasn't replaced at all (script failed early)
        $pattern2 = "/\{\{\s*\\$" . $var . "->links\(\)\s*\}\}/";
        $replacement2 = "{{ $" . $var . "->links('vendor.pagination.admin') }}";
        $new_content = preg_replace($pattern2, $replacement2, $new_content);
        
        if ($new_content !== null && $new_content !== $content) {
            file_put_contents($file, $new_content);
            echo "Fixed $file\n";
        }
    }
}
