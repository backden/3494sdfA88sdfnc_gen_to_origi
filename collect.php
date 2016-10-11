<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dest = __DIR__ . "/dest.txt";
$firstDest = "<?php ";
$endDest = "?>";
function collect($dest, $firstDest, $endDest, $outfile = "") {
    $content = file_get_contents($dest);
    $copy = substr($content, 0);
    $replaceArr = [];
    $pos = -1;
    while (($pos = strpos($copy, $firstDest)) !== false) {
        $endPos = strpos($copy, $endDest);
        $s = substr($copy, $pos, ($endPos + strlen($endDest) - $pos));
        $type = "input";
        if (strpos($s, "select") !== false) {
            $type = "select";
        } else if (strpos($s, "checkbox") !== false) {
            $type = "checkbox";
        } else if (strpos($s, "radio") !== false) {
            $type = "radio";
        }
        $replaceArr[$type][] = $s;
        $copy = substr($copy, $endPos + strlen($endDest));
    }
    if (!empty($outfile)) {
        foreach ($replaceArr as $type => $arr) {
            $fp = fopen($outfile . "/$type", "w");
            foreach ($arr as $ele) {
                fwrite($fp, $ele . "\n");
            }
            fclose($fp);
        }
    }
    return $replaceArr;
}

echo htmlentities(print_r(collect($dest, $firstDest, $endDest, __DIR__ . "/collect"), true));