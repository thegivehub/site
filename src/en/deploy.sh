#!/bin/bash

cd blog
./genblog.php

cd ..
./build-site

./gencauses.php

cp -r blog/html/* ../../../en/blog/
cp -r newsite/* ../../../en/


