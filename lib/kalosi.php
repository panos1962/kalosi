<?php

// Η βιβλιοθήκη "kalosi" σκοπό έχει να διευκολύνει τη γραφή ιστοσελίδων, αλλά
// μπορεί να χρησιμοποιηθεί και σε άλλα PHP προγράμματα, π.χ. προγράμματα που
// καλούνται μέσω Ajax από ιστοσελίδες κλπ.
//
// Προκειμένου να κάνουμε χρήση της βιβλιοθήκης, αρκεί να την εντάξουμε στην
// αρχή του προγράμματός μας μέσω της εντολής require, π.χ.:
//
//	require("/var/opt/kalosi/lib/kalosi.php");
//	...
//
// Αν δεν επιθυμούμε να εντάξουμε τη βιβιλιοθήκη με το πλήρες pathname, τότε
// μπορούμε να δημιουργήσουμε στην εφαρμογή μας subdirectory στο οποίο να
// έχουμε συντόμευση που να δείχνει στο πλήρες pathname της βιβλιοθήκης και
// να εντάσσουμε τη βιβλιοθήκη με εντολή require χρησιμοποιώντας σχετικό
// pathname, π.χ.:
//
//	require("../../mnt/kalosi/lib/kalosi.php");

// Το μόνο global αντικείμενο που είναι απαραίτητο για τη βιβλιοθήκη "kalosi"
// είναι το singleton "kalosi" το οποίο περιλαμβάνει ως static μεθόδους όλες
// τις functions της βιβλιοθήκης.

abstract class kalosi {
	// Για τη λειτουργία της βιβλιοθήκης, είναι απαραίτητο να υπάρχει
	// configuration file της μορφής:
	//
	//	kalosi::$conf = [
	//		"kalosidir" => "/var/opt/kalosi",
	//		"kalosiwww" => "http://localhost/kalosi",
	//		"appdir" => "/var/opt/sinergio",
	//		"wwwdir" => "/var/opt/sinergio/www",
	//		"www" => "http://localhost/sinergio",
	//		...
	//	];
	//
	// Το configuration file απλώς ορίζει την static property "conf" να
	// είναι ένα assosiative array, στο οποίο θα πρέπει οπωσδήποτε να
	// υπάρχει στοιχείο "appdir" που δείχνει το directory βάσης τής
	// εφαρμογής μας. Στο παραπάνω παράδειγμα το directory βάσης τής
	// εφαρμογής μας είναι το "/var/opt/sinergio". Στο directory βάσης
	// της εφαρμογής μας συνήθως υπάρχει subdirectory κάτω από το οποίο
	// βρίσκονται οι ιστοσελίδες της εφαρμογής και άλλα αρχεία τα οποία
	// παρέχονται προς τον έξω κόσμο. Αυτό το directory μπορεί να είναι
	// προσβάσιμο στον έξω κόσμο μέσω κάποιου domain name, ή να αποτελεί
	// subdirectory κάποιου άλλου directory που είναι προσβάσιμο στον
	// έξω κόσμο.
	//
	// Αν υποθέσουμε ότι η εφαρμογή μας είναι προσπελάσιμη στον έξω
	// κόσμο μέσω του domain name "sinergio.gr", τότε θέτουμε στο
	// configuration file:
	//
	//	"www" => "http://sinergio.gr"
	//
	// Αν, όμως, οι ιστοσελίδες της εφαρμογής μας παρέχονται μέσω του
	// subdirectory "sinergio" που βρίσκεται π.χ. στο domain "acme.com",
	// τότε θα θέσουμε:
	//
	//	"www" => "http://acme.com/sinergio"
	//
	// Όσον αφρά την database που ενδεχομένως χρησιμοποιεί η εφαρμογή
	// μας, τότε θα πρέπει να καθορίσουμε και τα στοιχεία της database
	// που είναι απαραίτητα για να προσπελάσουμε την database. Αυτά τα
	// στοιχεία είναι:
	//
	// dbhost	Το database host name (default "localhost").
	// dbuser	Όνομα χρήστη με τις απαραίτητες προσβάσεις στην
	//		database, ή στις databases που χρησιμοποιεί η
	//		εφαρμογή.
	// dbpass	Το password του του χρήστη που καθορίσαμε με την
	//		παράμετρο "dbuser".
	// dbname	Το όνομα της default database που χρησιμοποιεί
	//		η εφαρμογή μας. Αν δεν καθορίσουμε default database
	//		name, τότε θα πρέπει να καθορίζουμε πάντα το database
	//		name πριν από τα ονόματα των πινάκων.
	// charset	Είναι το όνομα του character set που θα χρησιμοποιηθεί
	//		για την ανταλλαγή δεδομένων μεταξύ των PHP προγραμμάτων
	//		και της database (default "utf8mb4").

	static public $conf = NULL;

	// Η function "init" καλείται πριν από οποιαδήποτε άλλη function τής
	// βιβλιοθήκης και δέχεται ως παράμετρο το pathname του configuration
	// file τής εφαρμογής μας, π.χ.
	//
	//	require("/var/opt/kalosi/lib/kalosi.php");
	//	kalosi::init("/var/opt/sinergio/local/conf.php");
	//
	// Αν έχουμε αναπτύξει βιβλιοθήκη "sinergio.php" στην εφαρμογή μας,
	// πιθανόν να εκκινεί κάπως έτσι:
	//
	//	require("/var/opt/kalosi/lib/kalosi.php");
	//	kalosi::init("/var/opt/sinergio/local/conf.php");
	//	...
	//
	// οπότε το "index.php" στην υποτιθέμενη ιστοσελίδα "promi" τής
	// εφαρμογής θα εκκινεί κάπως έτσι:
	//
	//	require("../../lib/sinergio.php");
	//	...
	//
	// ενώ το "index.php" στη βασική σελίδα τής εφαρμογής θα εκκινεί
	// κάπως έτσι:
	//
	//	require("../lib/sinergio.php");
	//	...

	static public function init($cfile = NULL) {
		if (isset(self::$conf))
		self::fatal("init: already called");

		if (!isset($cfile))
		self::fatal("init: missing configuration file");

		if (!is_readable($cfile))
		self::fatal("init: " . $cfile . ": cannot read file");

		require($cfile);

		if (!isset(self::$conf))
		self::fatal("init: invalid configuration file");

		if (!isset(self::$conf))
		self::fatal("init: configuration syntax error");

		foreach ([
			"kalosidir",
			"kalosiwww",
			"appdir",
			"wwwdir",
		] as $param) {
			if (self::no_conf($param))
			self::fatal("init: missing '" . $param . "' parameter");
		}

		self::
		fixconfdir("kalosidir")::
		fixconfdir("kalosiwww")::
		fixconfdir("appdir")::
		fixconfdir("wwwdir")::
		fixconfdir("www");

		register_shutdown_function("kalosi::atexit");
		return __CLASS__;
	}

	static private function fixconfdir($idx) {
		if (array_key_exists($idx, self::$conf))
		self::$conf[$idx] = preg_replace("@/+$@", "", self::$conf[$idx]);

		return __CLASS__;
	}

	static public function is_conf($idx) {
		return array_key_exists($idx, self::$conf);
	}

	static public function no_conf($idx) {
		return !self::is_conf($idx);
	}

	// Η function "kalosiwww" δέχεται ως παράμετρο ένα pathname και
	// επιστρέφει το πλήρες url με βάση την παράμετρο "kalosiwww" του
	// configuration.

	static public function kalosiwww($s) {
		$t = self::$conf["kalosiwww"];

		if (substr($s, 0, 1) !== "/")
		$t .= "/";

		return ($t .= $s);
	}

	// Η function "atexit" θα κληθεί στο τέλος όλων των PHP προγραμμάτων
	// τής εφαρμογής μας, προκειμένου να επιτελέσει κάποιες εργασίες που
	// ίσως δεν μεριμνήσαμε ή δεν προλάβαμε να εκτελέσουμε.

	static public function atexit() {
		if (self::$selida_state)
		self::html_close();

		return __CLASS__;
	}

///////////////////////////////////////////////////////////////////////////////@

	static public $db = NULL;

	static public function database() {
		if (isset(self::$db))
		return __CLASS__;

		if (!isset(self::$conf))
		self::fatal("database: must call init function first");

		if (self::no_conf("dbhost"))
		self::$conf["dbhost"] = "localhost";

		if (self::no_conf("dbuser"))
		self::fatal("database: dbuser not set");

		if (self::no_conf("dbpass"))
		self::fatal("database: dbpass not set");

		if (self::no_conf("dbname"))
		self::$conf["dbname"] = "";

		self::$db = new mysqli(
			self::$conf["dbhost"],
			self::$conf["dbuser"],
			self::$conf["dbpass"],
			self::$conf["dbname"]
		);

		if (self::$db->connect_errno)
		self::fatal("database: connect failed");

		if (self::no_conf("charset"))
		self::$conf["charset"] = "utf8mb4";

		if (!self::$db->set_charset(self::$conf["charset"]))
		self::fatal("database: " . self::$conf["charset"] . ": invalid charset");

		return __CLASS__;
	}

	static public function query($query = "") {
		if (!isset(self::$db))
		self::database();

		$result = self::$db->query($query);

		if ($result === false)
		self::fatal("query: " . $query . ": failed");

		return $result;
	}

	static public function fetch_row($result, $mode = MYSQLI_ASSOC) {
		$row = $result->fetch_object();

		switch ($row) {
		case null:
			$result->close();
			return null;
		case false:
			self::fatal("fetch_row: failed");
		}

		return $row;
	}

///////////////////////////////////////////////////////////////////////////////@

	static public $content_type = NULL;

	static private function content_type_set($tipos) {
		if (self::$content_type)
		self::fatal("content: redefined");

		switch ($tipos) {
		case "text/plain":
		case "text/html":
		case "application/json":
			break;
		default:
			self::fatal("content: " . $tipos . ": invalid content type");
		}

		self::$content_type = $tipos;

		header("Content-Type: " . $tipos . "; charset=utf-8");
		return __CLASS__;
	}

	static public function header_text() {
		return self::content_type_set("text/plain");
		return __CLASS__;
	}

	static public function is_content_text() {
		return self::$content_type === "text/plain";
	}

	static public function header_html() {
		return self::content_type_set("text/html");
		return __CLASS__;
	}

	static public function is_content_html() {
		return self::$content_type === "text/html";
	}

	static public function header_json() {
		return self::content_type_set("application/json");
		return __CLASS__;
	}

	static public function is_content_json() {
		return self::$content_type === "application/json";
	}

///////////////////////////////////////////////////////////////////////////////@

	static public $selida_state = NULL;
	static private $title = NULL;
	static private $favicon = NULL;

	static public function head_section() {
?>
<!DOCTYPE html>
<html>
<head>
<?php

		self::$selida_state = 'head';
		return __CLASS__;
	}

	static public function head_close() {
		if ((!isset(self::$title)) && self::is_conf("title"))
		self::title(self::$conf["title"]);

		if ((!isset(self::$favicon)) && self::is_conf("favicon"))
		self::favicon(self::$conf["favicon"]);

		// Αν υπάρχει PHP πρόγραμμα με όνομα "kalosi.php" στο
		// directory "lib" κάτω από το directory βάσης της εφαρμογής,
		// τότε το συμπεριλαμβάνουμε σ' αυτό το σημείο. Ωστόσο
		// φροντίζουμε να μην κάνουμε αυτήν την ενέργεια στην ίδια
		// την εφαρμογή "kalosi".

		if (self::$conf["appdir"] !== self::$conf["kalosidir"]) {
			$file = self::appdir("lib/kalosi.php");

			if (is_readable($file))
			require($file);
		}

		// Αν υπάρχει PHP πρόγραμμα με όνομα "kalosi.php" στο τρέχον
		// directory, τότε το συμπεριλαμβάνουμε σ' αυτό το σημείο.

		$file = "kalosi.php";

		if (is_readable($file))
		require($file);

		$file = self::wwwdir("lib/kalosi.css");

		if (is_readable($file))
		self::css(self::www("lib/kalosi.css"));

		$file = self::wwwdir("lib/kalosi.js");

		if (is_readable($file))
		script(self::www("lib/kalosi"));

		self::
		check_for_default_css()::
		check_for_default_script();

?>
</head>
<?php
		self::$selida_state = 'html';
		return __CLASS__;
	}

	static public function body_section() {
		switch (self::$selida_state) {
		case 'html':
			break;
		case 'head':
			self::head_close();
			break;
		default:
			self::fatal("body_section: invalid page state");
		}

?>
<body>
<?php
		self::$selida_state = 'body';
		return __CLASS__;
	}

	static public function body_close() {
		if (self::$selida_state !== 'body')
		self::fatal("body_close: invalid page state");

?>
</body>
<?php
		self::$selida_state = 'html';
		return __CLASS__;
	}

	static public function html_close() {
		switch (self::$selida_state) {
		case 'body':
			self::body_close();
			break;
		case 'head':
			self::head_close();
			break;
		case 'html':
			break;
		default:
			self::fatal("html_close: invalid page state");
		}

?>
</html>
<?php
		self::$selida_state = NULL;
		return __CLASS__;
	}

///////////////////////////////////////////////////////////////////////////////@

	static public function favicon($ikona = NULL) {
		if (!isset($ikona)) {
			self::$favicon = true;
			return __CLASS__;
		}

		self::$favicon = $ikona;

		$tipos = strtolower(preg_replace("/.*\./", "", $ikona));

		switch ($tipos) {
		case "ico":
			$tipos = "x-icon";
			break;
		case "png":
			$tipos = "png";
			break;
		case "gif":
			$tipos = "gif";
			break;
		default:
			self::fatal($ikona . ": invalid favicon image type");
		}

?>
<link rel="icon" type="image/<?php print $tipos; ?>" href="<?php print $ikona; ?>">
<?php
		return __CLASS__;
	}

	static public function title($title = NULL) {
		if (!isset($title)) {
			self::$title = true;
			return __CLASS__;
		}

		self::$title = true;
?>
<title><?php print $title; ?></title>
<?php
		return __CLASS__;
	}

	static public function jQuery() {
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<?php
		return __CLASS__;
	}

	static public function jQueryUI() {
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<?php
		return __CLASS__;
	}

	static private function check_for_default_css() {
		$css = "main.css";

		if (!file_exists($css))
		return __CLASS__;

		if (!is_readable($css))
		self::fatal($css . ": cannot read");

		self::css("main");
		return __CLASS__;
	}

	static private function check_for_default_script() {
		$script = "main.js";

		if (!file_exists($script))
		return __CLASS__;

		if (!is_readable($script))
		self::fatal($script . ": cannot read");

		self::script("main");
		return __CLASS__;
	}

	static public function css($css) {
		if (!preg_match("@\.css$@", $css))
		$css .= ".css";

		if (preg_match("@^https?://@", $css))
		return self::css_tag($css);

		$mt = filemtime($css);

		if ($mt === false)
		self::fatal($css . ": file not found");

		return self::css_tag($css . "?mt=" . $mt);
	}

	static private function css_tag($css) {
?>
<link rel="stylesheet" href="<?php print $css; ?>">
<?php
		return __CLASS__;
	}

	static public function script($script) {
		if (preg_match("@\.js$@", $script))
		$script = preg_replace("@\.js$@", "", $script);

		$script_src = $script . ".js";

		if (preg_match("@^https?://@", $script_src))
		return self::script_tag($script_src);

		$mt_src = filemtime($script_src);

		if ($mt_src === false)
		self::fatal($script_src . ": file not found");

		$script_min = $script . ".min.js";

		if (!file_exists($script_min))
		return self::script_tag($script_src . "?mt=" . $mt_src);

		if (!is_readable($script_min))
		self::fatal($script_min . ": cannot read");

		$mt_min = filemtime($script_min);

		if ($mt_min === false)
		self::fatal($script_min . ": file not found");

		if ($mt_src > $mt_min)
		return self::script_tag($script_src . "?mt=" . $mt_src);

		return self::script_tag($script_min . "?mt=" . $mt_min);
	}

	static private function script_tag($script) {
?>
<script src="<?php print $script; ?>"></script>
<?php
		return __CLASS__;
	}

///////////////////////////////////////////////////////////////////////////////@

	static public function is_session($idx) {
		return array_key_exists($idx, $_SESSION);
	}

	static public function is_get($idx) {
		return array_key_exists($idx, $_GET);
	}

	static public function is_post($idx) {
		return array_key_exists($idx, $_POST);
	}

	static public function is_request($idx) {
		return array_key_exists($idx, $_REQUEST);
	}

///////////////////////////////////////////////////////////////////////////////@

	static public function sqlstr($s) {
		return "'" . self::$db->real_escape_string($s) . "'";
	}

	static public function jsonstr($s) {
		return self::$db->json_encode($s);
	}

///////////////////////////////////////////////////////////////////////////////@

	// Η function "appdir" δέχεται ως παράμετρο ένα pathname και επιστρέφει
	// το πλήρες pathname με βάση την παράμετρο "appdir" του configuration.

	static public function appdir($s) {
		$t = self::$conf["appdir"];

		if (substr($s, 0, 1) !== "/")
		$t .= "/";

		return ($t .= $s);
	}

	// Η function "wwwdir" δέχεται ως παράμετρο ένα pathname και επιστρέφει
	// το πλήρες pathname με βάση την παράμετρο "wwwdir" του configuration.

	static public function wwwdir($s) {
		$t = self::$conf["wwwdir"];

		if (substr($s, 0, 1) !== "/")
		$t .= "/";

		return ($t .= $s);
	}

	// Η function "www" δέχεται ως παράμετρο ένα pathname και επιστρέφει
	// το πλήρες url με βάση την παράμετρο "www" του configuration.

	static public function www($s) {
		if (self::no_conf("www"))
		self::fatal("www: missing configuration value");

		$t = self::$conf["www"];

		if (substr($s, 0, 1) !== "/")
		$t .= "/";

		return ($t .= $s);
	}

	static public function fatal($msg) {
		exit("kalosi::" . $msg);
	}
}

class kalosiXristis {
	public $login;
	public $onoma;
	public $egrafi;
	public $kodikos;
	public $anenergos;
	public $info;

	public function __construct($data) {
		foreach ($data as $key => $val)
		$this->$key = $val;
	}

	public function validate() {
		$query = "SELECT 1 FROM `kalosi`.`xristis` " .
			"WHERE (`login` LIKE " . kalosi::sqlstr($this->login) . ") " .
			"AND (`kodikos` = SHA1(" . kalosi::sqlstr($this->kodikos) . "))";
		$result = kalosi::query($query);

		$row = kalosi::fetch_row($result);

		if (!$row)
		return false;

		$result->close();
		return true;
	}
}

session_start();
setcookie(session_name(), session_id(), time() + (3600 * 24 * 10), "/");

?>
