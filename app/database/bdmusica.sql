create database musica;
use musica;

create table interprete (
	id int unsigned primary key auto_increment,
	nombre varchar(500)
)engine=innodb;

create table genero (
	id int unsigned primary key auto_increment,
	nombre varchar(500)
)engine=innodb;

create table album (
	id int unsigned primary key auto_increment,
	interprete_id int unsigned,
	nombre varchar(500),
	constraint fk_alb_int foreign key (interprete_id) references interprete(id)
)engine=innodb;

create table cancion (
	id int unsigned primary key auto_increment,
	hash varchar(100),
	interprete_id int unsigned,
	album_id int unsigned,
	genero_id int unsigned,
	nombre varchar(700),
	anio smallint(4),
	ruta_archivo varchar(2000),
	fecha_agregado datetime,
	reproducciones int unsigned default 0,
	constraint fk_can_gen foreign key (genero_id) references genero(id),
	constraint fk_can_int foreign key (interprete_id) references interprete(id),
	constraint fk_can_alb foreign key (album_id) references album(id)
)engine=innodb;

create table palabra(
	id int unsigned primary key auto_increment,
	texto varchar(1000)
)engine=innodb;

create table indice (
	id int unsigned primary key auto_increment,
	palabra_id int unsigned,
	cancion_id int unsigned,
	constraint fk_ind_pal foreign key (palabra_id) references palabra(id),
	constraint fk_ind_can foreign key (cancion_id) references cancion(id)
)engine=innodb;

create table usuario (
	id varchar(50) primary key,
	nombre varchar(400),
	contrasena varchar(50)
)engine=innodb;

create table lista_reproduccion (
	id int unsigned primary key auto_increment,
	nombre varchar(200),
	usuario_id varchar(50),
	constraint fk_lst_usr foreign key (usuario_id) references usuario(id)
)engine=innodb;

create table detalle_lista (
	id int unsigned primary key auto_increment,
	cancion_id int unsigned,
	lista_id int unsigned,
	orden int unsigned default 1,
	constraint fk_det_can foreign key (cancion_id) references cancion(id),
	constraint fk_det_lst foreign key (lista_id) references lista_reproduccion(id)
)engine=innodb;

