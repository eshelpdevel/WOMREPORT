<?php
###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 14-02-2025    |   Create   |  1.1402.2025                                                                 #
############################################################################################################### 

include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
include "global_func_report.php";

$condb = connectDB();
$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");

$iddet 			= $library['iddet'];

$ffolder		= $library['folder'];
$fmenu_link		= $library['menu_link'];
$fdescription	= $library['description'];
$fmenu_id		= $library['menu_id'];
$ficon			= $library['icon'];
$fiddet			= $library['iddet'];
$fblist			= $library['blist'];

$fmenu_link_back = "reporting_header_list";


//file save data
$save_form = "view/report/reporting_header_save.php";

if($iddet  == "") {
	$desc_iddet = "Create New";
}else{
	$desc_iddet = "View";
}

 

?>
	<!-- Select2 -->
	<script src="assets/js/plugin/select2/select2.full.min.js"></script>
	<!-- Bootstrap Tagsinput -->
	<script src="assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>
	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo2.js"></script>

<div class="page-inner">
	<div class="content" style="margin-top: 10px;">
		<div class="row">
		
		<!-- table 1 start -->
		<div class="col-md-12">
			<div class="card">
				<div style="margin:10px 10px 10px 10px;">
					<div>

						
							<div class="form-body">		
								<?php
									$txttitle	= "Parameter Report CDR";
		                    		$icofrm	  = "fas fa-list-ul";
		                    		echo title_form_report_det($txttitle,$icofrm, "title_hreport");
									
									$x						 		 = 0;
		        

		                    		$txtlabel[$x]      = "Period";
		                    		$bodycontent[$x]   = input_text_temp("rpt_period","rpt_period",$rpt_period,"Period","required","form-control border-primary rpt_period");
		                    		$x++;

		                    		$sel  = "<SELECT id=\"trunk_id\" name=\"trunk_id\" class=\"select2 form-control trunk_id\" >";
								    $sql = "SELECT a.id,concat(a.trunk_name,' [',b.server_hostname,']') as trunkname 
								            FROM cc_pbx_sip_trunk a 
								            left join cc_server b 
								            on a.server_id=b.id 
								            where a.status=1 
								            ORDER BY id";
								    $sql_res = mysqli_query($condb,$sql);
								    while($sql_rec = mysqli_fetch_array($sql_res)) {
								       if ($sql_rec["id"] == $trunk_id)
								          $sel .= "<option value=\"".$sql_rec["id"]."\" selected>".$sql_rec["trunkname"]."</option>";
								       else 
								          $sel .= "<option value=\"".$sql_rec["id"]."\">".$sql_rec["trunkname"]."</option>";
								    }
								    $sel .= "</SELECT>";

		                    		$txtlabel[$x]      = "Trunk";
		                    		$bodycontent[$x]   = $sel;
		                    		$x++;

		                    		
		                    		echo label_form_det($txtlabel,$bodycontent,$x);
	                    		
								?>

							</div>


					</div>
				</div>
			</div>
		</div>
		<!-- table 1 end -->
		
		</div>
		
	</div>
</div>


</form>
<?php
disconnectDB($condb);

$now 		= DATE("Y-m-d");
$startdate 	= $now;
$enddate 	= $now;
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#rpt_group").change(function() {
		var vgroup_id = $("#rpt_group").val();
		var vctype	  = "rpt_grpid";
		var dataString = 'param1='+vgroup_id+'&rtype='+vctype;	
		$.ajax({
			type: "POST",
			url: "view/report/global_param_report.php",
			data: dataString,
			cache: false,
			success: function(html) {
				$("#rpt_user").html(html);
			} 
		});
	});
});
</script>

<script type="text/javascript">

	$(document).ready(function() {
		$('#cmbuser').select2({
			theme: "bootstrap"
		});
	});	
	// $('.rpt_period').daterangepicker({
	// 	format: 'YYYY-MM-DD',
	// });

	$('.rpt_period').daterangepicker({
		    locale: {
		      format: 'YYYY-MM-DD'
		    },
		    	startDate: '<?php echo $startdate;?>',
		    	endDate: '<?php echo $enddate;?>'
		});
</script>