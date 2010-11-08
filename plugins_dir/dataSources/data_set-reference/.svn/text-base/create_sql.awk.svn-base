BEGIN {
    FS="\n"
    RS=">"
}
{ 
   print "INSERT INTO das_commonserver_segments SET iddas_commonserver_dsns = "dsn",idmolecule_types="mtype",sname='"$1"',ssequence='"

    x=2 
    while ( x<NF ) { 
        print $x 
        x++ 
    } 
   
 print "',sstart=1,sstop="(x-1)*60",sversion=1,screated=now();\n"
}
