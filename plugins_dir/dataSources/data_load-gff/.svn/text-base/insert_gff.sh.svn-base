#!/bin/bash

#parameters
premotefile_url=$1
premotefile_name=$2
dsn=$3
mtype=$4
annot=$5
to=$6



#download
wget $premotefile_url -O tmp/$premotefile_name

#untar
./untar_file.sh tmp/$premotefile_name "tmp/"$premotefile_name"_1"
rm tmp/$premotefile_name


#insert data 
wget 'http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_load-gff/parseFile.php?file=tmp/'$premotefile_name'_1&dsn='$dsn'&mtype='$mtype'&annot='$annot




#send email
mail -s 'Your file has been uploaded' $to < mail_gff_inserted.txt


rm tmp/$premotefile_name"_1"

exit

