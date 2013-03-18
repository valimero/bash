#!/bin/bash

for I in echo $*
do
    convert -scale 1024 $I 1024.$I
done

echo 'done'
exit 0