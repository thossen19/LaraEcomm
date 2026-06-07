<?php
// Simple script to generate placeholder images for deals
// This script creates basic colored rectangles with text

$deals = [
    'iphone-15-pro-max.jpg' => 'iPhone 15 Pro',
    'sony-headphones.jpg' => 'Sony Headphones',
    'samsung-tv.jpg' => 'Samsung TV',
    'nike-air-max.jpg' => 'Nike Shoes',
    'dell-laptop.jpg' => 'Dell Laptop',
    'canon-camera.jpg' => 'Canon Camera',
    'apple-watch.jpg' => 'Apple Watch',
    'lg-monitor.jpg' => 'LG Monitor'
];

$colors = [
    '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', 
    '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7DC6F'
];

foreach ($deals as $filename => $text) {
    $image = imagecreatetruecolor(400, 300);
    $bgColor = imagecolorallocate($image, 
        hexdec(substr($colors[array_rand($colors)], 1, 2)),
        hexdec(substr($colors[array_rand($colors)], 3, 2)),
        hexdec(substr($colors[array_rand($colors)], 5, 2))
    );
    imagefill($image, 0, 0, $bgColor);
    
    // Add white text
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 20;
    $angle = 0;
    $x = 200;
    $y = 150;
    
    // Use built-in font
    imagestring($image, 5, $x - strlen($text) * 4, $y - 10, $text, $textColor);
    
    // Save to storage
    $storagePath = __DIR__ . '/../storage/app/public/deals/' . $filename;
    imagejpeg($image, $storagePath, 90);
    imagedestroy($image);
    
    echo "Created: $filename\n";
}

echo "Deal images generated successfully!\n";
?>
