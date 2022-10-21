CREATE DATABASE IF NOT EXISTS alfarim;
USE alfarim;

CREATE TABLE users(
id                  int(255) auto_increment not null,
name                varchar(50) NOT NULL,
surname             varchar(50),
role                varchar(20),
email               varchar(20) NOT NULL,
password            varchar(255) NOT NULL,
description         text,
image               varchar(255),
create_at           datetime DEFAULT NULL,
update_up           datetime DEFAULT NULL,
remenber_token      varchar(255),
CONSTRAINT  pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE categories(
id                  int(255) auto_increment not null,
name                varchar(255)  NOT NULL,
create_at           datetime DEFAULT NULL,
update_up           datetime DEFAULT NULL,
CONSTRAINT  pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE sub_categories(
id                  int(255) auto_increment not null,
name                varchar(50)  NOT NULL,
category_id         int(255) NOT NULL,
create_at           datetime DEFAULT NULL,
update_up           datetime DEFAULT NULL,
CONSTRAINT  pk_users PRIMARY KEY(id),
CONSTRAINT  fk_post_category FOREIGN KEY(category_id) REFERENCES categories(id)
)ENGINE=InnoDb;

CREATE TABLE posts(
id                  int(255) auto_increment not null,
sub_category_id     int(255) NOT NULL,
title               varchar(100) NOT NULL,
code                varchar(25),
measure             varchar(25),
weight              int(255),
price               int(25),
content             text,
image               varchar(255),
create_at           datetime DEFAULT NULL,
update_up           datetime DEFAULT NULL,
CONSTRAINT  pk_users PRIMARY KEY(id),
CONSTRAINT  fk_post_sub_category FOREIGN KEY(sub_category_id) REFERENCES sub_categories(id)
)ENGINE=InnoDb;