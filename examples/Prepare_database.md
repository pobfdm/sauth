#How to prepare the database

First of all you must prepare your database. Below I will show you how to prepare a basic SQLite data.
From console we type:
```
sqlite users.sqlite
```

After create the table with this sql:

```
CREATE TABLE  "users" (
  "id" INTEGER NOT NULL ,
  "user" varchar(1) ,
  "pass" varchar(256) ,
  "cookie" varchar(256) ,
  PRIMARY KEY ("id") 
);



INSERT INTO users (id,user,pass,cookie)VALUES (null,"utente","5f4dcc3b5aa765d61d8327deb882cf99","834502");
```

You can get the password field with the following command :
```	
echo -n password | md5sum
```
	
where "password" is your password. 
