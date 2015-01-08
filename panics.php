<body>
<?php
echo "<H1>LLBSD Panic DB</H1>";
echo "<H2>Work in progress, poke sbruno@</H2>";

$db = new SQLite3("/usr/local/www/panics/panics.db");

$results = $db->query('SELECT * from panics ORDER by datetime("epocdate") DESC');

echo "<table cellpadding=10 border=1>";

// print column headers
echo "<tr>";
echo "<td>Hostname</td>";
echo "<td>Date</td>";
echo "<td>OS rev</td>";
echo "<td>Panic String</td>";
echo "</tr>";

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
?>
</body>
