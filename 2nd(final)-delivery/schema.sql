
--FAZER OS DROP TABLES PARA TER A CERTEZA QUE
--ESTA TUDO VAZIO ?

DROP TABLE camara CASCADE;
DROP TABLE video CASCADE;
DROP TABLE segmento_video CASCADE;
DROP TABLE local CASCADE;
DROP TABLE vigia CASCADE;
DROP TABLE evento_emergencia CASCADE;
DROP TABLE processo_soccoro CASCADE;
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


----------------------------------------
-- Table Creation
----------------------------------------

--TODO: ->ver se e preciso not null em tudo
--		  msm nas chaves
--      ->escrever socorro bem
--      ->dar update na tabela evento_emergencia
--      ->
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

create table segmento_video( -- AAAA
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

create table evento_emergencia( -- AAAA
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

create table processo_socorro(
	num_processo_socorro integer,-- check(num_processo_socorro in (SELECT num_processo_socorro FROM evento_emergencia)),
	--RI:todo o processo de socorro esta assoc
	--iado a um ou mais eventos (ver check)
	constraint pk_processo_socorro primary key(num_processo_socorro)
);



create table entidade_meio(
	nome_entidade varchar(30) not null,
	constraint pk_entidade_meio primary key(nome_entidade)
);
--nome_meio e nome_entidade pode ser varchar(30)?
create table meio(
	num_meio integer not null ,
	nome_meio varchar(30) not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio primary key(num_meio,nome_entidade),
	constraint fk_meio foreign key(nome_entidade) 
		references entidade_meio(nome_entidade)
);

create table meio_combate( --AAAA
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_combate primary key(num_meio,nome_entidade),
	constraint fk_meio_combate_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);

create table meio_apoio( --AAAA
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_apoio primary key(num_meio,nome_entidade),
	constraint fk_meio_apoio_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);

create table meio_socorro( --AAAA
	num_meio integer not null,
	nome_entidade varchar(30) not null,
	constraint pk_meio_socorro primary key(num_meio,nome_entidade),
	constraint fk_meio_socorro_meio foreign key(num_meio,nome_entidade)
		references meio(num_meio,nome_entidade)
);
--aqui o num_processo_socorro pode ser null?
create table transporta( --AAAA
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

create table alocado( --AAAA
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

create table acciona( --AAAA
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
	--TODO verificar os checks!!!!!
);

create table solicita( --AAAA
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