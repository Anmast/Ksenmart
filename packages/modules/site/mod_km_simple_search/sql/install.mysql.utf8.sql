UPDATE 
	`#__modules` 
SET 
	`title` = 'Простой поиск', 
	`position` = 'content_top', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_simple_search'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_simple_search'), 
	'0'
);