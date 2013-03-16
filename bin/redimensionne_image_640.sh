#!/bin/bash

for I in echo $*
do
    convert -resize 640x480! $I 640.$I
done

echo 'done'
exit 0