<?php
require "layout.php";
require "menu.php";
print('<div class="container mx-auto my-auto">');   // main-content div
$file = file_get_contents("http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico=" . $_POST["ICO"]);
$xml = simplexml_load_string($file);    // zpracování získaného xml souboru
$ns = $xml->getDocNamespaces(true);
$data = $xml->children($ns['are']);
$element = $data->children($ns['D'])->VBAS;
$check = 0;   // pro ověření jestli zádané IČO existuje, pokůd najdé IČO, změní se na 1

if (strval($element->ICO) == $_POST["ICO"]) { // zjištění jednotlivých elementu v xml
  $datum = date('Y-m-d');
  $nazev_firmy = strval($element->OF);
  $ico = strval($element->ICO);
  $ulice = strval($element->AA->NU);
  $cislo_domu = strval($element->AA->CD);
  $cislo_orientacni = strval($element->AA->CO);
  $mesto = strval($element->AA->N);
  $psc = strval($element->AA->PSC);

  if ($cislo_orientacni) {
    $adresa = $mesto . ", " . $ulice . " " . $cislo_domu . "/" . $cislo_orientacni;
  } else {
    $adresa = $mesto . ", " . $ulice . " " . $cislo_domu;
  }
  $check = 1;
}

if ($check == 1) { ?>
  <div class="container col-6 py-2">
    <table class="table table-bordered">
      <tbody>
        <tr>
          <th class="col-6">Datum vyhledání</th>
          <td> <?php print($datum); ?> </td>
        </tr>
        <tr>
          <th>IČO</th>
          <td> <?php print($ico); ?> </td>
        </tr>
        <tr>
          <th>Nazev firmy</th>
          <td> <?php print($nazev_firmy); ?> </td>
        </tr>
        <tr>
          <th>Adresa</th>
          <td> <?php print($adresa); ?> </td>
        </tr>
        <tr>
          <th>PSČ</th>
          <td> <?php print($psc); ?> </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Prace s SQL databázi -->
<?php
  $link = mysqli_connect("localhost", "root", "", "history_ares"); //údeje pro připojeni k databázi

  if ($link == false) {
    print(mysqli_connect_error());
  } else {
    print("<p class='text-center'>Connection to DB: OK, the request has written to DB</p>");
    $link->set_charset("utf8mb4");    //nastavéni kodováni, jestli se připojíl k DB
  }
  $sql = 'INSERT INTO history (datum, nazev_firmy, ico, adresa, psc)
    VALUES ("' . $datum . '","' . $nazev_firmy . '",' . $ico . ',"' . $adresa . '",' . $psc . ');';   // SQL dotaz pro přidání záznamu
  $result = mysqli_query($link, $sql);

  if ($result == false) {
    print("Connection to DB: Error\n" . mysqli_error($link));
  }
} else {
  print('<h4 class="text-center py-2">Zadano špatné IČO</h4>');
} ?>
<div class="d-flex">
  <a class="btn btn-outline-danger mx-auto" role="button" href="index.php">Back</a>
</div>