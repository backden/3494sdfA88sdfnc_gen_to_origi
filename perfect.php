<?php

require_once __DIR__ . '/lib/Sepia/InterfaceHandler.php';
require_once __DIR__ . '/lib/Sepia/StringHandler.php';
require_once __DIR__ . '/lib/Sepia/FileHandler.php';
require_once __DIR__ . '/lib/Sepia/PoParser.php';
require_once __DIR__ . '/lib/simple_html_dom.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function replaceElement($html, $resource, $options) {
    $htmlDom = file_get_html($html, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false, DEFAULT_BR_TEXT);
    $resourceContent = file_get_contents($resource);

    $firstRs = "<?= ";
    $endRs = "?>";

    $selector = $options["selector"];
    $where_replace = $options["where_replace"];
    $output = $options["output"];

    $elements = $htmlDom->find($selector);
    foreach ($elements as $ele) {
        $startPos = strpos($resourceContent, $firstRs);
        $endPos = strpos($resourceContent, $endRs) + strlen($endRs);
        $ele->{$where_replace} = substr($resourceContent, $startPos, ($endPos - $startPos));
        $resourceContent = substr($resourceContent, $endPos);
    }
    $htmlDom->save($output);
}

function replaceResourceToElement($html, $resource, $options) {
    $htmlDom = file_get_html($html, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false, DEFAULT_BR_TEXT);
    // Parse a po file
    $fileHandler = new Sepia\FileHandler($resource);

    $poParser = new Sepia\PoParser($fileHandler);
    $entries = $poParser->parse();

    $resourceContent = [];
    $resourceContentKeys = [];
    foreach ($entries as $data) {
        $msgId = $data["msgid"][0];
        $msgstr = $data["msgstr"][0];
        $resourceContent[$msgstr] = $msgId;
    }
    krsort($resourceContent);

    $selector = $options["selector"];
    $output = $options["output"];

    $elements = $htmlDom->find($selector);
    foreach ($resourceContent as $msg => $key) {
        if (empty($msg)) {
            continue;
        }
        foreach ($elements as $ele) {
            if (strpos($ele->innertext, $msg) === false) {
                continue;
            }
            if ($ele->tag === "label") {
                $ele->outertext = '<?= $this->Form->label("' . $key . '", __d($domain, "' . $key . '"));?>';
            } else {
                $ele->innertext = '<?= __d($domain, "' . $key . '");?>';
            }
        }
    }

    $htmlDom->save($output);
}

if (isset($_GET["start"])) {
    $originalHtml = __DIR__ . "/resource/bk/index.html";
    $po = __DIR__ . "/resource/po/text.po";
    $html = __DIR__ . "/resource/index.html";
    $output = __DIR__ . "/result/index.php";

    copy($originalHtml, $html);

    $resource = __DIR__ . "/resource/gen/input";
    replaceElement($html, $resource, [
        "selector" => "input[type='text']",
        "where_replace" => "outertext",
        "output" => $output
    ]);
    copy($output, $html);

    $resource = __DIR__ . "/resource/gen/select";
    replaceElement($html, $resource, [
        "selector" => "select",
        "where_replace" => "outertext",
        "output" => $output
    ]);
    copy($output, $html);

    $resource = __DIR__ . "/resource/gen/button";
    replaceElement($html, $resource, [
        "selector" => "button",
        "where_replace" => "outertext",
        "output" => $output
    ]);
    copy($output, $html);

//$resource = __DIR__ . "/resource/gen/radio";
//replaceElement($html, $resource, [
//    "selector" => "input[type='radio']",
//    "where_replace" => "outertext",
//    "output" => $output
//]);
//copy($output, $html);

    $resource = $po;
    replaceResourceToElement($html, $resource, [
        "selector" => "label",
        "output" => $output
    ]);
    copy($output, $html);

    $resource = $po;
    replaceResourceToElement($html, $resource, [
        "selector" => "a",
        "output" => $output
    ]);
    copy($output, $html);

    $resource = $po;
    replaceResourceToElement($html, $resource, [
        "selector" => "p",
        "output" => $output
    ]);
    copy($output, $html);
} else {
    //counting element
    $originalHtml = __DIR__ . "/resource/bk/index.html";
    $po = __DIR__ . "/resource/po/text.po";
    $html = __DIR__ . "/resource/counter.html";
    $output = __DIR__ . "/result/counter.php";

    copy($originalHtml, $html);

    $countElement = ["input[type=text]", "select", "textarea", "label"];
    $htmlDom = file_get_html($html);
    foreach ($countElement as $counter) {
        $elements = $htmlDom->find($counter);
        $count = 0;
        foreach ($elements as $ele) {
            $count++;
        }
        echo $counter . " = " . $count . "\n";
    }
}