<?php
###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 14-02-2025    |   Create   |  1.1402.2025                                                                 #
############################################################################################################### 
include "../../sysconf/con_reff.php";
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
include "global_func_report.php";

$condb = connectDB();
session_start();

$v_agentid    = get_session("v_agentid");
$v_agentname  = get_session("v_agentname");

$reportlist   = get_param('sel_report');
$dateperiod   = get_param('rpt_period');
$vgroup_id    = get_param("rpt_group");
$vagent_id    = get_param("rpt_user");
$cmbtype         = get_param('cmbtype');

$cmbuser      = get_param("cmbuser");

$date_from    = substr($dateperiod,0,10);
$date_to      = substr($dateperiod,12);
$vstart_time  = $date_from." 00:00:00";
$vend_time    = $date_to." 23:59:59";
$preparedby   = $v_agentname;

$vstart_time = trim($vstart_time);
$vend_time   = trim($vend_time);

$date_from = trim($date_from);
$date_to   = trim($date_to);


 //array agent
  $array_agent_id[]   = "";
  $array_agent_name[] = "";
  $sql_str1 = "SELECT a.* FROM cc_pbx_sip_trunk a where a.status=1";
  $sql_res1 = mysqli_query($condb, $sql_str1);
  while($sql_rec1 = mysqli_fetch_array($sql_res1)) {
    $param_trunknm            = $sql_rec1["trunk_name"];
    if (strpos($param_trunknm, 'XL') !== false) { 
        $trunknm='XL';
    }
    elseif (strpos($param_trunknm, 'COMMSOL') !== false) { 
        $trunknm='COMMSOL';
    } 
    elseif (strpos($param_trunknm, 'TELESAVE') !== false) { 
        $trunknm='TELESAVE';
    } 
    elseif (strpos($param_trunknm, 'QUIROS') !== false) { 
        $trunknm='QUIROS';
    } 
    elseif (strpos($param_trunknm, 'SME') !== false) { 
        $trunknm='SME';
    } 
    // echo "string $trunknm || $param_trunknm </br>";
    if ($array_trunkid[$trunknm]=='') {
      $array_trunkid[$trunknm]  = $sql_rec1["id"];
    }else{
      $array_trunkid[$trunknm]  .= ",".$sql_rec1["id"];
    }
    // $array_trunkid[$trunknm]  = $sql_rec1["id"];
    
    if ($array_serverid[$trunknm]=='') {
      $array_serverid[$trunknm]  = $sql_rec1["server_id"];
    }else{
      $array_serverid[$trunknm]  .= ",".$sql_rec1["server_id"];
    }
    // $array_serverid[$trunknm] = $sql_rec1["server_id"];

    if ($array_host[$trunknm]=='') {
      $array_host[$trunknm]  = $sql_rec1["host"];
    }else{
      $array_host[$trunknm]  .= ",".$sql_rec1["host"];
    }
    // $array_host[$trunknm]     = $sql_rec1["host"];
  }
  mysqli_free_result($sql_res1); 
// print_r($array_trunkid);
// die();
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tahun
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tanggal
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

if($cmbtype==1){
  $repname = "By Provider";
}else{
  $repname = "By User";
}
 
?>

<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
  <head>
    <title>Report CC</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/bootstrap.css">
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="../../assets/report/fonts/icomoon.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/fonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/vendors/css/sliders/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/vendors/css/extensions/pace.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/vendors/css/tables/extensions/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/app.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/colors.css">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/core/menu/menu-types/vertical-overlay-menu.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/report/css/style.css">
    
    <style>
  div.dt-buttons{
      margin-top: 50px;
  }
  *{
    font-size: 11px;
  }
    </style>

  </head>

    <div class="content-body"><!-- HTML5 export buttons table -->
  <div class="row">
    <div class="col-xs-12">
      <div class="card-block" style="background-color: #ffffff">
        <?php
          echo header_label_client($condb);
        ?>
        <div id="invoice-company-details" class="row">
      <div class="col-md-12 col-sm-12 text-xs-center text-md-right floatright">
        <?php
          $header    = "Report Billing Summary ( ".$repname." )";
          $dateprint = $v_print_date;
          $printby   = report_get_data_agent($condb, $v_agentid);
          
          $x                 = 0;
          $params[$x]      = "Period : ".$date_from." to ".$date_to;
          $x++;
          $params[$x]      = "Group Name : ".report_get_data_group($condb, $vgroup_id);
          $x++;
          $params[$x]      = "Agent Name : ".report_get_data_agent($condb, $vagent_id);
          $x++;
          
          echo view_header_report($header, $params, $dateprint, $printby, $x)

        ?>
      </div>
    </div>
        <div class="card-body collapse in">
          
            <table id="datatablelist" class="table table-striped table-bordered dataex-html5-export">

    <?php
//By Provider
if($cmbtype=="1"){


      echo "<thead>";

      $x               = 0;
      $bodycontent[$x]= "No"; 
      $typespan[$x]   = "row";
      $span[$x]   = "";
      $x++;

      $bodycontent[$x]= "Trunk"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Provider"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Total Call"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Average Talk Time"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Total Talk Duration"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Rate Per Second"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Cost"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      echo get_tr_report($bodycontent, $typespan, $span, $x);

     

      echo "</thead>";

      echo "<tbody>";
        $no        = 1;

/*
        $array_prefixprovider[] = "";
        $array_prefixrates[] = "";
        $sql_str1x = "SELECT prefix_no,operator,rates FROM cc_prefix_no";
        $sql_res1x = mysqli_query($condb, $sql_str1x);
        while($sql_rec1x = mysqli_fetch_array($sql_res1x)) {
          $array_prefixprovider[$sql_rec1x["prefix_no"]] = $sql_rec1x["operator"];
          $array_prefixrates[$sql_rec1x["prefix_no"]] = $sql_rec1x["rates"];
        }
        mysqli_free_result($sql_res1x);
echo $vstart_time;
echo $vend_time;
      // */
      //  $sql = "SELECT SUBSTR(a.b_number,2,4) AS phoneprefix,  b.operator, b.rates,a.trunk_number,
      //             ROUND(AVG(a.agent_talk),2) AS avgtalk , SUM(a.agent_talk) AS sumtalk, count(a.id) AS sumcount
      //          FROM cc_call_session a
      //          LEFT OUTER JOIN cc_prefix_no b ON SUBSTR(a.b_number,2,4)=b.prefix_no
      //          WHERE 
      //               a.start_time >='$vstart_time' AND 
      //               a.end_time<='$vend_time' AND 
      //               a.`status`='3005'
      //           GROUP BY  SUBSTR(a.b_number,2,4)
      //           ORDER BY b.operator ASC  ";

       $sql = "SELECT 'XL' AS trunkname,if(SUBSTR(a.b_number,1,1)='9',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4)) AS phoneprefix, b.operator, b.rates,a.trunk_number,ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk, SUM(a.agent_talk+a.agent_hold) AS sumtalk, COUNT(a.id) AS sumcount
               FROM cc_call_session a
               LEFT OUTER JOIN cc_prefix_no b ON if(SUBSTR(a.b_number,1,1)='9',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4))=b.prefix_no
               WHERE 
                    a.start_time >='$vstart_time' AND 
                    a.end_time <='$vend_time' AND
                    a.`status`='3005' AND a.trunk_number IN (".$array_trunkid['XL'].") AND
                    LENGTH(a.b_number)>5
               GROUP BY if(SUBSTR(a.b_number,1,1)='9',SUBSTR(a.b_number,1,4),SUBSTR(a.b_number,4,4))       
                    UNION ALL                
			SELECT 'Commsol' AS trunkname,if(SUBSTR(a.b_number,1,1)='8',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4)) AS phoneprefix, b.operator, b.rates,a.trunk_number,ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk, SUM(a.agent_talk+a.agent_hold) AS sumtalk, COUNT(a.id) AS sumcount
               FROM cc_call_session a
               LEFT OUTER JOIN cc_prefix_no b ON if(SUBSTR(a.b_number,1,1)='8',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4))=b.prefix_no
               WHERE 
                    a.start_time >='$vstart_time' AND 
                    a.end_time<='$vend_time' AND 
                    a.`status`='3005' AND a.trunk_number IN (".$array_trunkid['COMMSOL'].")
                    AND LENGTH(a.b_number)>5
               GROUP BY if(SUBSTR(a.b_number,1,1)='8',SUBSTR(a.b_number,4,4),SUBSTR(a.b_number,4,4))  
                    UNION ALL                
               SELECT 'TeleSave' AS trunkname,if(SUBSTR(a.b_number,1,1)='6',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4)) AS phoneprefix, b.operator, b.rates,a.trunk_number,ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk, SUM(a.agent_talk+a.agent_hold) AS sumtalk, COUNT(a.id) AS sumcount
               FROM cc_call_session a
               LEFT OUTER JOIN cc_prefix_no b ON if(SUBSTR(a.b_number,1,1)='6',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4))=b.prefix_no
               WHERE 
                    a.start_time >='$vstart_time' AND 
                    a.end_time<='$vend_time' AND 
                    a.`status`='3005' AND a.trunk_number IN (".$array_trunkid['TELESAVE'].")
                    AND LENGTH(a.b_number)>5
               GROUP BY if(SUBSTR(a.b_number,1,1)='6',SUBSTR(a.b_number,1,4),SUBSTR(a.b_number,4,4))
                    UNION ALL
               SELECT 'Quiros' AS trunkname,if(SUBSTR(a.b_number,1,1)='7',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4)) AS phoneprefix, b.operator, b.rates,a.trunk_number,ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk, SUM(a.agent_talk+a.agent_hold) AS sumtalk, COUNT(a.id) AS sumcount
               FROM cc_call_session a
               LEFT OUTER JOIN cc_prefix_no b ON if(SUBSTR(a.b_number,1,1)='7',SUBSTR(a.b_number,2,4),SUBSTR(a.b_number,4,4))=b.prefix_no
               WHERE 
                    a.start_time >='$vstart_time' AND 
                    a.end_time<='$vend_time' AND 
                    a.`status`='3005' AND a.trunk_number IN (".$array_trunkid['QUIROS'].")
                    AND LENGTH(a.b_number)>5
               GROUP BY if(SUBSTR(a.b_number,1,1)='7',SUBSTR(a.b_number,1,4),SUBSTR(a.b_number,4,4))
		UNION ALL
	       SELECT 'SME' AS trunkname,SUBSTR(a.b_number,4,4) AS phoneprefix, b.operator, b.rates,a.trunk_number,ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk, SUM(a.agent_talk+a.agent_hold) AS sumtalk, COUNT(a.id) AS sumcount
               FROM cc_call_session a
               LEFT OUTER JOIN cc_prefix_no b ON SUBSTR(a.b_number,4,4)=b.prefix_no
               WHERE
                    a.start_time >='$vstart_time' AND
                    a.end_time<='$vend_time' AND
                    a.`status`='3005' AND a.trunk_number IN (".$array_trunkid['SME'].")
                    AND LENGTH(a.b_number)>5
               GROUP BY SUBSTR(a.b_number,4,4)
               ";//echo "string $sql";
       $res = mysqli_query($condb, $sql);
      
       while($rec = mysqli_fetch_array($res)) {
          $phoneprefix = $rec['phoneprefix'];
          $avgtalk     = $rec['avgtalk'];
          $sumtalk     = $rec['sumtalk'];
          $sumcount    = $rec['sumcount'];
          $operator    = $rec['operator'];
          $rates       = $rec['rates'];
          $trunk_number   = $rec['trunk_number'];
          $trunkname   = $rec['trunkname'];

         // $trunkname = "XL";
         //  if ($trunk_number>12) {
         //    $trunname     = "Commsol";
         //  }
        // $providers = $array_prefixprovider[$phoneprefix];

          $total_cost = number_format($sumtalk*$rates);

                echo "<tr>
                        <td>".$no."</td>
                        <td>".$trunkname."</td>
                        <td>".$operator."</td>
                        <td>".$sumcount."</td>
                        <td>".$avgtalk."</td>
                        <td>".$sumtalk."</td>
                        <td>Rp. ".$rates."</td>
                        <td>Rp. ".$total_cost."</td>";
             
                echo "</tr>";  

              $no++;
           

          //}

    
       }        
       
      echo "</tbody>";

}//By Extension
elseif($cmbtype=="3"){



echo "<thead>";

$x               = 0;
$bodycontent[$x]= "No"; 
$typespan[$x]   = "row";
$span[$x]   = "";
$x++;

$bodycontent[$x]= "Voip Name"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Extension"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Total Call"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;


$bodycontent[$x]= "Average Talk Time"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Total Talk Duration"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Rate Per Second"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Cost"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

echo get_tr_report($bodycontent, $typespan, $span, $x);



echo "</thead>";

echo "<tbody>";
  $no        = 1;


 // $sql = "SELECT  a.agent_ext,c.rates, c.operator,
 //            ROUND(AVG(a.agent_talk),2) AS avgtalk , SUM(a.agent_talk) AS sumtalk, count(a.id) AS sumcount
 //           FROM cc_call_session a
 //             LEFT OUTER JOIN cc_prefix_no c ON SUBSTR(a.b_number,2,4)=c.prefix_no
 //           WHERE a.start_time >='$vstart_time' AND 
 //                  a.end_time<='$vend_time'
 //                  AND a.`status`='3005'
 //                  GROUP BY  a.agent_ext";
 $sql = "SELECT  d.trunk_name,a.agent_ext,c.rates, c.operator,
            ROUND(AVG(a.agent_talk+a.agent_hold),2) AS avgtalk , SUM(a.agent_talk+a.agent_hold) AS sumtalk, count(a.id) AS sumcount
           FROM cc_call_session a
             LEFT OUTER JOIN cc_prefix_no c ON SUBSTR(a.b_number,4,4)=c.prefix_no,
             cc_pbx_sip_trunk d
           WHERE a.start_time >='$vstart_time' AND 
                  a.end_time<='$vend_time'
                  AND a.`status`='3005'AND a.trunk_number=d.id
                  GROUP BY a.trunk_number,a.agent_ext";
 $res = mysqli_query($condb, $sql);

 while($rec = mysqli_fetch_array($res)) {
    $trunk_name    = $rec['trunk_name'];
    $agent_ext    = $rec['agent_ext'];
    $avgtalk     = $rec['avgtalk'];
    $sumtalk     = $rec['sumtalk'];
    $sumcount    = $rec['sumcount'];
    $agent_name  = $rec['agent_name'];
    $rates       = $rec['rates'];
    $operator    = $rec['operator'];

    $username = $agent_ext;

   $trunkname = "XL";
  // $providers = $array_prefixprovider[$phoneprefix];

    $total_cost = number_format($sumtalk*$rates);

          echo "<tr>
                  <td>".$no."</td>
                  <td>".$trunk_name."</td>
                  <td>".$username."</td>
                  <td>".$sumcount."</td>
                  <td>".$avgtalk."</td>
                  <td>".$sumtalk."</td>
                  <td>Rp. ".$rates."</td>
                  <td>Rp. ".$total_cost."</td>";
       
          echo "</tr>";  

        $no++;
     

    //}


 }        
 
echo "</tbody>";


}
else{
//By User

echo "<thead>";

$x               = 0;
$bodycontent[$x]= "No"; 
$typespan[$x]   = "row";
$span[$x]   = "";
$x++;

$bodycontent[$x]= "User"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Total Call"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;


$bodycontent[$x]= "Average Talk Time"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Total Talk Duration"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Rate Per Second"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

$bodycontent[$x]= "Cost"; 
$typespan[$x]   = "row";
$span[$x]     = "";
$x++;

echo get_tr_report($bodycontent, $typespan, $span, $x);



echo "</thead>";

echo "<tbody>";
  $no        = 1;


 $sql = "SELECT  b.agent_id, b.agent_name, c.rates, c.operator,
            ROUND(AVG(a.agent_talk),2) AS avgtalk , SUM(a.agent_talk) AS sumtalk, count(a.id) AS sumcount
           FROM cc_call_session a
           LEFT OUTER JOIN cc_agent_profile b ON b.id=a.agent_id
             LEFT OUTER JOIN cc_prefix_no c ON SUBSTR(a.b_number,2,4)=c.prefix_no
           WHERE a.start_time >='$vstart_time' AND 
                  a.end_time<='$vend_time'
                  AND a.`status`='3005'
                  GROUP BY  b.id
                  ORDER BY b.agent_id, b.agent_name ASC ";
 $res = mysqli_query($condb, $sql);

 while($rec = mysqli_fetch_array($res)) {
    $agent_id    = $rec['agent_id'];
    $avgtalk     = $rec['avgtalk'];
    $sumtalk     = $rec['sumtalk'];
    $sumcount    = $rec['sumcount'];
    $agent_name  = $rec['agent_name'];
    $rates       = $rec['rates'];
    $operator    = $rec['operator'];

    $username = $agent_id." / ".$agent_name;

   $trunkname = "XL";
  // $providers = $array_prefixprovider[$phoneprefix];

    $total_cost = number_format($sumtalk*$rates);

          echo "<tr>
                  <td>".$no."</td>
                  <td>".$username."</td>
                  <td>".$sumcount."</td>
                  <td>".$avgtalk."</td>
                  <td>".$sumtalk."</td>
                  <td>Rp. ".$rates."</td>
                  <td>Rp. ".$total_cost."</td>";
       
          echo "</tr>";  

        $no++;
     

    //}


 }        
 
echo "</tbody>";

}
    ?>
		</table>
            <br><br><br><br><br><br>  
          </div>
        </div>
      </div>
    </div>
  </div>
  
   <footer class="footer footer-static">
      <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright  &copy; <?php echo date("Y"); ?> <a href="<?php echo $X_WEB_ADDR;?>" target="_blank" class="text-bold-800 grey darken-2"><?php echo $X_WEB_DESC;?> </a>, All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block">Print Key : <?php echo uuid();?></span></p>
    </footer>

    <!-- ////////////////////////////////////////////////////////////////////////////-->
 <!-- BEGIN VENDOR JS-->
    
    <script src="../../assets/report/js/core/libraries/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../assets/report/vendors/js/ui/jquery.sticky.js"></script>
    <script src="../../assets/report/vendors/js/tables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/datatable/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/datatable/buttons.bootstrap4.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/jszip.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/pdfmake.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/vfs_fonts.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/buttons.html5.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/buttons.print.min.js" type="text/javascript"></script>
    <script src="../../assets/report/vendors/js/tables/buttons.colVis.min.js" type="text/javascript"></script>
  
<script>

    
$(document).ready(function() {
    var table = $('#datatablelist').DataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,
    "ordering": false,
    "bAutoWidth": false,
    dom: '<"top"i>rt<"bottom"Bflp><"clear">',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5',
        'print'
    ] });
     
} );
</script>
