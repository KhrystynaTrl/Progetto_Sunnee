CREATE DATABASE Sunnee;
USE Sunnee;

CREATE TABLE IF NOT EXISTS prodotto(
ID INT auto_increment PRIMARY KEY,
nome varchar(25) NOT NULL UNIQUE,
kg_riciclati float(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS ordine(
ID INT auto_increment PRIMARY KEY,
data_di_vendita date DEFAULT (now()),
quantita int check (quantita > 0) NOT NULL,
prodotto int NOT NULL, 
foreign key (prodotto) references prodotto(ID)
);


 