#!/bin/bash

cd blog
./genblog.php

cd ..
./build-site

cp -r newsite/* ..


