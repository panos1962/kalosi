<?php
require("../lib/kalosi.php");

kalosi::
init("../local/conf.php")::
database()::
header_html()::
head_section("kalosi!")::
jQuery()::
favicon("kalosi.png")::
head_close();

kalosi::body_section();

$query = "SELECT * FROM `iliko` LIMIT 2";
$result = kalosi::query($query);

while ($row = kalosi::fetch_row($result)) {
?>
<div>
<?php
print $row["id"];
?>
</div>
<?php
}
?>
