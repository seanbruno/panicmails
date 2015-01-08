<body>
<?php
echo "<H1>LLBSD Panic DB</H1>";
echo "<H2>Work in progress, poke sbruno@</H2>";

$db = new SQLite3("/usr/local/www/panics/panics.db");

// master table to next the other three
echo "<table cellpadding=0 border=0>";

echo '<td valign="top">';
// Count by host for date range of 24 hours
echo "<table cellpadding=10 border=1>";
echo "<tr>";
echo "<th align=center>Hostname</th>";
echo "<th>#</th>";
echo "</tr>";
$results = $db->query("SELECT hostname, count(hostname) FROM panics WHERE datetime(epocdate) >= date('now', '-1 day') group by hostname");
while ($paniccount = $results->fetchArray()) {
	echo "<tr>";
	echo "<td>".$paniccount['hostname']."</td>";
	echo "<td>".$paniccount[1]."</td>";
	echo "</tr>";
}
echo "</table>";
echo "</td>";
// end of first nested table row

echo '<td valign="top">';
// Count by osrev for date range of 24 hours
echo "<table cellpadding=10 border=1>";
echo "<tr>";
echo "<th align=center>OS Revision</th>";
echo "<th>#</th>";
echo "</tr>";
$results = $db->query("SELECT osrev, count(panicstr) FROM panics WHERE datetime(epocdate) >= date('now', '-1 day') group by osrev");
while ($paniccount = $results->fetchArray()) {
	echo "<tr>";
	echo "<td>".$paniccount['osrev']."</td>";
	echo "<td>".$paniccount[1]."</td>";
	echo "</tr>";
}
echo "</table>";
echo "</td>";

echo '<td valign="top">';
// main old table
echo "<table cellpadding=10 border=1>";

// print column headers
echo "<tr>";
echo "<th align=center>Hostname</th>";
echo "<th>Date</th>";
echo "<th>OS rev</th>";
echo "<th>Panic String</th>";
echo "</tr>";

$results = $db->query("SELECT * FROM panics WHERE datetime(epocdate) >= date('now', '-1 day') ORDER BY datetime(epocdate) DESC");
while ($panicrow = $results->fetchArray()) {
	echo "<tr>";
	//echo "<td>".$panicrow['hostname']."</td>";
	echo "<td><a href='".$panicrow['url']."'>".$panicrow['hostname']."</a></td>";
	echo "<td>".$panicrow['date']."</td>";
	echo "<td>".$panicrow['osrev']."</td>";
	echo "<td>".$panicrow['panicstr']."</td>";
	echo "</tr>";
}


echo "</table>";
echo "</td>";
echo "</table>";
?>
</body>
