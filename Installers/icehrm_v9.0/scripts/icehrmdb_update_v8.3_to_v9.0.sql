ALTER TABLE  `Modules` ADD  `icon` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `name`;
UPDATE `Settings` set value = '1' where name = 'System: Reset Modules and Permissions';