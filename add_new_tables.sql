DROP TABLE IF EXISTS mediums;

CREATE TABLE mediums (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       `name`		VARCHAR(50)	NOT NULL,
       price		INTEGER		NOT NULL,
       shipping		INTEGER		DEFAULT 0
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
       id		INTEGER		PRIMARY KEY AUTOINCREMENT,
       shipped		BOOLEAN		DEFAULT 0,
       shipping_json	TEXT		NOT NULL,
       cart_json	TEXT		NOT NULL,
       comments		TEXT		DEFAULT NULL
);
