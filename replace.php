<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function str_replace_first($from, $to, $subject) {
    $from = '/' . preg_quote($from, '/') . '/';

    return preg_replace($from, $to, $subject, 1);
}

function replaceMe($src, $dest, $firstSrc, $endSrc, $firstDest, $endDest, $outfile = "") {
    $content = file_get_contents($src, "r");
    $replacer = file_get_contents($dest, "r");
    $copy = substr($replacer, 0);
    $replaceArr = [];
    $pos = -1;
    while (($pos = strpos($copy, $firstDest)) !== false) {
        $endPos = strpos($copy, $endDest);
        $s = substr($copy, $pos, ($endPos + strlen($endDest) - $pos));
        $replaceArr[] = $s;
        $copy = substr($copy, $endPos + strlen($endDest));
    }
    $pos = -1;
    $result = substr($content, 0);
    $index = 0;
    while (($pos = strpos($content, $firstSrc)) !== false && !empty($replaceArr[$index])) {
        $endPos = strpos($content, $endSrc);
        $s = substr($content, $pos, ($endPos + strlen($endSrc) - $pos));
        $result = str_replace_first($s, $replaceArr[$index++], $result);
        $content = substr($content, $endPos + strlen($endDest));
    }
    if (!empty($outfile)) {
        $fp = fopen($outfile . "/target.php", "w");
        fwrite($fp, $result);
        fclose($fp);
    }
    return $result;
}
$src = __DIR__ . "/source.php";
$dest = __DIR__ . "/dest.txt";
$firstSrc = "<label";
$endSrc = "/label>";
$firstDest = "<?php ";
$endDest = "?>";
echo htmlspecialchars(replaceMe($src, $dest, $firstSrc, $endSrc, $firstDest, $endDest, __DIR__ . "/collect"));
