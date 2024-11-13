#!/usr/local/bin/php
<?php
include("/simple/.env");
$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");


$out = <<<EOT
<div class="tab-post">
    <ul class="nav nav-pills nav-justified">
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#featured">Featured</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#popular">Popular</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#latest">Latest</a>
        </li>
    </ul>
    <div class="tab-content">
EOT;

$itemtpl = file_get_contents("templates/post-item.html");

// First get featured

$out .= '<div id="featured" class="container tab-pane active show">';

$sql = "SELECT * FROM blog_posts WHERE featured=1 and language='es'";
$result = $link->query($sql);

while ($obj = $result->fetch_object()) {
    $out .= preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
    }, $itemtpl);
}
$out .= '</div>';

// now do 'popular'
$out .= '<div id="popular" class="container tab-pane">';

$sql = "SELECT * FROM post_metrics where language='es' order by views_count desc limit 5";
$result = $link->query($sql);

while ($obj = $result->fetch_object()) {
    $obj2 = get_post($obj->post_id);
    
    $out .= preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $obj2;
        if (isset($obj2->{$m[1]})) {
            return $obj2->{$m[1]};
        } else {
            return "";
        }
    }, $itemtpl);
}  
$out .= "</div>";

// now do latest

$out .= '<div id="latest" class="container tab-pane">';

$sql = "SELECT * FROM blog_posts WHERE language='es' order by published_at desc limit 5";
$result = $link->query($sql);

while ($obj = $result->fetch_object()) {
    $out .= preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
    }, $itemtpl);
}
$out .= '</div>';



$out .= "</div>";

print $out;

function get_post($id) {
    global $link;
    $result = $link->query("select * from blog_posts where id='{$id}'");
    return $result->fetch_object();
}
