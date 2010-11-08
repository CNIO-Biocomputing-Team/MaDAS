<?php
	###############################################################################
	#                      class.load-list.php -  description                     #
	#                             -------------------                             #   
	#    begin                : Wed Jul 25 14:18:02 CEST 2007                     # 
	#    copyright            : (C) 2007 by Victor de la Torre                    # 
	#    email                : victor@madas.es                                   # 
	###############################################################################
	
	###############################################################################
	#                                                                             #
	#   This file is part of MaDas                                                #
	#                                                                             #
	#   MaDas is free software; you can redistribute it and/or modify             #
	#   it under the terms of the GNU General Public License as published by      #
	#   the Free Software Foundation; either version 2 of the License, or         #
	#   (at your option) any later version.                                       #
	#                                                                             #
	#   MaDas is distributed in the hope that it will be useful,                  #
	#   but WITHOUT ANY WARRANTY; without even the implied warranty of            #
	#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             #
	#   GNU General Public License for more details.                              #
	#                                                                             #
	#   You should have received a copy of the GNU General Public License         #
	#   along with MaDas; if not, write to the Free Software                      #
	#   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA #
	#                                                                             #
	###############################################################################
	
class Load_list {

	// parse biomart results
	function parseBiomart($results,$dsn,$mtype,$annot){
		
		global $c;
		global $d;

		
		foreach ($results as $line){
			if (strlen($line)>5){
				
				$record = $this->parseLine(trim($line));
		
				$segment_id = $d->segmentExist($record['seqname'],$dsn);
				//new segment in the file
					if (!$segment_id){
					$segment_id = $d->storeSegment($record['seqname'],$dsn,$mtype);
				}
	
				$d->storeFeature($segment_id,$record,$annot);	
			}
		}
	}


	// parse biomart record
	function parseLine($line){
	
		if (!preg_match("/#/",$line)){

			$tmp = explode(",",$line);
			
			$record['seqname']		= @$tmp[1];
			$record['source']		= 'Biomart';
			$record['type']		    = 'Gene';
			$record['start']		= @$tmp[2];
			$record['end']			= @$tmp[3];
			$record['score']		= 0;
			$record['strand']		= (@$tmp[4]>0)?'+':'-';
			$record['frame']    	= 0;
			$record['attributes']   = 'ID='.@$tmp[5].';ENSEMBL-ID='.@$tmp[0].';';

			return $record;
		}
	}
}