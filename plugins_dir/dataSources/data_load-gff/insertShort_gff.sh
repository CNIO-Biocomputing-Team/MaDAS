#!/bin/bash

#parameters
pfile_name=$1
dsn=$2
mtype=$3
annot=$4

#mv file
mv '../../../libs/FlashUploader_102/uploads/'$pfile_name 'tmp/'$pfile_name


#insert data 
wget 'http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_load-gff/parseFile.php?file=tmp/'$pfile_name'&dsn='$dsn'&mtype='$mtype'&annot='$annot

rm 'tmp/'$pfile_name

exit

