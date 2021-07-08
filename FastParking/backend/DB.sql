create database estacionamentoApi;

use estacionamentoApi;

create table tblCadastro (
	    idCadastro int not null auto_increment primary key,
    nomeCliente varchar(45) not null,
    dataSaida date,
    dataEntrada date not null,
    horaEntrada time not null,
    horaSaida time not null,
    placa varchar(9) not null,
        idPreco int not null,
        foreign key (idPreco)
    references tblPreco(idPreco)
);

create table tblPreco(
	    idPreco int not null auto_increment primary key,
    precoPorHora double not null,
    precoInicial double not null,
    preco not null,
    precoAdicionalPorHora double,

    unique key(idPreco)
);