<?php
	###############################################################################
	#                      class.das_server.php -  description                    #
	#                             -------------------                             #   
	#    begin                : Wed Jul 25 14:18:02 CEST 2007                     # 
	#    copyright            : (C) 2007 by Victor de la Torre                    # 
	#    email                : victor@madas.es                          # 
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
	
	
	class Das_server{
		
		function parse_das_url(){
		
			$parameters = array();
			$url=$_SERVER['REQUEST_URI'];
			
			preg_match('/(\/das.php\/)(.*)/',$url,$matches);
			
			//400 Bad command (command not recognized)
			if (!@$matches[2])
				return 0;	
			
				
			$durl = @explode('/',$matches[2]);
			
			//dsn command doesn't provide dsn
			if ($durl[0] == 'dsn'){

				$param['command'] = 'dsn';
				return $param;
				
			//other commands
			}else{
				
				$param['dsn'] = $durl[0];
				//command without parameters
				if (!preg_match('/\?/',$durl[1])){
					
					$param['command'] = $durl[1];
					
				//command with parameters return parameter list
				}else{
					
					$p1 = array();
					$com = @explode('?',$durl[1]);
					$param['command'] = $com[0];
					$pairs = preg_split("/[;|&]/",$com[1]);
					foreach ($pairs as $pair){
						list($k,$v) = explode('=',$pair);
						//first time parameter
						if (!array_key_exists($k,$param)){
							$param[$k] = array();
							array_push($param[$k],$v);
						//adding values to parameter list	
						}else{
							array_push($param[$k],$v);
						}	
					}	
				}
			}	
			return $param;	
		}
	}
?>