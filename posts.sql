create table posts (
  id integer auto_increment not null,
  name varchar(80) not null,
  email varchar(80),
  post varchar(max),
  poster_id integer,
  primary key (id),
  foreign key (poster_id) references users(id)
);
