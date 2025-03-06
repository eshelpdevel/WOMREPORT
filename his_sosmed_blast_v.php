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
// $vgroup_id    = get_param("rpt_group");
// $vagent_id    = get_param("rpt_user");
// $vgroup_id    = get_param("rpt_group");
$vgroup_id    = get_param("rpt_group");
// $vagent_id    = get_param("rpt_user");
$vagent_id    = get_param("rpt_user");
$type         = get_param('type');

$date_from    = substr($dateperiod,0,10);
$date_to      = substr($dateperiod,12);
$vstart_time  = $date_from." 00:00:00";
$vend_time    = $date_to." 23:59:59";
$preparedby   = $v_agentname;

 $sql = "DELETE FROM cc_report_all_session WHERE created_by = '$v_agentid'";
 mysqli_query($condb, $sql);

 //array agent
  $array_agent_id[]   = "";
  $array_agent_name[] = "";
  $sql_str1 = "SELECT a.id, a.agent_id, a.agent_name FROM cc_agent_profile a";
  $sql_res1 = mysqli_query($condb, $sql_str1);
  while($sql_rec1 = mysqli_fetch_array($sql_res1)) {
    $array_agent_id[$sql_rec1["id"]]   = $sql_rec1["agent_id"];
    $array_agent_name[$sql_rec1["id"]] = $sql_rec1["agent_name"];
  }
  mysqli_free_result($sql_res1); 

  // array group
  $array_groupid[] = "";
  $sql_str1x = "select a.id, a.group_name from cc_group_profile a";
  $sql_res1x = mysqli_query($condb, $sql_str1x);
  while($sql_rec1x = mysqli_fetch_array($sql_res1x)) {
    $array_groupid[$sql_rec1x["id"]] = $sql_rec1x["group_name"];
  }
  mysqli_free_result($sql_res1x);

$sqlwhere = "";
// if ($vgroup_id != "") {
//    $sqlwhere .= "AND 
//                  a.group_id = '$vgroup_id' "; 
// }
if ($vagent_id != "") {
   $sqlwhere .= " AND a.agent_id = '".$vagent_id."' "; 
}
    
  function get_first_response($live_time, $out_time) {
    $first = "";
    
    // $datetime1 = new DateTime($out_time);
    // $datetime2 = new DateTime($live_time);
    // $interval = $datetime1->diff($datetime2);
    // $first = $interval->format('%H:%I:%S');

    $date = new DateTime($live_time);
    $date2 = new DateTime($out_time);

    $first = $date2->getTimestamp() - $date->getTimestamp();
    
    return $first;

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
          $header    = "Report Social Media Blast";
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
          <div style="width:100%;overflow-x: auto">
            <table id="datatablelist" class="table table-striped table-bordered dataex-html5-export">

    <?php
      $x                 = 0;

      $bodycontent[$x]   = "No."; $x++;
      $bodycontent[$x]   = "Channel."; $x++;
      $bodycontent[$x]   = "Blast Name"; $x++;
      $bodycontent[$x]   = "Template Category"; $x++;
      $bodycontent[$x]   = "Distribution Type"; $x++;
      $bodycontent[$x]   = "Created By"; $x++;
      $bodycontent[$x]   = "Assigned To"; $x++;
      $bodycontent[$x]   = "Created Time"; $x++;
      $bodycontent[$x]   = "Saved Time"; $x++;
      $bodycontent[$x]   = "Scheduled Time"; $x++;
      $bodycontent[$x]   = "Processed Time"; $x++;
      $bodycontent[$x]   = "Total Participant"; $x++;

      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;

      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      $bodycontent[$x]   = ""; $x++;
      
      echo get_thead_report($bodycontent, $x);
      echo "<tbody>";
       $no        = 1;
       $groupname = "";
       $agentname = "";
       $tot_first_sec = 0;
       $tot_duration_sec = 0;
       $tot_sess = 0;
       $sql = "SELECT 
                a.id,
                b.channel_name as channel,
                a.blast_name,
                a.close_remark,
                CONCAT(c.agent_name,' / ',c.agent_id) as created_by,
                a.created_time,
                a.process_time,
                a.close_time,
                a.schedule_time,
                count(d.id) as participants,
                e.category_id,
                CONCAT(f.agent_name,' / ',f.agent_id) as assigned_to
               from cc_sosmed_blast a
               left join cc_ticket_channel b on a.channel = b.id
               left join cc_agent_profile c on a.created_by = c.id
               left join cc_sosmed_blast_participant d on d.blast_id = a.id
               left join cc_wa_template e on a.hsm_template = e.template_id
               left join cc_agent_profile f on a.assign_id = f.id
               WHERE a.`status` = '1' AND a.created_time >= '".$vstart_time."' 
               AND a.created_time <= '".$vend_time."'
               group by a.id"; 

      $res                    = mysqli_query($condb, $sql);
      while($rec              = mysqli_fetch_array($res)){
        $id                   = $rec["id"];
        $channel              = $rec["channel"];
        $blast_name           = $rec["blast_name"];
        $close_remark         = $rec["close_remark"];
        $created_by           = $rec["created_by"];
        $created_time         = $rec["created_time"];
        $process_time         = $rec["process_time"];
        $close_time           = $rec["close_time"];
        $schedule_time        = $rec["schedule_time"];
        $participants        = $rec["participants"];
        $category_id        = $rec["category_id"];
        $assigned_to        = $rec["assigned_to"];

        
        echo "<tr>
              <td>".$no.". </td>
              <td>".$channel."</td>
              <td>".$blast_name."</td>
              <td>".$category_id."</td>
              <td>".ucwords($close_remark)."</td>
              <td>".$created_by."</td>
              <td>".$assigned_to."</td>
              <td>".$created_time."</td>
              <td>".$process_time."</td>
              <td>".$close_time."</td>
              <td>".$schedule_time."</td>
              <td>".$participants." Participants</td>

              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>

               <td></td>
                <td></td>
                 <td></td>
              </tr>";
              echo "
                      <tr>
                        <td></td>
                        <td style=\"font-weight:bold\"></td>
                        <td style=\"font-weight:bold\"></td>
                        <td style=\"font-weight:bold\"></td>
                        <td style=\"font-weight:bold\"></td>
                        <td style=\"font-weight:bold\">No.</td>
                        <td style=\"font-weight:bold\">Participant</td>
                        <td style=\"font-weight:bold\">Destination</td>
                        <td style=\"font-weight:bold\">Process time</td>
                        <td style=\"font-weight:bold\">Request Status</td>
                        <td style=\"font-weight:bold\">Response Status</td>

                        <td style=\"font-weight:bold\">Sent time</td>
                        <td style=\"font-weight:bold\">Delivered time</td>
                        <td style=\"font-weight:bold\">Read time</td>

                        <td style=\"font-weight:bold\">Call Status</td>

                         <td style=\"font-weight:bold\">Tahun</td>
                         <td style=\"font-weight:bold\">Unit</td>
                         <td style=\"font-weight:bold\">Cabang</td>
                         <td style=\"font-weight:bold\">CusId</td>
                         <td style=\"font-weight:bold\">Order No</td>
                         <td style=\"font-weight:bold\">Label</td>
                         <td style=\"font-weight:bold\">Produk</td>
                         <td style=\"font-weight:bold\">Nominal Denda</td>
                         <td style=\"font-weight:bold\">DPD</td>
                         <td style=\"font-weight:bold\">No Polisi</td>
                         <td style=\"font-weight:bold\">Jatuh Tempo</td>
                         <td style=\"font-weight:bold\">Angsuran ke-</td>

                      </tr>
                    ";
              $nox=1;
              $sql2 = "SELECT 
                          RIGHT(username,4) as username,
                          a.usercontactname,
                          a.created_time,
                          CASE
                              WHEN b.sent_status = 1 THEN \"Success\"
                              WHEN b.sent_status = 2 THEN \"Failed\"
                              WHEN b.sent_status = 0 THEN \"Queue\"
                              ELSE \"\"
                          END as request_status,
                          a.outbox_id,
                          CONCAT('') as teleuploadinfo,
                          a.tahun,
                          a.unit,
                          a.cabang,
                          a.cusid,
                          a.orderno,
                          a.label,
                          a.produk,
                          a.nominaldenda,
                          a.dpd,
                          a.nopolisi,
                          a.jatuhtempo,
                          a.angsuranke,
                          b.sent_status,
                          b.sent_code,
                          b.sent_time,
                          b.delivered_time,
                          b.read_time,
                          b.pushed_remark
                    FROM cc_sosmed_blast_participant a  
                    LEFT JOIN cc_wa_outbox b on a.outbox_id = b.id
                    
                    
                    where a.blast_id = '$id' and a.status = 1
                    group by a.id"; 
                    /*
                    CASE
                              WHEN b.sent_status = 1 THEN 
                                GROUP_CONCAT(CONCAT(IF(c.title = '',c.status,c.title),' at ', c.message_time) separator '<br />')
                              WHEN b.sent_status = 2 THEN b.sent_code
                              WHEN b.sent_status = 0 THEN \"Waiting for Respsonse Status\"
                              ELSE \"\"
                          END as response_status,
                    LEFT JOIN cc_wa_ack c on c.report_id = b.sent_code


                    if(a.user_upload_referenceid = 0,'Manual',IF(d.last_followup_call = 0,'No Status',e.call_status)) as teleuploadinfo,

                    LEFT JOIN cc_teleupload_data d ON d.id = a.user_upload_referenceid
                    LEFT JOIN cc_ts_call_status e ON d.last_followup_call = e.id
                    */

              $res2                       = mysqli_query($condb, $sql2);
              while($rec2                 = mysqli_fetch_array($res2)){
                $username                 = $rec2["username"];
                $usercontactname          = $rec2["usercontactname"];
                $created_time             = $rec2["created_time"];
                $request_status           = $rec2["request_status"];
                $outbox_id                = $rec2["outbox_id"];
                $sent_status              = $rec2["sent_status"];
                $sent_code                = $rec2["sent_code"];

                $sent_time           = $rec2["sent_time"];
                $delivered_time           = $rec2["delivered_time"];
                $read_time           = $rec2["read_time"];
                $pushed_remark           = $rec2["pushed_remark"];

                $response_status          = "";
                if ($sent_status == 1) {
                    $queryT = "SELECT c.status,c.message_time FROM cc_wa_ack c WHERE c.report_id = '$sent_code'";
                    $resultT = mysqli_query($condb,$queryT);
                    if ($resultT) {
                      while ($rowT     = mysqli_fetch_row($resultT)){
                        $response_status    = $rowT[0];
                        $message_time    = $rowT[1];

                        if ($response_status == 'sent') {
                          $sent_time = $message_time;
                        }elseif ($response_status == 'delivered') {
                          $delivered_time = $message_time;
                        }elseif ($response_status == 'read') {
                          $read_time = $message_time;
                        }
                        /*
                        $sent_time           = $rec["sent_time"];
                      $delivered_time           = $rec["delivered_time"];
                      $read_time           = $rec["read_time"];
                      */
                        
                      }
                      mysqli_free_result($resultT);
                    }
                    $response_status = $pushed_remark;
                }elseif ($sent_status == 2) {
                  $response_status = $sent_code;
                }elseif ($sent_status == 0) {
                  $response_status = "Waiting for Response Status";
                }else{
                  $response_status = " - ";
                }
                
                if ($response_status=='') {
                    $response_status = $pushed_remark;
                }

                $teleuploadinfo           = $rec2["teleuploadinfo"];

                $tahun                    = $rec2["tahun"];
                $unit                     = $rec2["unit"];
                $cabang                   = $rec2["cabang"];
                $cusid                    = $rec2["cusid"];
                $orderno                  = $rec2["orderno"];
                $label                    = $rec2["label"];
                $produk                   = $rec2["produk"];
                $nominaldenda             = $rec2["nominaldenda"];
                $dpd                      = $rec2["dpd"];
                $nopolisi                 = $rec2["nopolisi"];
                $jatuhtempo               = $rec2["jatuhtempo"];
                $angsuranke               = $rec2["angsuranke"];

                echo "<tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>".$nox.". </td>
                      <td>".$usercontactname."</td>
                      <td>*******".$username."</td>
                      <td>".$created_time."</td>
                      <td>".$request_status."</td>
                      <td>".$response_status."</td>

                      <td>".$sent_time."</td>
                      <td>".$delivered_time."</td>
                      <td>".$read_time."</td>

                      <td>".$teleuploadinfo."</td>

                      <td>".$tahun."</td>
                      <td>".$unit."</td>
                      <td>".$cabang."</td>
                      <td>".$cusid."</td>
                      <td>".$orderno."</td>
                      <td>".$label."</td>
                      <td>".$produk."</td>
                      <td>".$nominaldenda."</td>
                      <td>".$dpd."</td>
                      <td>".$nopolisi."</td>
                      <td>".$jatuhtempo."</td>
                      <td>".$angsuranke."</td>
                      </tr>";
                      $nox++;

             }

       $no++;
       }


      echo "</tbody>";
    ?>
    </table>
  </div>
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
      {
        extend: 'csv',
        filename: 'Report Blast'
      },
      {
      extend: 'excel',
      charset: 'UTF-8',
      stripHtml: false,
      filename: 'Report Blast',
      title: null,
      exportOptions: {
          format: {
              body: function (data, row, column, node ) {
                var val = data
                if(row == 13 || row == 14 || row == 15 || row == 16 || row == 17 || row == 18 || row == 19) {
                  var data = data.replace('&amp;','dan');
                  val = data ? "\0" + data : data;
                } 
               /* if(row == 13 || row == 14 || row == 15 || row == 16 || row == 17 || row == 18) {
                  data = data.replace(/&amp;lt;/g,"(");
                  data = data.replace(/&amp;gt;/g,")");
                  data = data.replace(/&amp;nbsp;/g,"");
                  data = data.replace(/&amp;/g,"");
                  data = data.replace(/&nbsp;/g,"");
                  data = data.replace(/&lt;/g, "(");
                  data = data.replace(/&gt;/g, ")");
                  val=data
                } */
                // return row === 9 ? "\0" + data : data;
                return val;
              }
            }
         }
    },
      {
        extend: 'pdf',
        filename: 'Report Blast'
      },
      {
        extend: 'copy',
        filename: 'Report Blast'
      },
      {
        extend: 'print',
        filename: 'Report Blast'
      }
    ] });
     
} );
</script>
