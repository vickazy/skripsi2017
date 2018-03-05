<!DOCTYPE html>
<?php 
define('core', true); 
define("DOC_ID", 0);
define("TERM_POSITION", 1);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(__FILE__) . DS);
include_once DIR.'include'.DS.'config.php';
include_once DIR.'include'.DS.'dokumen.php';
include_once DIR.'lib'.DS.'database.class.php';
include_once DIR.'lib'.DS.'core.tfidf.php';
$ir = new IR();
$cekTerm = $ir->cekTerm();
// print_r($cekTerm);
$countTerm = $ir->countTerm();
print("Jumlah Term = ".$countTerm);
echo "<br>";
$ir->create_index($D);
echo "<br>";
$arr = $ir->indexAyat();
echo "<br>";
// $indexAyat = $ir->indexAyat();
 ?>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    /*width: 100%;*/
}

td{
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
th {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>

<table>
  <tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Term</th>
    <th colspan="286">TF*IDF</th>
  </tr>
  <tr>
    <?php for ($i=1; $i <= 286; $i++) { 
      # code...
      ?><th><?php echo $i ?></th><?php 
    } ?>
  </tr>

<?php 
  $id = 0;
  $no = 1;
  $c = 1;
  while ($result= $cekTerm->fetch_assoc()) {
    # code...
    $term = $result['kataTerjemahan'];
    // print($terms."<br>");
    $tf  = $ir->tf($term);
    // $ndw = $ir->ndw($term);
    $ndw   = $ir->ndw($term);
    $idf = $ir->idf($term);
    for ($i=0; $i < 286; $i++) { 
      # code...
      $arkali[$id][$i] = $arr[$id][$i]*$idf;
    }
    ?>
      <tr>
        <td><?php echo $no ?></td>
        <td><?php echo $term ?></td>
        <?php 
        for ($i=0; $i < 286; $i++) { 
            echo "<td>".$arkali[$id][$i]."</td>";
        }
         ?>
      </tr>
    <?php
    // echo $arkali[2][30]+$arkali[1][10]."<br>";
    $id++;
    $no++;
    /*echo "<p>";
    echo "Term Frequency of '$term' is $tf<br />";
    echo "Number Of Documents with $term is $ndw<br />";
    echo "Inverse Document Frequency of $term is $idf";
    echo "</p>";*/
  }
  ?>
  <tr>
    <td></td>
    <td>Jumlah</td>
    <?php
  for ($i=0; $i < 286; $i++) { 
  $jumlah = 0;
  $ayat = $i+1;
  # code...
        for ($id=0; $id < $countTerm; $id++) { 
          # code...
          // echo $arkali[$id][$i]." ";
          $jumlah += $arkali[$id][$i];
        }
        echo "<td>".$jumlah."</td>";
        // $ir->insertTFIDF($ayat,$jumlah);
        // echo $arkali[$id][$i];
        // echo "<br><br>";
  }

   ?>
   </tr>
</table>
<?php 
    /*for ($id=0; $id < $countTerm; $id++) { 
      echo $arkali[$id][$i];
    }*/
 ?>
</body>
</html>
