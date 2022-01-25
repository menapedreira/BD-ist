-- Grupo 60
-- 86466 : Madalena Pedreira
-- 86496 : Pedro Custodio
-- 86508 : Rita Fernandes

--1)
SELECT num_processo_socorro
FROM acciona
GROUP BY num_processo_socorro
HAVING COUNT(*) >= ALL (
SELECT COUNT(*)
FROM acciona
GROUP BY num_processo_socorro);


--2)
SELECT nome_entidade 
FROM acciona NATURAL JOIN (
	SELECT * 
	FROM evento_emergencia 
	WHERE instante_chamada BETWEEN '2018-06-21 00:00:00' AND '2018-09-23 23:59:59' ) AS t 
GROUP BY nome_entidade 
HAVING COUNT(*) >= all (
	SELECT COUNT(*) 
	FROM acciona NATURAL JOIN (
		SELECT * 
		FROM evento_emergencia 
		WHERE instante_chamada BETWEEN '2018-06-21 00:00:00' AND '2018-09-23 23:59:59') AS r
	GROUP BY nome_entidade
	);

--3)
SELECT distinct num_processo_socorro 
FROM evento_emergencia NATURAL JOIN (
	SELECT * 
	FROM acciona 
	EXCEPT 
	SELECT num_meio, nome_entidade, num_processo_socorro 
	FROM audita ) AS t
WHERE instante_chamada BETWEEN '2018-01-01 00:00:00' AND '2018-12-31 23:59:59' 
AND morada_local = 'Oliveira do Hospital';

--4)
SELECT distinct num_camara, num_segmento, data_hora_inicio, duracao  
FROM video NATURAL JOIN vigia NATURAL JOIN segmento_video 
WHERE duracao > '00:00:60' 
AND morada_local = 'Monchique' 
AND data_hora_inicio BETWEEN '2018-08-01 00:00:00' AND '2018-08-31 23:59:59' 
AND data_hora_fim BETWEEN '2018-08-01 00:00:00' AND '2018-08-31 23:59:59';

--5) assumiu-se que se querem listar meios de combate que nao foram accionados em processos de socorro
SELECT num_meio, nome_entidade 
FROM meio_combate EXCEPT SELECT num_meio,nome_entidade FROM alocado ;


--6)
SELECT nome_entidade 
FROM entidade_meio AS e 
WHERE NOT EXISTS (
	SELECT num_processo_socorro 
	FROM processo_socorro 
	EXCEPT 
	SELECT num_processo_socorro 
	FROM (acciona NATURAL JOIN meio_combate ) AS a 
	WHERE a.nome_entidade = e.nome_entidade
);

