CREATE TABLE todo (
  id int(11) NOT NULL auto_increment,
  name varchar(100) NOT NULL,
  description varchar(100) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO todo (name, description)
    VALUES  ('Hello todo list',  'First todo list created by database migration');
