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

CREATE TABLE orders (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       shipped		BOOLEAN		DEFAULT 0,
       shipping_json	TEXT		NOT NULL,
       cart_json	TEXT		NOT NULL,
       comments		TEXT		DEFAULT NULL
);

CREATE TABLE mediums (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       `name`		VARCHAR(50)	NOT NULL,
       `price`		INTEGER		NOT NULL,
       `shipping`	INTEGER		DEFAULT 0
);
