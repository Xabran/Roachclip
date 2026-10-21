<?php
    require("../constants.php");
    require_once 'excel_reader2.php';
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);
    //$mysqli = mysqli_connect($dbhost, $dbusr, $dbpw, $targetDB);
    error_reporting(E_ALL ^ E_NOTICE);

   $data = new Spreadsheet_Excel_Reader("newtshirtinfo2.xls");
   //$data->rowspan($row,$col,$sheet=0) $data->colspan($row,$col,$sheet=0)
   //$data->val(10,'AZ')
   //$data->rowcount($sheet_index=0) $data->colcount($sheet_index=0)
   //echo $data->dump($row_numbers=false,$col_letters=false,$sheet=0,$table_class='excel');
   //$rows = $data->rowcount($sheet_index=0);
   //$cols = $data->colcount($sheet_index=0);
   $cols = 5;
   $rows = 244;

   for($i=2;$i<=$rows;$i++)
   {//rows
       if($data->val($i,1) == "END"){break;}
       $sql = "INSERT INTO tshirts (affiliatelink,imagelink) VALUES (";
       $sql .= "'".prepareforDB($data->val($i,1))."',";
       $sql .= "'".prepareforDB($data->val($i,3))."');";
       mysql_query($sql);
       echo $sql . "<br/>";
       $sql = "SELECT LAST_INSERT_ID() AS NEWID;";
       $results = mysql_query($sql);
       $newID = mysql_fetch_assoc($results);
       echo $newID["NEWID"] . "<br>";
       $sql = "INSERT INTO tshirt_cats_link (shirt_id,category_id) VALUES (".$newID["NEWID"];
       $sql .= ",(SELECT category_id FROM tshirt_cats WHERE category_name='".$data->val($i,4)."' LIMIT 0,1 ));";
       echo $sql . "<br>";
       mysql_query($sql);
       if(strlen(trim($data->val($i,5))) > 0)
       {
            $sql = "INSERT INTO tshirt_cats_link (shirt_id,category_id) VALUES (".$newID["NEWID"];
            $sql .= ",(SELECT category_id FROM tshirt_cats WHERE category_name='".$data->val($i,5)."' LIMIT 0,1 ));";
            echo $sql . "<br>";
            mysql_query($sql);
       }
       echo "<br>";


       /*
       if ($mysqli->multi_query($sql)) {
       do {
           if ($result = $mysqli->use_result()) {
               echo $result;
               $result->close();
           }
       } while ($mysqli->next_result());
    }
    else
    {
        die(mysqli_error($mysqli)." query: ".$sql);
    }


      // mysqli_multi_query($mysqli, $sql) or die(mysqli_error($mysqli)." query: ".$sql);
       //$result = mysqli_use_result($mysqli);
       //mysqli_free_result($result);
       //mysqli_close($mysqli);
       //$result->close();
    */
   }
   

    function prepareforDB($val)
    {
            return trim(str_replace("'","''",$val));
    }

    function toMYSQLDate($val)
    {
        return strftime("%Y/%m/%d",strtotime($val));
    }
?>