CREATE TABLE photos (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       active		BOOLEAN		DEFAULT 1,
       title		VARCHAR(50)	DEFAULT NULL,
       comment		TEXT		DEFAULT NULL,
       timestamp	DATETIME	DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE photo_has_tag (
       photo_id		INT,
       tag_id		INT,
       PRIMARY KEY (photo_id, tag_id)
);

CREATE TABLE tags (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       `name`		VARCHAR(50)	NOT NULL,
       active		BOOLEAN		DEFAULT 1
);

CREATE TABLE customers (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       `name`		VARCHAR(50)	NOT NULL,
       email		VARCHAR(50)	NOT NULL,
       address		TEXT		DEFAULT NULL
);

CREATE TABLE orders (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       customer_id	INTEGER		NOT NULL,
       merchandise_id	INTEGER		NOT NULL,
       ship_to		TEXT		NOT NULL,
       comments		TEXT		DEFAULT NULL,
       FOREIGN KEY( customer_id )	REFERENCES customers( id ),
       FOREIGN KEY( merchandise_id )	REFERENCES merchandise( id )
);
