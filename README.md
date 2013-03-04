DSN Factory
=========

Tired of going on http://php.net because you can't remember how's constructed the DSN you need ?

	$dsn = new Dsn(Dsn::MYSQL, [
	    "host" => "127.0.0.1",
	    "port" => "3306",
	    "dbname" => "my_database",
	]);
	
	
	$dsn = new Dsn(Dsn::SQL_SERVER, [
	    "host" => "127.0.0.1",
	    "port" => "3306",
	    "dbname" => "my_database",
	]);
	
