#!/bin/bash

#parameters
pfile_url=$1
pfile_name=$2
pdsn=$3
pmtype=$4
to=$5



#download
/sw/bin/wget $pfile_url -O $pfile_name 

#untar
./untar_file.sh $pfile_name $pfile_name"_1"
rm $pfile_name



#create sql file
awk -f ./create_sql.awk dsn=$pdsn mtype=$pmtype $pfile_name"_1" >$pfile_name".sql" 

#connect to database
/usr/local/mysql/bin/mysql -h 127.0.01 -u startver_madas -pmadas  startver_madas < $pfile_name".sql"

#remove tmp files
rm $pfile_name*

#send email
mail -s 'Your reference has been uploaded' $to < mail_fasta_inserted.txt
exit

