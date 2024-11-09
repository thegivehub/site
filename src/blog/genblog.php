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

$start = file_get_contents("../components/start.html");
$header = file_get_contents("templates/blogheader.html");
$single = file_get_contents("templates/single-post.html");
$end = file_get_contents("../components/end.html");

$exe = `./genrecent.php > recent.html`;
$exe = `./gentabs.php > tabs.html`;

$recent = file_get_contents("recent.html");
$tabs = file_get_contents("tabs.html");

$page = $start . $header;

foreach ($posts as $post) {
    $obj = new stdClass();

    $parts = preg_split("/\|/", $post);
    $file = trim($parts[0]);
    $id = $parts[2];
    $outfile = preg_replace("/\.md/", '.html', $file);

    $titleparts = preg_split("/:/", preg_replace("/\*\s*/", '', $parts[1]));
   

    $cwd = getcwd();;
    $cmd = "pandoc  --highlight-style breezedark -f markdown -t html5 md/{$parts[0]}";
    
    $content = `$cmd`;
    $content = preg_replace("/^<h1.+?\/h1>/s", '', $content);

    $link->query("UPDATE blog_posts set content='".mysqli_real_escape_string($link, $content)."' where id=$id");

    $title = $titleparts[0];
    $subtitle = $titleparts[1];
    
    $obj->content = $content;
    $obj->fulltitle = $parts[1];
    $obj->subtitle = $subtitle;
    $obj->title = $title;
    
    $page = $start . preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
  
    }, $header);

    $obj->recent_posts = $recent;
    $obj->tabs = $tabs;
    $newcontent = preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
    }, $single);
    $page .= $newcontent;
    //$page .= $recent;
    $page .= $end;

    file_put_contents("html/".$outfile, $page);
    print "Wrote ".strlen($page)." to html/$outfile\n";
        
}
   
