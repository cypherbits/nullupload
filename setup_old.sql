CREATE TABLE `files` (
  `id` varchar(10) NOT NULL,
  `origName` varchar(240) DEFAULT NULL,
  `filename` varchar(64) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `uploadDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nDownloads` int(11) NOT NULL DEFAULT '0',
  `lastDownload` timestamp NULL DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `deleteDate` timestamp NOT NULL,
  `deletePassword` varchar(64) NOT NULL,
  `integrity` varchar(41) NOT NULL,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**
+----------------+--------------+------+-----+-------------------+-------------------+
| Field          | Type         | Null | Key | Default           | Extra             |
+----------------+--------------+------+-----+-------------------+-------------------+
| id             | varchar(10)  | NO   | PRI | NULL              |                   |
| origName       | varchar(240) | YES  |     | NULL              |                   |
| filename       | varchar(64)  | NO   |     | NULL              |                   |
| extension      | varchar(5)   | NO   |     | NULL              |                   |
| uploadDate     | timestamp    | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
| nDownloads     | int(11)      | NO   |     | 0                 |                   |
| lastDownload   | timestamp    | YES  |     | NULL              |                   |
| type           | varchar(64)  | YES  |     | NULL              |                   |
| password       | varchar(64)  | YES  |     | NULL              |                   |
| deleteDate     | timestamp    | NO   |     | NULL              |                   |
| deletePassword | varchar(64)  | NO   |     | NULL              |                   |
| integrity      | varchar(41)  | NO   |     | NULL              |                   |
| user           | int(11)      | YES  |     | NULL              |                   |
+----------------+--------------+------+-----+-------------------+-------------------+

 */

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(240) NOT NULL,
  `newText` text,
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/**
+--------------+--------------+------+-----+-------------------+-------------------+
| Field        | Type         | Null | Key | Default           | Extra             |
+--------------+--------------+------+-----+-------------------+-------------------+
| id           | int(11)      | NO   | PRI | NULL              | auto_increment    |
| title        | varchar(240) | NO   |     | NULL              |                   |
| newText      | text         | YES  |     | NULL              |                   |
| dateCreation | timestamp    | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
+--------------+--------------+------+-----+-------------------+-------------------+

 */