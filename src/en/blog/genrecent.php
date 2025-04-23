#!/usr/local/bin/php
<?php
include("/simple/.env");
$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");

$results = $link->query("select * from posts order by id desc limit 5");

//$posts = preg_split("/\n/", $txt);

/* 
id, slug, title, subtitle, content, excerpt, featured_image_url, author_id, status, language, reading_time_minutes
*
*/

$start = file_get_contents("templates/recent-posts.html");
$mypost = file_get_contents("templates/recent-post.html");
$myposts = [];
$cnt = 1;
while ($post = $results->fetch_object()) {
    $obj = new stdClass();

    $outfile = preg_replace("/\.md/", '.html', $post->url);
    
    $obj->fulltitle = $post->fulltitle;
    $obj->subtitle = $post->subtitle;
    $obj->title = $post->title;
    $obj->category = $post->category;
    $obj->category_id = $post->category_id;
    $obj->post_image = $post->post_image; // "/site/img/blog-{$cnt}.jpg";
    $obj->link = $post->url;
    $obj->url = $post->url;

    if ($obj->title != "") {
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

