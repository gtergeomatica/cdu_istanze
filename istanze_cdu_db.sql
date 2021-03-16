-- DROP SCHEMA istanze;

CREATE SCHEMA istanze AUTHORIZATION comuneisernia;

-- DROP SCHEMA utenti;

CREATE SCHEMA utenti AUTHORIZATION comuneisernia;

-- istanze.istanze definition

-- Drop table

-- DROP TABLE istanze.istanze;

CREATE TABLE istanze.istanze (
	data_istanza timestamp(0) NOT NULL DEFAULT now(),
	id serial NOT NULL,
	doc_id varchar NOT NULL,
	id_utente int4 NOT NULL,
	ruolo varchar NULL,
	motivo varchar NULL,
	inviato bool NULL,
	file_txt varchar NULL,
	n_bolli int4 NULL,
	file_cdu varchar NULL,
	terminato bool NULL,
	data_invio timestamp(0) NULL,
	CONSTRAINT istanze_pk PRIMARY KEY (id)
);

-- istanze.dettagli_istanze definition

-- Drop table

-- DROP TABLE istanze.dettagli_istanze;

CREATE TABLE istanze.dettagli_istanze (
	id_istanza int4 NOT NULL,
	foglio varchar NOT NULL,
	mappale varchar NOT NULL,
	CONSTRAINT dettagli_istanze_pk PRIMARY KEY (mappale, foglio, id_istanza)
);


-- istanze.dettagli_istanze foreign keys

ALTER TABLE istanze.dettagli_istanze ADD CONSTRAINT dettagli_istanze_fk FOREIGN KEY (id_istanza) REFERENCES istanze.istanze(id);

-- istanze.istanze_temp definition

-- Drop table

-- DROP TABLE istanze.istanze_temp;

CREATE TABLE istanze.istanze_temp (
	"data" timestamp(0) NOT NULL DEFAULT now(),
	id_utente int4 NOT NULL,
	foglio varchar NOT NULL,
	mappale varchar NOT NULL,
	id serial NOT NULL,
	CONSTRAINT istanze_temp_pk PRIMARY KEY (id_utente, foglio, mappale)
);

-- istanze.pagamento_bollo_cdu definition

-- Drop table

-- DROP TABLE istanze.pagamento_bollo_cdu;

CREATE TABLE istanze.pagamento_bollo_cdu (
	id_istanza_bc int4 NOT NULL,
	file_bc varchar NULL,
	id_bc int4 NOT NULL DEFAULT nextval('istanze.pagamento_bollo_cdu_id_seq'::regclass)
);

-- istanze.pagamento_bollo_ist definition

-- Drop table

-- DROP TABLE istanze.pagamento_bollo_ist;

CREATE TABLE istanze.pagamento_bollo_ist (
	id_istanza_bi int4 NOT NULL,
	file_bi varchar NULL,
	id_bi int4 NOT NULL DEFAULT nextval('istanze.pagamento_bollo_ist_id_seq'::regclass)
);

-- istanze.pagamento_segreteria definition

-- Drop table

-- DROP TABLE istanze.pagamento_segreteria;

CREATE TABLE istanze.pagamento_segreteria (
	id_istanza_s int4 NOT NULL,
	file_s varchar NULL,
	id_s int4 NOT NULL DEFAULT nextval('istanze.pagamento_segreteria_id_seq'::regclass)
);

-- istanze.listino definition

-- Drop table

-- DROP TABLE istanze.listino;

CREATE TABLE istanze.listino (
	id serial NOT NULL,
	prezzo varchar NULL,
	CONSTRAINT listino_pk PRIMARY KEY (id)
);

-- utenti.utenti definition

-- Drop table

-- DROP TABLE utenti.utenti;

CREATE TABLE utenti.utenti (
	id serial NOT NULL,
	usr_login varchar(50) NOT NULL,
	usr_password varchar(120) NULL,
	usr_email varchar(255) NOT NULL,
	firstname varchar(100) NULL,
	lastname varchar(100) NULL,
	cf varchar(16) NOT NULL,
	doc_id varchar NOT NULL,
	street varchar(150) NULL,
	postcode varchar(10) NULL,
	city varchar(150) NULL,
	phonenumber varchar(20) NULL,
	organization varchar(100) NULL,
	create_date timestamp(0) NOT NULL DEFAULT now(),
	"admin" bool NULL,
	nascosto bool NULL,
	doc_exp date NULL,
	CONSTRAINT utenti_pk PRIMARY KEY (id)
);