-- drop database rota_paciente;
create database rota_paciente
default character set utf8mb4
default collate utf8mb4_general_ci;

use rota_paciente;

create table unidades(
unidade_id int not null auto_increment primary key,
nome varchar(60) not null,
tipo enum('UBS','ESPECIALIZACAO','HOSPITAL') not null
)default charset = utf8mb4 ,engine= InnoDB;

create table pacientes(
id_paciente int not null auto_increment primary key,
nome varchar(60) not null,
cns char(15) not null,
nascimento date not null,
telefone varchar(60),
unidade_fk int not null,
foreign key(unidade_fk) references unidades(unidade_id)
)default charset = utf8mb4 ,engine= InnoDB;

create table usuarios(
usuario_id int not null auto_increment primary key,
nome varchar(60) not null,
email varchar(100) not null,
tipo_funcionario enum('OBS','GESTOR','HOSPITAL','ESPECIALIZACAO') not null,
unidade_fk int not null,
senha_hash varchar(255),

foreign key(unidade_fk) references unidades(unidade_id)
)default charset = utf8mb4 ,engine= InnoDB;

create table especialidades(
especialidade_id int not null auto_increment primary key,
nome varchar(60) not null,
sla_dias int not null
)default charset = utf8mb4 ,engine= InnoDB;

create table checklist_itens(
checklist_item_id int not null auto_increment primary key,
especialidade_fk int not null,
descricao TINYTEXT not null,
obrigatorio boolean not null
)default charset = utf8mb4 ,engine= InnoDB;

create table encaminhamentos(
encaminhamento_id int not null auto_increment primary key,
codigo varchar(60) not null,
paciente_fk int not null,
origem_fk int not null,
destino_fk int not null,
especialidade_fk int not null,
prioridade enum('verde', 'amarelo', 'vermelho') not null,
statuss varchar(60) not null,
criado_em DATETIME,

foreign key(paciente_fk) references pacientes(id_paciente)

)default charset = utf8mb4 ,engine= InnoDB;

create table eventos(
evento_id int not null auto_increment primary key,
encaminhamento_fk int not null,
etapa varchar(60) not null,
usuario_fk int not null,
observacao TINYTEXT,
criado_em date not null,

foreign key(encaminhamento_fk) references encaminhamentos(encaminhamento_id),
foreign key(usuario_fk) references usuarios(usuario_id)
)default charset = utf8mb4 ,engine= InnoDB;

create table contrareferencias(
constrareferencia_id int not null auto_increment primary key,
encaminhamento_fk int not null,
diagnostico text,
consuta text,
seguimento_ubs int not null,
precisa_retorno boolean not null,

foreign key(encaminhamento_fk) references encaminhamentos(encaminhamento_id)
)default charset = utf8mb4 ,engine= InnoDB;


create table altas(
alta_id int not null auto_increment primary key,
paciente_fk int not null,
data_alta DATE not null,
resumo text not null,
unidade_retorno_id int,
prazo_retorno date,

foreign key(paciente_fk) references pacientes(id_paciente)
)default charset = utf8mb4 ,engine= InnoDB;



-- create table xx(
-- xx int not null auto_increment primary key,
-- )default charset = utf8mb4 ,engine= InnoDB;