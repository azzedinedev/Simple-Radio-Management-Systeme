
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `rms`
--

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `PatientId` int(10) unsigned NOT NULL auto_increment,
  `PatientName` varchar(255) collate utf8_unicode_ci default NULL,
  `PatientBirthday` date default NULL,
  `PatientAge` int(10) unsigned default NULL,
  `PatientDescription` text collate utf8_unicode_ci,
  PRIMARY KEY  (`PatientId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `radiorequests`
--

CREATE TABLE IF NOT EXISTS `radiorequests` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `RequestUserId` int(10) unsigned NOT NULL default '0',
  `PatientId` int(10) unsigned NOT NULL default '0',
  `RequestDateTime` datetime default NULL,
  `RequestDescription` text collate utf8_unicode_ci,
  `Status` enum('demande','encours','reponse','annule') collate utf8_unicode_ci NOT NULL default 'demande',
  `UpdateID` int(11) NOT NULL default '0',
  `UpdateIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `UpdateTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `InsertID` int(11) NOT NULL default '0',
  `InsertIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `InsertTime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`Id`),
  KEY `radiorequsets_FKIndex1` (`PatientId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `radioresponse`
--

CREATE TABLE IF NOT EXISTS `radioresponse` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `ResponseUserId` int(10) unsigned NOT NULL default '0',
  `RequestId` int(10) unsigned NOT NULL default '0',
  `ResponseImage` varchar(255) collate utf8_unicode_ci default NULL,
  `ResponseDateTime` datetime default NULL,
  `ResponseDescription` text collate utf8_unicode_ci,
  `Conclusion` text collate utf8_unicode_ci,
  `Type` varchar(255) character set latin1 NOT NULL default '',
  `Hauteur` double NOT NULL default '0',
  `Largeur` double NOT NULL default '0',
  `Resolution` double NOT NULL default '0',
  `Status` enum('demande','encours','reponse','annule') collate utf8_unicode_ci NOT NULL default 'demande',
  `UpdateID` int(11) NOT NULL default '0',
  `UpdateIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `UpdateTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `InsertID` int(11) NOT NULL default '0',
  `InsertIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `InsertTime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`Id`),
  KEY `radiorequsets_FKIndex1` (`RequestId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `radioresponsegallery`
--

CREATE TABLE IF NOT EXISTS `radioresponsegallery` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `ResponseId` int(10) unsigned NOT NULL default '0',
  `ResponseGalleryImage` varchar(255) collate utf8_unicode_ci default NULL,
  `ResponseGalleryDescription` varchar(255) collate utf8_unicode_ci default NULL,
  `UpdateID` int(11) NOT NULL default '0',
  `UpdateIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `UpdateTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `InsertID` int(11) NOT NULL default '0',
  `InsertIP` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `InsertTime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `radiotypes`
--

CREATE TABLE IF NOT EXISTS `radiotypes` (
  `Id` int(11) NOT NULL auto_increment,
  `Type` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Height` double NOT NULL default '0',
  `Width` double NOT NULL default '0',
  `Resolution` double NOT NULL default '0',
  `Description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`Id`),
  UNIQUE KEY `Type` (`Type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `requestusers`
--

CREATE TABLE IF NOT EXISTS `requestusers` (
  `RequestUserId` int(10) unsigned NOT NULL auto_increment,
  `UserId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`RequestUserId`),
  KEY `requestusers_FKIndex1` (`UserId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `responseusers`
--

CREATE TABLE IF NOT EXISTS `responseusers` (
  `responseuserId` int(10) unsigned NOT NULL auto_increment,
  `UserId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`responseuserId`),
  KEY `responseusers_FKIndex1` (`UserId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `Id` int(11) NOT NULL auto_increment,
  `Name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`Id`),
  UNIQUE KEY `Nom` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserId` int(10) unsigned NOT NULL auto_increment,
  `UserName` varchar(100) collate utf8_unicode_ci default NULL,
  `UserPassword` varchar(255) collate utf8_unicode_ci default NULL,
  `UserFullName` text collate utf8_unicode_ci,
  `Mail` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Token` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `UserSecretQestion` varchar(255) collate utf8_unicode_ci default NULL,
  `UserSecretAnswer` varchar(255) collate utf8_unicode_ci default NULL,
  `UserStatus` enum('0','1','2') collate utf8_unicode_ci default '1',
  `UserDescription` text collate utf8_unicode_ci,
  `Roles` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`UserId`),
  KEY `users_usernameunic` (`UserName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;
