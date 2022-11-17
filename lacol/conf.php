<?php

// Το παρόν αρχείο μπορεί να χρησιμοποιηθεί ως πρότυπο configuration file για
// τη βιβλιοθήκη "kalosi".

kalosi::$conf = [
	"kalosidir" => "/var/opt/kalosi",
	"kalosiwww" => "http://localhost/kalosi",
	"appdir" => "/var/opt/sinergio",
	"wwwdir" => "/var/opt/sinergio/www",
	"www" => "http://localhost/sinergio",
	"title" => "Συνεργείο",

	/*
	Αν δεν επιθυμούμε ενεργοποίηση του session by default, τότε
	προσθέτουμε την παράμετρο "session" με τιμή false:

	"session" => false,
	*/

	"dbhost" => "localhost",
	"dbuser" => "sinergio",
	"dbpass" => "xxx",
	"dbname" => "sinergio",
	"charset" => "utf8mb4"
];

kalosi::$conf["favicon"] = kalosi::$conf["www"] . "/images/sinergio.png";

?>
