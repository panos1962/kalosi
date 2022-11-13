<?php
require("../lib/kalosi.php");

class Xristis extends kalosiXristis {
	public function html($col = NULL) {
		if (isset($col)) {
?>
<span>
<?php
			printf("<i>%s</i>: <b>%s</b>", $col, $this->$col);
?>
</span>
<?php
			return $this;
		}
?>
<div>
<?php
		$this->
		html("login")->
		html("onoma")->
		html("egrafi")->
		html("info");
?>
</div>
<?php
	}
}

kalosi::
init("../local/conf.php")::
database()::
header_html()::
head_section("kalosi!")::
jQuery()::
favicon("kalosi.png")::
head_close();

kalosi::body_section();

$query = "SELECT * FROM `xristis`";
$result = kalosi::query($query);

while ($row = kalosi::fetch_row($result))
(new Xristis($row))->html();

?>
