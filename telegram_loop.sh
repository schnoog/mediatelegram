#!/bin/bash

i=1

while [ $i -lt 10000 ]
do

php index.php
sleep 0.1
echo "Run $i"
let i=$i+1

done
