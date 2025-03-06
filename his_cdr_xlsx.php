<?php
###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 20-02-2025    |   Create   |  1.2002.2025                                                                 #
############################################################################################################### 

include "../../sysconf/con_reff.php";
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
include "global_func_report.php";

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
set_time_limit(0);          # unlimited transfer time

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

  //array reason
  // $array_reason[] = "";
  // $sql_str1 = "SELECT a.id, a.aux_code FROM cc_aux_reason a";
  // $sql_res1 = mysqli_query($condb, $sql_str1);
  // while($sql_rec1 = mysqli_fetch_array($sql_res1)) {
  //   $array_reason[$sql_rec1["id"]]   = $sql_rec1["aux_code"];
  // }
  // mysqli_free_result($sql_res1);

  // array group
  $totgrp = 0;
  $sql_str1x = "select a.id, a.group_id, a.group_name from cc_group_profile a";
  $sql_res1x = mysqli_query($condb, $sql_str1x);
  while($sql_rec1x = mysqli_fetch_array($sql_res1x)) {
    $id = $sql_rec1x["id"];
    $array_groupid[$id]   = $sql_rec1x["id"];
    $array_groupname[$id] = $sql_rec1x["group_name"];
    $array_group_id[$id] = $sql_rec1x["group_id"];
    $totgrp++;
  }
  mysqli_free_result($sql_res1x);


// $sqld = " SELECT a.buck_id, TIMEDIFF(b.end_time, b.start_time) as duration 
// FROM cc_teleupload_call_session a
// LEFT JOIN cc_call_session b ON (a.ref_id=b.assign_id)
// WHERE a.insert_time >= '".$vstart_time."' AND a.insert_time <='".$vend_time."'
// AND b.start_time >= '".$vstart_time."' AND b.start_time <='".$vend_time."' ";
// $resd = mysqli_query($condb, $sqld);
// while ($row = mysqli_fetch_array($resd)) {

//     $buck_id = $row["buck_id"];
//     $call_duration = $row["duration"];
//     $arrdur[$buck_id] = $call_duration;

// }
// mysqli_free_result($resd);


error_reporting(E_ALL);
ini_set('display_errors', False);
ini_set('display_startup_errors', False);
date_default_timezone_set('Asia/Jakarta');

/** Include PHPExcel */
require_once  '../../library/PHPExport/php-export-data.class.php';

$exporter = new ExportDataExcel('browser', 'cdr.xls');

$exporter->initialize(); // starts streaming data to web browser

// echo "<pre>";

$exporter->addRow(array("No",
  "Session ID",
  "Call ID",
  "Start Time",
  "End Time",
  "Server ID",
  "Trunk Number",
  "Trunk Member",
  "a Number",
  "b Number",
  "Agent ID",
  "Agent Name",
  "Agent EXT",
  "Agent Ring",
  "Agent Dial",
  "Agent Talk",
  "Agent Hold",
  "Total Hold",
  "Agent Mute",
  "Total Mute",
  "Disconnected By",
  "Status Desc" ));
        
$no  = 1;
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
              AND a.direction = '2' "; //echo $sql;//AND a.call_status IN (1,2) AND a.id_teledata=66097
$res  = mysqli_query($condb, $sql);
while($rec = mysqli_fetch_array($res)) {
$call_duration=0;
        
$session_id             = mysqli_real_escape_string($condb, $rec['session_id']);
$call_id                = mysqli_real_escape_string($condb, $rec['call_id']);
$start_time             = mysqli_real_escape_string($condb, $rec['start_time']);
$end_time               = mysqli_real_escape_string($condb, $rec['end_time']);
$server_id              = mysqli_real_escape_string($condb, $rec['server_id']);
$trunk_number           = mysqli_real_escape_string($condb, $rec['trunk_number']);
$trunk_member           = mysqli_real_escape_string($condb, $rec['trunk_member']);
$a_number               = mysqli_real_escape_string($condb, $rec['a_number']);
$b_number               = mysqli_real_escape_string($condb, $rec['b_number']);
$agent_id               = mysqli_real_escape_string($condb, $rec['agent_id']);
$agent_name             = mysqli_real_escape_string($condb, $rec['agent_name']);
$agent_ext              = mysqli_real_escape_string($condb, $rec['agent_ext']);
$agent_ring             = mysqli_real_escape_string($condb, $rec['agent_ring']);
$agent_dial             = mysqli_real_escape_string($condb, $rec['agent_dial']);
$agent_talk             = mysqli_real_escape_string($condb, $rec['agent_talk']);
$agent_hold             = mysqli_real_escape_string($condb, $rec['agent_hold']);
$total_hold             = mysqli_real_escape_string($condb, $rec['total_hold']);
$agent_mute             = mysqli_real_escape_string($condb, $rec['agent_mute']);
$total_mute             = mysqli_real_escape_string($condb, $rec['total_mute']);
$disconnected_by_desc   = mysqli_real_escape_string($condb, $rec['disconnected_by_desc']);
$status_desc            = mysqli_real_escape_string($condb, $rec['status_desc']);
            
            $exporter->addRow(array(
    $no,
    $session_id,
    $call_id,
    $start_time,
    $end_time,
    $server_id,
    $trunk_number,
    $trunk_member,
    $a_number,
    $b_number,
    $agent_id,
    $array_agentname[$agent_id],
    $agent_ext,
    $agent_ring,
    $agent_dial,
    $agent_talk,
    $agent_hold,
    $total_hold,
    $agent_mute,
    $total_mute,
    $disconnected_by_desc,
    $status_desc));

                $no++;  

              
            // }
}
        mysqli_free_result($res);

        $exporter->finalize(); // writes the footer, flushes remaining data to browser.
        
        disconnectDB($condb);
        
        exit(); // all done
       
// echo "</pre>";
?>