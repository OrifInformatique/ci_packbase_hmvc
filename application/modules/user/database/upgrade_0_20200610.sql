--
-- Add email to `user`
--
ALTER TABLE `user` ADD `email` VARCHAR(45) NULL DEFAULT NULL AFTER `password`;