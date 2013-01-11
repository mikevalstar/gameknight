DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_pk` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(32) NOT NULL,
  `passkey` varchar(32) NULL,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_when` datetime NOT NULL,
  `deleted_by` int(11) unsigned DEFAULT NULL,
  `deleted_when` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_when` datetime NOT NULL,
  PRIMARY KEY (`user_pk`)
) ;

DROP TABLE IF EXISTS `user_login`;
CREATE TABLE `user_login` (
  `user_login_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_fk` int(11) unsigned NOT NULL,
  `when` datetime NOT NULL,
  PRIMARY KEY (`user_login_pk`)
);

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_name` varchar(250) NOT NULL,
  `min_players` int(11) NOT NULL,
  `max_players` int(11) NOT NULL,
  `min_avg_len` int(11) NOT NULL,
  `max_avg_len` int(11) NOT NULL,
  `coop` int(1) NOT NULL,
  `team` int(1) NOT NULL,
  `notes` text NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_when` datetime NOT NULL,
  `deleted_by` int(11) unsigned DEFAULT NULL,
  `deleted_when` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_when` datetime NOT NULL,
  PRIMARY KEY (`game_pk`)
);

ALTER TABLE  `game` ADD  `setup_time` INT NOT NULL AFTER  `max_players`;

DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `owner_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_fk` int(11) unsigned NOT NULL,
  `game_fk` int(11) unsigned NOT NULL,
  PRIMARY KEY (`owner_pk`)
);

ALTER TABLE  `game` ADD  `preorder` INT( 1 ) NOT NULL AFTER  `notes` ,
ADD  `owners_txt` VARCHAR( 300 ) NOT NULL AFTER  `preorder` ,
ADD  `owners_count` INT( 3 ) NOT NULL AFTER  `owners_txt`;

ALTER TABLE  `game` ADD  `parent_fk` INT( 11 ) UNSIGNED NULL DEFAULT NULL AFTER  `game_pk`;

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `event_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_name` varchar(250) NOT NULL,
  `event_text` text NOT NULL,
  `event_start` datetime NOT NULL,
  `event_end` datetime DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_when` datetime NOT NULL,
  `deleted_by` int(11) unsigned DEFAULT NULL,
  `deleted_when` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_when` datetime NOT NULL,
  PRIMARY KEY (`event_pk`)
);

ALTER TABLE  `event` ADD  `participants` INT( 4 ) NOT NULL DEFAULT  '0' AFTER  `event_end` ,
ADD  `invite_sent` INT( 1 ) NOT NULL DEFAULT  '0' AFTER  `participants`;

DROP TABLE IF EXISTS `event_participant`;
CREATE TABLE `event_participant` (
  `event_fk` int(11) unsigned NOT NULL,
  `user_fk` int(11) unsigned NOT NULL,
  `response` varchar(100) NOT NULL,
  UNIQUE KEY `event_fk` (`event_fk`,`user_fk`)
);

DROP TABLE IF EXISTS `event_game_vote`;
CREATE TABLE `event_game_vote` (
  `event_fk` int(11) unsigned NOT NULL,
  `user_fk` int(11) unsigned NOT NULL,
  `game_fk` int(11) unsigned NOT NULL,
  UNIQUE KEY `game_fk` (`event_fk`,`user_fk`,`game_fk`)
);

ALTER TABLE  `game` ADD  `bgg_id` INT( 11 ) NOT NULL AFTER  `parent_fk`;
ALTER TABLE  `game` ADD  `bgg_data` text NULL AFTER  `bgg_id`;
ALTER TABLE  `game` ADD  `bgg_rating` DOUBLE( 6, 3 ) NOT NULL DEFAULT  '0' AFTER  `bgg_id`;
ALTER TABLE  `event` ADD  `location` VARCHAR( 250 ) NOT NULL AFTER  `event_name`;

ALTER TABLE  `game` ADD  `scoring_type` INT( 2 ) NOT NULL DEFAULT  '1' AFTER  `team` ,
ADD  `game_weight` DOUBLE( 6, 3 ) NOT NULL DEFAULT  '1' AFTER  `scoring_type`;

DROP TABLE IF EXISTS `play`;
CREATE TABLE `play` (
  `play_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_fk` int(11) unsigned NOT NULL,
  `playtime` int(5) NOT NULL,
  `started` datetime NOT NULL,
  `ranked` int(1) NOT NULL DEFAULT '0',
  `created_by` int(11) unsigned NOT NULL,
  `created_when` datetime NOT NULL,
  `deleted_by` int(11) unsigned DEFAULT NULL,
  `deleted_when` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_when` datetime NOT NULL,
  PRIMARY KEY (`play_pk`)
);

DROP TABLE IF EXISTS `play_player`;
CREATE TABLE `play_player` (
  `play_player_pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `play_fk` int(11) unsigned NOT NULL,
  `user_fk` int(11) unsigned NOT NULL,
  `score` double(10,2) NOT NULL,
  `win` int(1) NOT NULL,
  `rank_change` double(6,2) NOT NULL,
  PRIMARY KEY (`play_player_pk`)
);

ALTER TABLE  `user` ADD  `ranking` INT( 8 ) NOT NULL DEFAULT  '2000' AFTER  `name_last`;

ALTER TABLE  `play_player` ADD UNIQUE (
`play_fk` ,
`user_fk`
);