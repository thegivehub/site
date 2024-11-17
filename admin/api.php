<?php

include("/simple/.env");

$link = mysqli_connect($env->db->host, $env->db->user, $env->db->pass, "givehub");

$in = $_REQUEST;

$json = file_get_contents('php://input');

if (isset($json)) {
    $posted = json_decode($json);
}

if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!="") {
    $parts = preg_split("/\//", $_SERVER['PATH_INFO']);
    array_shift($parts);
    if (count($parts)) $in['rsc'] = array_shift($parts);
    if (count($parts)) $in['id'] = array_shift($parts);
    if (count($parts)) $in['x'] = array_shift($parts);
}

if (!isset($in['rsc'])) $in['rsc'] = "posts";
if (!isset($in['x'])) $in['x'] = "get";

if (($_SERVER['REQUEST_METHOD'] == "PUT") && (!isset($posted['id']))) {
    $in['x'] = 'create';
} else if ($_SERVER['REQUEST_METHOD'] == "POST")  {
    $in['x'] = 'update';
}

file_put_contents("x.log", date('Y-m-d H:i:s') ."\nPOSTED\n======\n".json_encode($posted, JSON_PRETTY_PRINT)."\n\n_REQUEST\n==========\n". json_encode($_REQUEST,JSON_PRETTY_PRINT) ."\n\n_SERVER\n========\n". json_encode($_SERVER, JSON_PRETTY_PRINT)."\n\n===\n", FILE_APPEND);
$out = [];

switch ($in['x']) {
    case "get":
        $out = get($in['rsc'], $in['id']);
        break;
    case "create":
        $out = create($in['rsc'], $in[$in['rsc']]);
        break;
    case "update":
        $out = update($in['rsc'], $in['id'], $posted);
        break;
    case "remove":
        $out = remove($in['rsc'], $in['id']);
        break;
}

header("Content-Type: application/json");
print json_encode($out);


function get($rsc="posts", $id="") {
    global $link;
    
    $cond = [];

    if ($_GET['language']) {
        $cond[] = "language='".$_GET['language']."'";
    }

    $sql = "SELECT * FROM {$rsc}";

    if (isset($id) && $id!="") {
        if (is_numeric($id)) {
            $cond[] = "id='".$link->real_escape_string($id)."'";
        } else {
            $cond[] = "$id";
        }
    }
    
    if (count($cond)) {
        $sql .= " WHERE ".join(' AND ', $cond);
    }
    $out = [];
    $results = $link->query($sql);

    while ($row = $results->fetch_object()) {
        $out[] = $row;
    }
    $realout = new stdClass();
    $realout->{$rsc} = $out;

    return $out;
}

function update($rsc="posts", $id, $data) {
    global $link;
    
    $sql = "UPDATE {$rsc} SET ";
    $sets = [];

    foreach ($data as $key=>$val) {
        $sets[] = "`{$key}`='".$link->real_escape_string($val)."'";
    }
    $sql .= join(", ", $sets) . " WHERE id='".$link->real_escape_string($id)."'";

    $link->query($sql);

    $out = new stdClass();
    $out->affected_rows = $link->affected_rows;
    $out->data = get($rsc, $id);

    return $out;    
}

function create($rsc="posts", $data) {
    global $link;

    $keys = [];
    $vals = [];

    foreach ($data as $key=>$val) {
        $keys[] = "`{$key}`";
        $vals[] = "'".$link->real_escape_string($val)."'";
    }
    
    $sql = "INSERT INTO {$rsc} (" . join(", ", $keys) . ") VALUES (" . join(", ", $vals) . ")";
    $link->query($sql);
    
    $newid = $link->insert_id;

    $out = new stdClass();
    $out->new_id = $newid;
    $out->data = get($rsc, $id);
    $out->status = "ok";
    $out->msg = "Created new '{$rsc}' [{$newid}]";

    return $out;    
}

function remove($rsc="posts", $id) {
    global $link;
    $out = new stdClass();

    if (!isset($id) || $id=="") {
        $out->status = "error";
        $out->msg = "No record id";
        
        return $out;        
    }

    $sql = "DELETE FROM {$rsc} WHERE id='".$link->real_escape_string($id)."'";
    $link->query($sql);
    $out->status = "ok";
    $out->affected_rows = $link->affected_rows;
    $out->msg = "Remove {$rsc} record [{$id}]";

    return $out;
}
