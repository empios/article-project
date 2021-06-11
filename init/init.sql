CREATE TABLE status (
    id int NOT NULL AUTO_INCREMENT,
    status_name varchar(150),
    PRIMARY KEY (id)
);

INSERT INTO status(status_name) VALUES ('created'), ('read');

CREATE TABLE category (
    id int NOT NULL AUTO_INCREMENT,
    category_name varchar(150),
    PRIMARY KEY (id)
);

INSERT INTO category(category_name) VALUES ('travel'), ('gaming');

CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    username varchar (100) NOT NULL,
    password varchar (100) NOT NULL,
    email varchar (100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE articles (
    id int NOT NULL AUTO_INCREMENT,
    title varchar (20),
    description varchar (255),
    status_id int,
    user_id int,
    category_id int,
    PRIMARY KEY (id),
    FOREIGN KEY (status_id) REFERENCES status(id),
    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);