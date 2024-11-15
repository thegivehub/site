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
$jsout = [];
$cnt = 1;

$result = $link->query("select * from posts where language='en'");
while ($post = $result->fetch_object()) {
    $obj = $post;
    
    $page = $start . preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
  
    }, $header);
    $jsout[] = $obj;

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
    $outfile = preg_replace("/.+\//", '', $obj->url);
   
    /*
     * $mdfile = 'md/' . preg_replace("/\.html/", '.md', $outfile);

    if (file_exists($mdfile)) {
        $mdtxt = file_get_contents($mdfile);
        $link->query("update posts set markdown='".$link->real_escape_string($mdtxt)."' where id=".$obj->id);
        print "Updated " . $link->affected_rows." rows\n";
    }
    */ 
    file_put_contents("html/".$outfile, $page);
    print "Wrote ".strlen($page)." to html/$outfile\n";
    ++$cnt;
}
file_put_contents("blog_posts.json", json_encode($jsout));

$exe = `./genblog3.php > ../components/blog3.html`;

