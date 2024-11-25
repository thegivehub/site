#!/usr/local/bin/php
<?php
include("/simple/.env");
$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");


/* 
id, slug, title, subtitle, content, excerpt, featured_image_url, author_id, status, language, reading_time_minutes, piblished_at, created_at, update_at, category, post_image, featured, url, author, category_id
*
*/

$start = file_get_contents("../components/start.html");
$header = file_get_contents("templates/blogheader.html");
$single = file_get_contents("templates/single-post.html");
$end = file_get_contents("../components/end.html");

$recent = file_get_contents("recent.html");
$tabs = file_get_contents("tabs.html");

$page = $start . $header;
$jsout = json_decode(file_get_contents("blog_posts.json"));

array_shift($argv);
while ($file = array_shift($argv)) {
    $md = file_get_contents($file);

    $mdlines = preg_split("/\n/", $md);
        
    $shortfile = preg_replace("/\..*/", '', preg_replace("/^.*\//", '', $file));

    $out = new stdClass();
    $title = array_shift($mdlines);
    $tparts = preg_split("/[:\-]\s*/", preg_replace("/^\#+\s/", '', $title));

    $out->title = $tparts[0];
    $out->subtitle = $tparts[1];
    $out->fulltitle = preg_replace("/^\#+\s/", '', $title);
    $out->markdown = $md;
    
    $html = `pandoc  --metadata title="{$out->fulltitle}" --css pandoc.css --highlight-style breezedark -f markdown -t html5 "$file"`;
    $html = preg_replace("/^<h1.+?\/h1>/is",'', $html);
    print $html."\n";
    $out->content = $html;
    $out->reading_time_minutes = floor(count(preg_split("/\s+/", $md)) / 200);
    $out->published_at = date("Y-m-d H:i:s");
    $out->created_at = date("Y-m-d H:i:s");
    $out->updated_at = date("Y-m-d H:i:s");
    $out->slug = strtolower(preg_replace("/\W+/", '-', $out->title));
    $out->url = "/en/blog/{$shortfile}.html";
    $out->author = "Chris Robison";
    $out->category = "Charity";
    $out->category_id = 8;
    $out->featured = 0;
    $out->post_image = "/img/blog-".random_int(1, 20).".jpg";
    $out->language = "en";
    
    preg_match("/(<p.+?<\/p>)/is", $html, $m);
    $out->excerpt = $m[1];
    
    $jsout[] = $out;
    
    // First check if we already have this entry in our database
    $results = $link->query("SELECT * from posts where title='{$out->title}' and subtitle='{$out->subtitle}'");

    if ($existing = $results->fetch_object()) {
        // We have a winner! Let's update instead of creating a new record...
        $upd = [];
        foreach ($out as $key=>$val) {
            $upd[] = "`$key`='".$link->real_escape_string($val)."'";
        }
        $sql = "UPDATE posts set ".join(", ", $upd)." WHERE id=".$existing->id;
        $link->query($sql);
        print "Updated ".$link->affected_rows." records.\n";
    } else {
        $keys = []; 
        $vals = [];
        
        foreach ($out as $key=>$val) {
            $keys[] = "`".$key."`";
            $vals[] = "'".$link->real_escape_string($val)."'";
        }
        
        $sql = "INSERT INTO posts (".join(',', $keys).") VALUES (".join(',', $vals).")";
        $link->query($sql);

        print "Added ".$link->affected_rows." new records to 'post'\n";
    }

    $out->recent_posts = $recent;
    $out->tabs = $tabs;
    $page = $start . preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $out;
        if (isset($out->{$m[1]})) {
            return $out->{$m[1]};
        } else {
            return "";
        }
  
    }, $header);
    
    $newcontent = preg_replace_callback("/\%\%(.+?)\%\%/", function ($m) {
        global $out;
        if (isset($out->{$m[1]})) {
            return $out->{$m[1]};
        } else {
            return "";
        }
    }, $single);
    $page .= $newcontent;
    $page .= $end;
    $outfile = preg_replace("/.+\//", '', $out->url);
   
    file_put_contents("html/".$outfile, $page);
    print "Wrote ".strlen($page)." to html/$outfile\n";
}
file_put_contents("blog_posts.json", json_encode($jsout));


