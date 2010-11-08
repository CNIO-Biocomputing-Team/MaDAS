#!/bin/bash

#parameters
pfile_name=$1
dsn=$2
mtype=$3
annot=$4

#insert data 
/usr/bin/wget 'http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_load-array-express/parseFile.php?file='$pfile_name'&dsn='$dsn'&mtype='$mtype'&annot='$annot

exit