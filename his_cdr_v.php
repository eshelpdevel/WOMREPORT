<?php
###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 20-02-2025    |   Create   |  2.2002.2025                                                                 #
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
$trunk_id     = get_param("trunk_id");

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
  $totag = 0;
  $sql_str1 = "SELECT a.id, a.agent_id, a.agent_name FROM cc_agent_profile a";
  $sql_res1 = mysqli_query($condb, $sql_str1);
  while($sql_rec1 = mysqli_fetch_array($sql_res1)) {
    $id = $sql_rec1["id"];
    $array_agentid[$id]   = $sql_rec1["id"];
    $array_agentname[$id] = $sql_rec1["agent_name"];
    $array_contact_id[$id] = $sql_rec1["agent_id"];
    $totag++;
  }
  mysqli_free_result($sql_res1); 


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
          $header    = "Report CDR";
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


      echo "<thead>";

      $x               = 0;
      $bodycontent[$x]= "No"; 
      $typespan[$x]   = "row";
      $span[$x]   = "";
      $x++;

      $bodycontent[$x]= "Session ID"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Call ID"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Start Time"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "End Time"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Server ID"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Trunk Number"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Trunk Member"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "a Number"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "b Number"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent ID"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Name"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent EXT"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Ring"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Dial"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Talk"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Hold"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Total Hold"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Agent Mute"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Total Mute"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Disconnected By"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      $bodycontent[$x]= "Status Desc"; 
      $typespan[$x]   = "row";
      $span[$x]     = "";
      $x++;

      echo get_tr_report($bodycontent, $typespan, $span, $x);

     

      echo "</thead>";

      echo "<tbody>";
        $no        = 1;

       $sql = "SELECT 
              a.session_id, 
              a.call_id, 
              a.start_time, 
              a.end_time,
              a.server_id, 
              a.trunk_number, 
              a.trunk_member, 
              a.a_number, 
              a.b_number, 
              a.agent_id, 
              a.agent_ext, 
              a.agent_ring, 
              a.agent_dial, 
              a.agent_talk, 
              a.agent_hold, 
              a.total_hold, 
              a.agent_mute, 
              a.total_mute, 
              CASE 
                  WHEN a.disconnected_by = 1 THEN 'Caller'
                  WHEN a.disconnected_by = 2 THEN 'Called'
                  WHEN a.disconnected_by = 3 THEN 'System'
              END AS disconnected_by_desc, 
              CASE 
                  WHEN a.status = 3003 THEN 'No answer'
              WHEN a.status = 3004 THEN 'Connected'
                    WHEN a.status = 3005 THEN 'Answer'
                    WHEN a.status = 3007 THEN 'Originated'
                END AS status_desc
              FROM 
                  cc_call_session a
              WHERE 
              a.trunk_number = '$trunk_id' 
              AND a.start_time >= '$vstart_time' 
              AND a.start_time <= '$vend_time' 
              AND a.direction = '2';
               ";//echo "string $sql";
       $res = mysqli_query($condb, $sql);
      
       while($rec = mysqli_fetch_array($res)) {
          // $no                     = $rec['no'];
          $session_id             = $rec['session_id'];
          $call_id                = $rec['call_id'];
          $start_time             = $rec['start_time'];
          $end_time               = $rec['end_time'];
          $server_id              = $rec['server_id'];
          $trunk_number           = $rec['trunk_number'];
          $trunk_member           = $rec['trunk_member'];
          $a_number               = $rec['a_number'];
          $b_number               = $rec['b_number'];
          $agent_id               = $rec['agent_id'];
          $agent_name             = $rec['agent_name'];
          $agent_ext              = $rec['agent_ext'];
          $agent_ring             = $rec['agent_ring'];
          $agent_dial             = $rec['agent_dial'];
          $agent_talk             = $rec['agent_talk'];
          $agent_hold             = $rec['agent_hold'];
          $total_hold             = $rec['total_hold'];
          $agent_mute             = $rec['agent_mute'];
          $total_mute             = $rec['total_mute'];
          $disconnected_by_desc   = $rec['disconnected_by_desc'];
          $status_desc            = $rec['status_desc'];

         // $trunkname = "XL";
         //  if ($trunk_number>12) {
         //    $trunname     = "Commsol";
         //  }
        // $providers = $array_prefixprovider[$phoneprefix];

          $total_cost = number_format($sumtalk*$rates);

                echo "<tr>
                        <td>".$no."</td>
                        <td>".$session_id."</td>
                        <td>".$call_id."</td>
                        <td>".$start_time."</td>
                        <td>".$end_time."</td>
                        <td>".$server_id."</td>
                        <td>".$trunk_number."</td>
                        <td>".$trunk_member."</td>
                        <td>".$a_number."</td>
                        <td>".$b_number."</td>
                        <td>".$agent_id."</td>
                        <td>".$array_agentname[$agent_id]."</td>
                        <td>".$agent_ext."</td>
                        <td>".$agent_ring."</td>
                        <td>".$agent_dial."</td>
                        <td>".$agent_talk."</td>
                        <td>".$agent_hold."</td>
                        <td>".$total_hold."</td>
                        <td>".$agent_mute."</td>
                        <td>".$total_mute."</td>
                        <td>".$disconnected_by_desc."</td>
                        <td>".$status_desc."</td>";
             
                echo "</tr>";  

              $no++;
           

          //}

    
       }        
       
      echo "</tbody>";

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
