<?php

// Το παρόν αρχείο μπορεί να χρησιμοποιηθεί ως πρότυπο configuration file για
// τη βιβλιοθήκη "kalosi".


kalosi::$conf = [
	"basedir" => "/var/opt/sinergio",
	"www" => "http://localhost/sinergio",
	"title" => "Συνεργείο",

	"dbhost" => "localhost",
	"dbuser" => "sinergio",
	"dbpass" => "xxx",
	"dbname" => "sinergio",
	"charset" => "utf8mb4"
];

kalosi::$conf["favicon"] = kalosi::$conf["www"] . "/images/sinergio.png";

?>