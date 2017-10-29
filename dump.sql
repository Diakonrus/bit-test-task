-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.37-log - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица test.finances
CREATE TABLE IF NOT EXISTS `finances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `sum` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency_id` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_finances_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test.finances: ~3 rows (приблизительно)
DELETE FROM `finances`;
/*!40000 ALTER TABLE `finances` DISABLE KEYS */;
INSERT INTO `finances` (`id`, `user_id`, `sum`, `currency_id`, `created_at`) VALUES
	(1, 1, 23500.00, 0, '2017-10-26 11:37:57'),
	(2, 1, 100.00, 1, '2017-10-26 11:37:57'),
	(3, 1, 50.00, 2, '2017-10-26 11:37:57');
/*!40000 ALTER TABLE `finances` ENABLE KEYS */;

-- Дамп структуры для таблица test.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `password` varchar(550) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test.users: ~1 rows (приблизительно)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`, `created_at`) VALUES
	(1, 'spn@mail.ru', 'e4d1b9371b671c84cd8b2ba7b696a08d', '2017-10-25 20:32:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для триггер test.upd_finances_sum
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `upd_finances_sum` BEFORE UPDATE ON `finances` FOR EACH ROW BEGIN
           IF NEW.sum < 0 THEN
						SIGNAL SQLSTATE '45000'
						SET MESSAGE_TEXT = 'The amount can not be written off; more current amount on the account; table `{SCHEMA_NAME}`.`finances`';
					 END IF;
       END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дамп структуры для триггер test.upd_finances_sum_after
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `upd_finances_sum_after` AFTER UPDATE ON `finances` FOR EACH ROW BEGIN
           IF OLD.sum < 0 THEN
						SIGNAL SQLSTATE '45000'
						SET MESSAGE_TEXT = 'The amount can not be written off; more current amount on the account; table `{SCHEMA_NAME}`.`finances`';
					 END IF;
       END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
