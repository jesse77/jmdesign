CREATE TABLE merchandise (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       `name`		VARCHAR(50)	DEFAULT NULL,
       link		VARCHAR(50)	NOT NULL,
       comment		TEXT		DEFAULT NULL,
       timestamp	DATETIME	DEFAULT CURRENT_TIMESTAMP
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
