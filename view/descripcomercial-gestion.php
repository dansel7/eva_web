<?php

$link = @mysql_connect("localhost", "root", "va");
mysql_select_db("siade", $link);

// maximo por pagina
$limit = 5;

// pagina pedida
$pag = (int) $_GET["pag"];
if ($pag < 1)
{
   $pag = 1;
}
$offset = ($pag-1) * $limit;


$sql = "SELECT SQL_CALC_FOUND_ROWS idretaceo, numero FROM retaceo LIMIT $offset, $limit";
$sqlTotal = "SELECT FOUND_ROWS() as total";

$rs = mysql_query($sql);
$rsTotal = mysql_query($sqlTotal);

$rowTotal = mysql_fetch_row($rsTotal);
// Total de registros sin limit
$total = $rowTotal[0];

?>

<table border="1" bordercolor="#000">
   <thead>
      <tr>
         <td>Id</td>
         <td>Nombre</td>
      </tr>
   </thead>
   <tbody>
      <?php
         while ($row = mysql_fetch_assoc($rs))
         {
            $id = $row["idretaceo"];
            $name = htmlentities($row["numero"]);
         ?>
         <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo $name; ?></td>
         </tr>
         <?php
         }
      ?>
   </tbody>
   <tfoot>
      <tr>
         <td colspan="2">
      <?php
         $totalPag = ceil($total/$limit);
         $links = array();
         for( $i=1; $i<=$totalPag ; $i++)
         {
            $links[] = "<a href=\"?pag=$i\">$i</a>"; 
         }
         echo implode(" - ", $links);
      ?>
         </td>
      </tr>
   </tfoot>
</table>