#!/usr/local/bin/php
<?php

array_shift($argv);

$wrapstart = <<<EOT
<div class="container">
    <div class="row align-items-center" style="justify-content: center;">
        <div class="col-lg-7">
            <div class="my-content">
EOT;
$wrapend = <<<EOT
            </div>
        </div>
    </div>
</div>
EOT;
$start = file_get_contents("components/start.html");
$pagehead = file_get_contents("components/pageheader.html");
$end = file_get_contents("components/end.html");

while ($file = array_shift($argv)) {
    $markdown = file_get_contents($file);
    $mlines = preg_split("/\n/", $markdown);
    $title = preg_replace("/^#+\s*/", '', $mlines[0]);

    $out = $wrapstart;
    $shortfile = preg_replace("/\.md/", '', preg_replace("/^.+\//", '', $file));

    $html = `pandoc --metadata title="The Give Hub" -F mermaid-filter --highlight-style breezedark --css blog/pandoc.css -f markdown -t html5  {$file}`;
    $out .= $html;
    $out .= $wrapend;
    file_put_contents("components/{$shortfile}.html", $out);
    print "Wrote html fragment to components/{$shortfile}.html\n";
    $out = $start.$pagehead.$out.$end;
    $out = preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $title;
        return $title;
    }, $out);
    
    file_put_contents("newsite/{$shortfile}.html", $out);
    print "Wrote complete HTML doc to newsite/{$shortfile}.html\n";
}

