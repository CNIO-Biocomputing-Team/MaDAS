CREATE TABLE `genes` (
  `IDX` int(11) NOT NULL default '0' COMMENT 'Gene index',
  `ID` varchar(30) NOT NULL default '' COMMENT 'Unique sequence ID',
  `TID` varchar(30) NOT NULL default '' COMMENT 'Transcript ID',
  `TVER` tinyint(3) unsigned NOT NULL default '0' COMMENT 'Version of TID',
  `GID` varchar(30) NOT NULL default '' COMMENT 'Gene ID',
  `GVER` tinyint(3) unsigned NOT NULL default '0' COMMENT 'Version of GID',
  `SYMBOL` varchar(30) NOT NULL default '' COMMENT 'gene symbol',
  `DISP_ID` varchar(45) NOT NULL default '' COMMENT 'display name (obsolete)',
  `TAX_ID` int(10) unsigned NOT NULL default '0' COMMENT 'Taxonomy ID',
  `DESC` text NOT NULL COMMENT 'Description',
  PRIMARY KEY  (`IDX`),
  UNIQUE KEY `ID` (`ID`),
  KEY `GID` (`GID`,`GVER`),
  KEY `TID` (`TID`,`TVER`),
  KEY `TAX_ID` (`TAX_ID`),
  FULLTEXT KEY `DESC` (`DESC`,`SYMBOL`,`TID`,`GID`,`DISP_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='gene list'
