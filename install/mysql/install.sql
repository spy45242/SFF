create table if not exists seo_for_filter 
(
	filter_id INT(99) NOT NULL primary key AUTO_INCREMENT
,	url VARCHAR(255) NOT NULL UNIQUE
,	keywords VARCHAR(255)
,	description VARCHAR(255)
,	title VARCHAR(255)
,	hone VARCHAR(255)
,   robots VARCHAR(255)
);