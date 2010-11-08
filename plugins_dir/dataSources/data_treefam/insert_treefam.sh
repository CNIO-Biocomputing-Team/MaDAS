#!/bin/bash

#parameters
pgenefile_url=$1
pgenefile_name=$2
ptreefile_url=$3
ptreefile_name=$4
to=$5



#download
#wget $pgenefile_url -O tmp/$pgenefile_name
#wget $ptreefile_url -O tmp/$ptreefile_name 


#chdir
#cd tmp/

#untar
#gunzip -S "" -c --stdout $pgenefile_name > $pgenefile_name"_1"
#rm $pgenefile_name

#tar -zxvf $ptreefile_name 
#rm $ptreefile_name

#insert data into temp tables
#echo "LOAD DATA LOCAL INFILE '"$pgenefile_name"_1' INTO TABLE datasource_treefam_genes" |  mysql -h jabba -u startver_madas -pmadas  startver_madas

#chdir
#cd ../


#SPECIES TREE
#wget http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_treefam/load-species-tree.php


#TREES
#convert trees to XML
#find tmp/families_work/ -iname clean.nhx -exec java -Xmn100M -Xms500M -Xmx500M -cp forester.jar org.forester.application.phyloxml_converter -f=tc {} {}.xml \;

#update gene path
find tmp/families_work/ -iname clean.nhx.xml -exec  wget http://localhost/madas/plugins_dir/dataSources/data_treefam/load-trees.php?file={} -O {}.html \;


#remove tmp files
#rm tmp/$pgenefile_name"_1"


#send email
#mail -s 'Your file has been uploaded' $to < mail_treefam_inserted.txt
exit
