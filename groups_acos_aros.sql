-- Schema-only ACL bootstrap tables for pvers
-- Order: groups, acos, aros

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `groups` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'admin', NULL, NULL, NULL),
(2, 'manager', NULL, NULL, NULL),
(3, 'reporter', NULL, NULL, NULL),
(4, 'partner', NULL, NULL, NULL),
(5, 'reviewer', NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `created` = VALUES(`created`),
  `modified` = VALUES(`modified`);

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Group', 1, 'admin', 1, 2),
(2, NULL, 'Group', 2, 'manager', 3, 4),
(3, NULL, 'Group', 3, 'reporter', 5, 6),
(4, NULL, 'Group', 4, 'partner', 7, 8),
(5, NULL, 'Group', 5, 'reviewer', 9, 10)
ON DUPLICATE KEY UPDATE
  `parent_id` = VALUES(`parent_id`),
  `model` = VALUES(`model`),
  `foreign_key` = VALUES(`foreign_key`),
  `alias` = VALUES(`alias`),
  `lft` = VALUES(`lft`),
  `rght` = VALUES(`rght`);
