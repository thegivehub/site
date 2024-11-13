#!/usr/local/bin/php
<?php
include("/simple/.env");
$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");

$tpl = file_get_contents("templates/blog3-item.html");

$result = $link->query("select * from blog_posts order by id desc limit 3");
$out = <<<EOT
        <!-- Blog Start -->
        <div class="blog">
            <div class="container">
                <div class="section-header text-center">
                    <p>Nuestro blog</p>
                    <h2>Últimas noticias y artículos directamente desde nuestro blog</h2>
                </div>
                <div class="row">
EOT;

while ($item = $result->fetch_object()) {
    $out .= preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $item;
        if (isset($item->{$m[1]})) {
            return $item->{$m[1]};
        } else {
            return "";
        }
    }, $tpl);
}
$out .= <<<EOT
 </div>
            </div>
        </div>
        <!-- Blog End -->
EOT;

print $out;

