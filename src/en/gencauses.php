#!/usr/local/bin/php
<?php

$campaigns = json_decode(file_get_contents("https://app.thegivehub.com/api/campaign"));

$causewrap = file_get_contents("components/causes-template.html");

$tpl = <<<EOT
                    <div class="causes-item">
                        <div class="causes-img">
                            <img src="/site/img/causes-%%cnt%%.jpg" alt="Image">
                        </div>
                        <div class="causes-progress">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="%%progress%%" aria-valuemin="0" aria-valuemax="100">
                                    <span>%%progress%%%</span>
                                </div>
                            </div>
                            <div class="progress-text">
                                <p><strong>Raised:</strong> $%%raised%%</p>
                                <p><strong>Goal:</strong> $%%goal%%</p>
                            </div>
                        </div>
                        <div class="causes-text">
                            <h3>%%title%%</h3>
                            <p>%%description%%</p>
                        </div>
                        <div class="causes-btn">
                            <a class="btn btn-custom" href="https://app.thegivehub.com/campaign.html?id=%%id%%">Learn More</a>
                            <a class="btn btn-custom" hfre="https://app.thegivehub.com/donatenow.html?id=%%id%%">Donate Now</a>
                        </div>
                    </div>
EOT;

$out = <<<EOT
        <!-- Causes Start -->
        <div class="causes">
            <div class="container">
                <div class="section-header text-center">
                    <p>Popular Causes</p>
                    <h2>Here are some of the most popular charity causes around the world.</h2>
                </div>
                <div class="owl-carousel causes-carousel">
EOT;

for ($i=0; $i<5; $i++) {
    $obj = $campaigns[$i];
    
    $newobj = new stdClass();

    $newobj->title = $obj->title;
    $newobj->description = $obj->description;
    $newobj->goal = $obj->funding->goalAmount;
    $newobj->raised = $obj->funding->raisedAmount;
    print $newobj->raised . " / " .$newobj->goal."\n";
    $newobj->cnt = $i;
    $newobj->progress = floor(($newobj->raised / $newobj->goal) * 100);
    $newobj->id = $obj->_id;

    $out .= preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $newobj;
        if (isset($newobj->{$m[1]})) {
            return $newobj->{$m[1]};
        } else {
            return "";
        }
    }, $tpl);
}


$out .= <<<EOT
                </div>
            </div>
        </div>
        <!-- Causes End -->
EOT;

file_put_contents("components/causes.html", $out);


//print $out;
