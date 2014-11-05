
pwd			= $(shell pwd)

default: build configure

build: 
	sudo apt-get update;					\
	sudo apt-get install					\
		apache2 libapache2-mod-php5 apache2-utils;

configure:
	echo "Set apache password for 'jesse' user:";		\
	htpasswd -c /server/jmdesign/.htpasswd jesse;		\
	echo "Set apache password for 'webadmin' user:";	\
	htpasswd -c /server/jmdesign/.htpasswd webadmin;
