<?php
function luminance($hex) {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex,0,2))/255;
    $g = hexdec(substr($hex,2,2))/255;
    $b = hexdec(substr($hex,4,2))/255;
    $r = $r <= 0.03928 ? $r/12.92 : pow(($r+0.055)/1.055, 2.4);
    $g = $g <= 0.03928 ? $g/12.92 : pow(($g+0.055)/1.055, 2.4);
    $b = $b <= 0.03928 ? $b/12.92 : pow(($b+0.055)/1.055, 2.4);
    return 0.2126*$r + 0.7152*$g + 0.0722*$b;
}
function contrast($c1, $c2) {
    $l1 = luminance($c1);
    $l2 = luminance($c2);
    return (max($l1,$l2)+0.05) / (min($l1,$l2)+0.05);
}
$bg = '#f2f0ed';
$checks = [
    'Primary text on bg' => ['#2d2d2d', $bg, 4.5],
    'Secondary text on bg' => ['#454545', $bg, 4.5],
    'Muted text on bg' => ['#5e5e5e', $bg, 4.5],
    'Green text on bg' => ['#2d6a4f', $bg, 4.5],
    'Blue text on bg' => ['#1a3a6b', $bg, 4.5],
    'Red text on bg' => ['#a83232', $bg, 4.5],
    'Orange text on bg' => ['#a04000', $bg, 4.5],
    'Dark green on bg' => ['#1b4332', $bg, 4.5],
    'Green on white card' => ['#2d6a4f', '#faf8f5', 4.5],
    'White on green button' => ['#ffffff', '#2d6a4f', 4.5],
];
echo "WCAG Contrast Ratios (Soft Light Theme):\n";
echo str_pad('', 40, '-') . "\n";
foreach ($checks as $name => $data) {
    list($fg, $bg_c, $min) = $data;
    $ratio = contrast($fg, $bg_c);
    $pass = $ratio >= $min ? 'PASS' : 'FAIL';
    $level = $ratio >= 7 ? 'AAA' : ($ratio >= 4.5 ? 'AA' : 'FAIL');
    printf("%-30s %5.1f:1  %s (%s)\n", $name, $ratio, $pass, $level);
}
