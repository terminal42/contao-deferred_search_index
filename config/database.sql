-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_dsi`
-- 

CREATE TABLE `tl_dsi` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` text NULL,
  `lastIndex` char(1) NOT NULL default '',
  KEY `lastIndex` (`lastIndex`)
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
