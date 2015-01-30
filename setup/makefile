#!/usr/bin/make -s
SHELL := bash

cc_red		= "\033[0;31m"
cc_green	= "\033[0;32m"
cc_yellow	= "\033[0;33m"
cc_blue		= "\033[0;34m"
cc_pink		= "\033[0;35m"
cc_light_blue	= "\033[0;36m"
cc_white	= "\033[0;37m"
cc_normal	= "\033[m\017"

timestamp	= $(shell date +"%Y-%m-%d_%H:%M:%S")

all: help

help:
	@echo -e ${cc_white}
	@echo	"	all (default)	- Display this help text"
	@echo	"	apache_config	- Setup apache2 site configurations"
	@echo	"	build		- Installs necessary packages"
	@echo	"	codeigniter	- Sets up codeigniter config file"
	@echo	"	database	- Build SQLite database and tables"
	@echo	"	export_database	- exports the database and names it with a timestamp"
	@echo	"	setup		- Runs all the setup procedures"
	@echo	"	server_users	- Setup apache2 server users"
	@echo -e ${cc_normal}

setup: build server_file apache_config server_users database

build:
	@echo -e ${cc_white} "Installing necessary packages" ${cc_normal};
	sudo apt-get update;
	sudo apt-get install	apache2 libapache2-mod-php5 php5-curl \
				apache2-utils php5-sqlite sqlite3 libsqlite3-dev;

codeigniter:
	echo -e ${cc_white} "Setting up codeigniter file." ${cc_normal};		\
	cp ./config.php ../application/config/

apache_config: server_users
	if [ ! -e /etc/apache2/sites-available/jmdesign.conf ]; then						\
		echo -e ${cc_white} "Moving jmdesign.conf to apache sites folder." ${cc_normal};		\
		echo -e ${cc_pink} "Don't forget to configure it correctly and enable it!" ${cc_normal};	\
		sudo cp ./jmdesign.conf /etc/apache2/sites-available/jmdesign.conf;				\
	fi;

server_users:
	@echo -e ${cc_yellow}"Set apache password for 'jesse' user:" ${cc_normal};
	@htpasswd -c ../.htpasswd jesse;
	@echo -e ${cc_normal};
	@echo -e ${cc_yellow}"Set apache password for 'admin' user:" ${cc_normal};
	@htpasswd ../.htpasswd admin;
	@echo -e ${cc_normal}

database: export_database
	echo -e ${cc_white} "	Removing old database" ${cc_normal};		\
	rm ../db/database.sql;							\
	echo -e ${cc_white} "Building new SQLite database" ${cc_normal};	\
	sqlite3 ../db/database.sql < tables.sql;				\
	chmod 766 ../db/database.sql;						\
	echo -e ${cc_blue} "	Saved '../db/database.sql'." ${cc_normal};

export_database:
	mkdir -p ../db;								\
	if [ -e ../db/database.sql ]; then					\
		echo -e ${cc_white} "Exporting current database to ../db/database-"${timestamp}".sql" ${cc_normal}; \
		cp ../db/database.sql ../db/database-${timestamp}.sql; \
	fi;
