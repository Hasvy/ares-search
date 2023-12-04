<?php
require "layout.php";
require "menu.php";
print('<div class="container mx-auto my-auto">');		// main-content div

// Výpis z databáze
$limit = 3;
$page_number = $_GET['page'] ?? 1;		//zjištěni aktuální stránky
if ($page_number < 1) {
	$page_number = 1;
}
$link = mysqli_connect("localhost", "root", "", "history_ares");
$offset = $limit * ($page_number - 1);
$sql_count = 'SELECT* FROM history';		// dotaz aby zjistit počet elementů v DB
$result_count = mysqli_query($link, $sql_count);
$count = mysqli_num_rows($result_count);
$count_pages = $count / $limit;		//počet stránek
$order_by = $_GET["order_by"] ?? "";		//sortováni
switch ($order_by) {		// jestli "order_by", který jsem zjistíl GETem = podmince v case, tedy zapiše do proměnne "ordering" spravnou čast pro SQL dotaz
	case "nazev_firmy_asc":
		$ordering = "nazev_firmy ASC";
		break;
	case "nazev_firmy_desc":
		$ordering = "nazev_firmy DESC";
		break;
	case "datum_asc":
		$ordering = "datum ASC";
		break;
	case "datum_desc":
	default:		// základně bude sortovát sestupně podle data hledání
		$order_by = "datum_desc";
		$ordering = "datum DESC";
		break;
}
$order_by_array = array(		//pro jednodušší výpis s použitím foreach
	"datum_desc" => "Seřadit sestupně podle data hledání",
	"datum_asc" => "Seřadit vzestupně podle data hledání",
	"nazev_firmy_desc" => "Seřadit sestupně podle názvu",
	"nazev_firmy_asc" => "Seřadit vzestupně podle názvu"
);
$sql = "SELECT * FROM history ORDER BY $ordering LIMIT $limit OFFSET $offset";		// SQL dotaz pro výpis historii
$result = mysqli_query($link, $sql);
print('<div class="d-flex justify-content-center my-2">');		// div pro tabulky v historii
foreach ($order_by_array as $key => $item) {
	if ($order_by == $key) {		// výpis aktualného sortováni
		print("<a class='btn btn-danger mx-1' href='history.php?page=$page_number&order_by=$key'> $item </a>");
	} else {		// výpis neaktualných sortování
		print("<a class='btn btn-outline-danger mx-1' href='history.php?page=$page_number&order_by=$key'> $item </a>");
	}
}
print('</div><div class="container col-6">');
while ($row = mysqli_fetch_array($result)) {
?>
	<table class="table table-bordered table-sm">
		<tbody>
			<tr style="border-top-color: black; border-top-width: 2px;">
				<th class="col-6">Datum vyhledání</th>
				<td> <?php print($row['datum']); ?> </td>
			</tr>
			<tr>
				<th>IČO</th>
				<td> <?php print($row['ico']); ?> </td>
			</tr>
			<tr>
				<th>Nazev firmy</th>
				<td> <?php print($row['nazev_firmy']); ?> </td>
			</tr>
			<tr>
				<th>Adresa</th>
				<td> <?php print($row['adresa']); ?> </td>
			</tr>
			<tr>
				<th>PSČ</th>
				<td> <?php print($row['psc']); ?> </td>
			</tr>
		</tbody>
	</table>
<?php
}
?>
</div>
<div class="container">
	<ul class="pagination row row-cols-auto">
		<?php
		for ($i = 1; $i < $count_pages + 1; $i++) {
			if ($i == $page_number) {
				print('<li class="col px-0 page-item active"><a class="page-link" style="background-color: #009FFF; border-color: #009FFF;" 
				href="history.php?page=' . $i . '&order_by=' . $order_by . '">' . $i . '</a></li>');		// výpis aktuálné stránky
			} else {
				print('<li class="col px-0 page-item" aria-current="page"><a class="page-link" style="color: #009FFF;" 
				href="history.php?page=' . $i . '&order_by=' . $order_by . '">' . $i . '</a></li>');		// výpis neaktuálných stránek
			}
		}
		?>
	</ul>
</div>