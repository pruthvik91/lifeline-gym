<?php
$files = ['member_dashboard.php', 'login.php', 'index.php', 'admin_class.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Fix header('location: something.php') or header("location: something.php")
        $content = preg_replace('/header\s*\(\s*([\'"])location:\s*([^\'"]+)\.php([^\'"]*)\1\s*\)/i', 'header($1location:$2$3$1)', $content);
        
        // Fix location.href = 'something.php'
        $content = preg_replace('/location\.href\s*=\s*([\'"])([^\'"]+)\.php([^\'"]*)\1/i', 'location.href = $1$2$3$1', $content);
        
        file_put_contents($file, $content);
    }
}
echo 'Done';
?>
