<?php
$files = [
    'workout_requests.php', 'whatsapp-setting.php', 'registered_members.php',
    'navbar.php', 'members.php', 'login_.php', 'lifeline_hq.php',
    'index.php', 'income_expense.php', 'home.php'
];

foreach ($files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('index.php?page=', 'admin-', $content);
        file_put_contents($file, $content);
    }
}

// Replace login.php with login in specific files
$files2 = ['landing_page.php', '404.php'];
foreach ($files2 as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('href="login.php"', 'href="login"', $content);
        file_put_contents($file, $content);
    }
}
echo "Done replacing URLs.";
