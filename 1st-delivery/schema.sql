DROP TABLE camara CASCADE;
DROP TABLE video CASCADE;
DROP TABLE segmento_video CASCADE;
DROP TABLE local CASCADE;
DROP TABLE vigia CASCADE;
DROP TABLE evento_emergencia CASCADE;
DROP TABLE processo_socorro CASCADE;
DROP TABLE entidade_meio CASCADE;
DROP TABLE meio CASCADE;
DROP TABLE meio_socorro CASCADE;
DROP TABLE meio_apoio CASCADE;
DROP TABLE meio_combate CASCADE;
DROP TABLE transporta CASCADE;
DROP TABLE alocado CASCADE;
DROP TABLE audita CASCADE;
DROP TABLE coordenador CASCADE;
DROP TABLE acciona CASCADE;
DROP TABLE solicita CASCADE;
DROP TABLE d_evento CASCADE;
DROP TABLE d_meio CASCADE;
DROP TABLE d_tempo CASCADE;
DROP TABLE d_factos CASCADE;


----------------------------------------
-- Table Creation
----------------------------------------


create table camara(
	num_camara integer not null,
	constraint pk_camara primary key(num_camara) 
);

create table video(
	data_hora_inicio timestamp not null,
	data_hora_fim timestamp not null,
	num_camara integer not null,
	constraint pk_video primary key(data_hora_inicio,num_camara),
	constraint fk_video_camara foreign key(num_camara)
		references camara(num_camara)
);

create table segmento_video( 
	num_segmento integer not null,
	duracao interval hour to second,  
	data_hora_inicio timestamp not null,
	num_camara integer not null,
	constraint pk_segmento_video primary key(num_segmento,data_hora_inicio,num_camara),
	constraint fk_segmento_video_video foreign key(data_hora_inicio,num_camara)
		references video(data_hora_inicio,num_camara)
);

create table local(
	morada_local varchar(30) not null,
	constraint pk_local primary key(morada_local)
);

create table vigia(
	morada_local varchar(30) not null,
	num_camara integer not null,
	constraint pk_vigia primary key(morada_local,num_camara),
	constraint fk_vigia_local foreign key(morada_local) 
		references local(morada_local),
	constraint fk_vigia_camara foreign key(num_camara)
		references camara(num_camara)
);

create table processo_socorro(
	num_processo_socorro integer,
	constraint pk_processo_socorro primary key(num_processo_socorro)
);

create table evento_emergencia( 
	num_telefone varchar(15) not null,
	instante_chamada timestamp not null,
	nome_pessoa varchar(80) not null,
	morada_local varchar(30) not null,
	num_processo_socorro integer not null,
	constraint pk_evento_emergencia primary key(num_telefone,instante_chamada),
	constraint fk_evento_emergencia_local foreign key(morada_local) 
		references local(morada_local),
	constraint fk_evento_emergencia_num_processo_socorro foreign key(num_processo_socorro) 
		references processo_socorro(num_processo_socorro),
	unique(num_telefone,nome_pessoa)
);

CREATE TABLE d_evento ( 
    id_evento serial,
    num_telefone varchar(15) not null,
	instante_chamada timestamp not null,
    constraint pk_d_evento primary key(id_evento),
	constraint fk_d_evento foreign key(num_telefone, instante_chamada) 
		references evento_emergencia(num_telefone, instante_chamada),
	unique(num_telefone,instante_chamada)
);




create table entidade_meio(
	nome_entidade varchar(30) not null,
	constraint pk_entidade_meio primary key(nome_entidade)
);

create table meio(
	num_meio integer not null ,
	nome_meio varchar(30) not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio primary key(num_meio,nome_entidade),
	constraint fk_meio foreign key(nome_entidade) 
		references entidade_meio(nome_entidade),
	unique(num_meio,nome_meio,nome_entidade)
);

create table meio_combate( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_combate primary key(num_meio,nome_entidade),
	constraint fk_meio_combate_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);

create table meio_apoio( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_apoio primary key(num_meio,nome_entidade),
	constraint fk_meio_apoio_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);

create table meio_socorro( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_socorro primary key(num_meio,nome_entidade),
	constraint fk_meio_socorro_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);


CREATE TABLE d_meio ( 
    id_meio serial,
    num_meio integer not null ,
	nome_meio varchar(30) not null,
	nome_entidade varchar(30) not null,
	tipo varchar(30) not null,
    constraint pk_d_meio primary key(id_meio),
	constraint fk_d_meio foreign key(num_meio,nome_meio,nome_entidade)
		references meio(num_meio,nome_meio,nome_entidade),
	unique(num_meio,nome_meio,nome_entidade,tipo)
);

create table transporta( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	num_vitimas integer not null,
	num_processo_socorro integer,
	constraint pk_transporta primary key(num_meio,nome_entidade,num_processo_socorro),
	constraint fk_transporta_meio_socorro foreign key(num_meio,nome_entidade)
		references meio_socorro(num_meio,nome_entidade),
	constraint fk_transporta_processo_socorro foreign key(num_processo_socorro)
		references processo_socorro(num_processo_socorro)
);

create table alocado( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	num_horas integer not null,
	num_processo_socorro integer,
	constraint pk_alocado primary key(num_meio,nome_entidade,num_processo_socorro),
	constraint fk_alocado_meio_apoio foreign key(num_meio,nome_entidade)
		references meio_apoio(num_meio,nome_entidade),
	constraint fk_alocado_processo_socorro foreign key(num_processo_socorro)
		references processo_socorro(num_processo_socorro)
);

create table acciona( 
	num_meio integer not null,
	nome_entidade varchar(30) not null,	
	num_processo_socorro integer,
	constraint pk_acciona primary key(num_meio,nome_entidade,num_processo_socorro),
	constraint fk_acciona_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade),
	constraint fk_acciona_processo_socorro foreign key(num_processo_socorro)
		references processo_socorro(num_processo_socorro)
);


create table coordenador(
	id_coordenador integer not null,
	constraint pk_coordenador primary key(id_coordenador)
);

create table audita(
	id_coordenador integer not null,
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	num_processo_socorro integer,
	data_hora_inicio timestamp not null,
	data_hora_fim timestamp not null,
	data_auditoria date not null,
	texto varchar(120) not null,
	constraint pk_audita primary key(nome_entidade,num_processo_socorro,id_coordenador),
	constraint fk_audita_acciona foreign key (num_meio,nome_entidade,num_processo_socorro)
		references acciona(num_meio,nome_entidade,num_processo_socorro),
	constraint fk_audita_coordenador foreign key (id_coordenador)
		references coordenador(id_coordenador),
	check(data_hora_inicio < data_hora_fim),
	check(data_auditoria <= current_date)
);

create table solicita( 
	id_coordenador integer not null,
	data_hora_inicio timestamp not null,
	num_camara integer not null,
	data_hora_inicio_video timestamp not null,
	data_hora_fim timestamp not null,
	constraint pk_solicita primary key(data_hora_inicio_video,num_camara,id_coordenador),
	constraint fk_solicita_coordenador foreign key(id_coordenador)
		references coordenador(id_coordenador),
	constraint fk_solicita_video foreign key(data_hora_inicio_video,num_camara)
		references video(data_hora_inicio,num_camara)
);



CREATE TABLE d_tempo (  
    id_tempo serial,
    dia integer not null ,
	mes integer not null ,
	ano integer not null ,
    constraint pk_d_tempo primary key(id_tempo),
    unique(dia,mes,ano)
);


CREATE TABLE d_factos (  
    id_evento integer not null,
    id_meio integer not null,
    id_tempo integer not null,
    constraint pk_d_factos primary key(id_evento,id_meio,id_tempo),
    constraint fk_d_factos_evento foreign key(id_evento)
    	references d_evento(id_evento),
    constraint fk_d_factos_meio foreign key(id_meio)
    	references d_meio(id_meio),
    constraint fk_d_factos_tempo foreign key(id_tempo)
    	references d_tempo(id_tempo)
);