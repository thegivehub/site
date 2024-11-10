#!/usr/local/bin/php
<?php
include("/simple/.env");
$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");
$txt = file_get_contents("posts.txt");

$posts = preg_split("/\n/", $txt);

/* 
id, slug, title, subtitle, content, excerpt, featured_image_url, author_id, status, language, reading_time_minutes
*
*/

$start = file_get_contents("templates/recent-posts.html");
$mypost = file_get_contents("templates/recent-post.html");
$myposts = [];
$cnt = 1;
for ($i=0; $i < 5; $i++) {
    $post = $posts[$i];
    $obj = new stdClass();

    $parts = preg_split("/\|/", $post);
    $file = trim($parts[0]);
    $outfile = preg_replace("/\.md/", '.html', $file);

    $titleparts = preg_split("/:/", preg_replace("/\*\s*/", '', $parts[1]));
   
    $title = $titleparts[0];
    $subtitle = $titleparts[1];
    
    $obj->fulltitle = $parts[1];
    $obj->subtitle = $subtitle;
    $obj->title = $title;
    $obj->category = "Charity";
    $obj->post_image = "/site/img/blog-{$cnt}.jpg";
    $obj->link = "/site/blog/$outfile";
    if ($title != "") {
    $myposts[] = preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
  
    }, $mypost);
    }
    ++$cnt;
}

$out = preg_replace("/\%\%recent_posts\%\%/", join("\n", $myposts), $start); 
print $out;

