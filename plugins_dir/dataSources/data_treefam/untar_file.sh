#!/bin/bash

output=$2

# Test for correct file type.
TYPE=`eval file $1 | awk '{ print $2 }'`


echo $TYPE
case $TYPE in
[gG]zip)
gunzip -S "" -c --stdout "${1}" >$output
;;
[gG]z)
gunzip -S "" -c --stdout "${1}" >$output
;;
[bB]zip)
tar -jxvf "${1}" >$output
;;
[bB]z2)
tar -jxvf "${1}" >$output
;;
[tT]ar)
tar -xpvf "${1}" >$output
;;
*)
echo "Not a compressed File!"
mv "${1}" $output
;;
esac
exit 0

