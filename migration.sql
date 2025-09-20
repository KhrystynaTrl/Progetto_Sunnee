CREATE DATABASE Sunnee;
USE Sunnee;

CREATE TABLE IF NOT EXISTS Product(
ID INT auto_increment PRIMARY KEY,
`name` varchar(25) NOT NULL UNIQUE,
kg_recycled float(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `Order`(
ID INT auto_increment PRIMARY KEY,
date_of_sale date DEFAULT (now()),
quantity int check (quantity > 0) NOT NULL,
product int NOT NULL, 
foreign key (product) references Product(ID)
);


