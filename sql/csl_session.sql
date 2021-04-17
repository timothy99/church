create table csl_session (
	id varchar(128) collate utf8_unicode_ci not null,
	ip_address varchar(45) collate utf8_unicode_ci not null,
	timestamp int(10) unsigned not null default 0,
	data blob not null,
	key ci_sessions_timestamp (timestamp)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;
