DROP TABLE IF EXISTS 'raw_logs';
CREATE TABLE 'raw_logs' (
	'key' INTEGER PRIMARY KEY AUTOINCREMENT,
	'v' varchar(1) NOT NULL,
	'session' varchar(15) NOT NULL,
	'id' varchar(32) NOT NULL,
	'time' varchar(15) NOT NULL
);
