<?php

###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 20-02-2025    |   Create   |  1.2002.2025                                                                 #
############################################################################################################### 

include "global_func_report.php";

$condb = connectDB();
$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");

?>
	<!-- 
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/atlantis.min.css">
	<link href="assets/styles.css" rel="stylesheet" />
	<link href="assets/prism.css" rel="stylesheet" /> -->

	<link rel="stylesheet" href="assets/js/plugin/webfont/lato.css">
	<link rel="stylesheet" href="assets/js/plugin/webfont/Font-Awesome-master/css/all.css">
	<link rel="stylesheet" href="assets/js/plugin/webfont/simple-line-icons-master/css/simple-line-icons.css">

	<link rel="stylesheet" type="text/css" href="assets/css/pickers/daterange/daterangepicker.css">
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/pickers/datetime/bootstrap-datetimepicker.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/pickers/pickadate/pickadate.css"> -->

<style type="text/css">
	.select2-results__group {
		color: #007bff !important;
		font-weight: bold !important;
		/*background-color: #ededed !important;*/
	}
</style>
    
<form name="frmDataDet" id="frmDataDet" method="POST"><?php $idsec = get_session('idsec'); ?> <input type="hidden" name="idsec" id="idsec" value="<?php echo $idsec;?>">

<div class="page-inner">
	<div class="page-header"  style="margin-bottom:0px;margin-top:-15px;padding-left:0px;padding:0px;margin-left:-20px;">
		<ul class="breadcrumbs" style="border-left:0px;margin:0px;">
			<li class="nav-home">
				<a href="index.php">
					<i class="fas fa-home"></i>
				</a>
			</li>
			<li class="separator">
				<i class="fas fa-chevron-right"></i>
			</li>
			<?php
				$menu_tree = explode("|", $library['page']);
				for ($i=0; $i <count($menu_tree) ; $i++) { 
					if ($i != 0) {
						echo "<li class=\"separator\"><i class=\"fas fa-chevron-right\"></i></li>";
					}
					echo "<li class=\"nav-item\">".$menu_tree[$i]."</li>";;
				}
				echo "<li class=\"separator\"><i class=\"fas fa-chevron-right\"></i></li>";
				echo "<li class=\"nav-item\">".$desc_iddet."</li>";;				
			?>
		</ul>
	</div>
	<div class="content" style="margin-top: 10px;">
		<div class="row">
		
		<!-- table 1 start -->
		<div class="col-md-5">
			<div class="card">
				<div style="margin:10px 10px 10px 10px;">
					<div>

						
							<div class="form-body">		
								<?php
								
									$txttitle	= "Reporting Menu";
		                    		$icofrm	  = "fas fa-list-ul";
		                    		echo title_form_det($txttitle,$icofrm);
									
									$x						 		 = 0;
		        
		                    		$txtlabel[$x]      = "Report List";
		                    		$bodycontent[$x]   = select_menu_report("sel_report","sel_report", $condb);
		                    		$x++;
								/*
		                    		$txtlabel[$x]      = "File Name";
		                     		$bodycontent[$x]   = input_text_temp("sel_filename","sel_filename",$sel_filename,"File Name","required","form-control border-primary sel_filename");;
		                    		$x++;
		                    	*/
							if($reportfile_dev=="1"){
								
									$txtlabel[$x]      = "File Name";
		                     		$bodycontent[$x]   = input_text_temp("sel_filename","sel_filename",$sel_filename,"File Name","required","form-control border-primary sel_filename");;
		                    		$x++;	
		                    		
							} else {
								echo "<input type='hidden' name='sel_filename' id='sel_filename'>";
							}		
		                    			                    		
		                    		
		                    		echo label_form_det($txtlabel,$bodycontent,$x);
		                    		
								?>

							</div>


					</div>
				</div>
			</div>
		</div>
		<!-- table 1 end -->
		
		<!-- table 2 start -->
		<div class="col-md-7">
			<div class="card">
				<div style="margin:5px;">
					<div>

						
							<div class="form-body">		
								<div id="report_param_list"></div>
							</div>

							<div id="div_view" class="card-action" style="text-align:right; margin-right: 2%; padding: 3% !important; display:block;">
								<?php
									echo button_priv_report('1','','', '');
								?>
							</div>

							<div id="div_viewdownload" class="card-action" style="text-align:right; margin-right: 2%; padding: 3% !important; display:none;">
								<?php
									echo button_priv_report('','1','1', '');
								?>
							</div>


					</div>
				</div>
			</div>
		</div>
		
		<!-- table 2 end -->
		
		</div>
		
		
		<!-- <div class="card-action">
			<?php
				//echo button_priv_report('1','','');
			?>
		</div> -->
		
	</div>
</div>
<br><br><br><br><br>

</form>
<?php
disconnectDB($condb);
?>

<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	
	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
	<!-- Bootstrap Toggle -->
	<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Select2 -->
	<script src="assets/js/plugin/select2/select2.full.min.js"></script>
	<!-- jQuery Validation -->
	<script src="assets/js/plugin/jquery.validate/jquery.validate.min.js"></script>
	<!-- Bootstrap Tagsinput -->
	<script src="assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>
	<script src="assets/js/setting.js"></script>
	
	<script src="assets/js/plugin/moment/moment.min.js"></script>
	<script src="assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/plugin/pickers/daterange/daterangepicker.js" type="text/javascript"></script>

	<!-- <script src="assets/js/plugin/select2/select2.full.min.js"></script> -->

<script type="text/javascript">
$(document).ready(function() {
    var filereport = $('#sel_report').val();
	if(filereport == "") {
	 $("#report_param_list").load("view/report/kosong_list.php");
	}
});

$("#sel_report").change(function(){
	var filereport = $('#sel_report').val();
	if(filereport == "") {
	 $("#report_param_list").load("view/report/kosong_list.php");
	 document.getElementById("sel_filename").value = "";
	} else {
	 vsel = document.getElementById("sel_report").value;
	 document.getElementById("sel_filename").value = vsel;
	//  alert(vsel);

// console.log(vsel);
		if(vsel == "his_teleupload_daily_activity") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		} else if(vsel == "his_teleupload_detail") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		} else if(vsel == "his_daily_activity_call") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		} else if(vsel == "his_data_global") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		} else if(vsel == "his_whatsapp_cust_detail") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		} else if(vsel == "his_cdr") {
			document.getElementById("div_viewdownload").style.display = "block";
			document.getElementById("div_view").style.display = "none";
		}  else {
			document.getElementById("div_viewdownload").style.display = "none";
			document.getElementById("div_view").style.display = "block";
		}
	 $("#report_param_list").load("view/report/"+vsel+".php");
	}
});
	$("#btnView1").click(function(){

		var filereport = $('#sel_report').val();
		if(filereport != "") {
			var varS    = $("#frmDataDet").serialize()+'&type=html';
			var parts = varS.split('&');
			var p1 = parts[2];
			if(p1.length == "38") {
				var strdate = p1.substring(21, 11);
				var enddate = p1.substring(38, 28);
			} else {
				var strdate = p1.substring(21, 11);
				var enddate = p1.substring(43, 53);
			}
									
			var p2 = parts[2];
			var gptype = p2.substring(10, 11);

			var periodDate1 = new Date(strdate);
			var periodDate2 = new Date(enddate);    
				if(periodDate2 < periodDate1) {
			swal({ title: "Information!", type: "error",  text: "Start Date < End Date",   timer: 3000,   showConfirmButton: false });
				} else {
					$.ajax({
						type: "POST",
						url: "view/system/trial_report_logs.php",
						cache: false,
						data: { filereport: filereport, strdate:strdate, enddate:enddate, types_desc:"view", varS:varS  },
						success: function(result) {
							//alert(html);
						}
					});
					strdate = strdate.split('-');
					enddate = enddate.split('-');

					strdate = new Date(strdate[0], strdate[1], strdate[2]);
					enddate = new Date(enddate[0], enddate[1], enddate[2]);
					date1_unixtime = parseInt(strdate.getTime() / 1000);
					date2_unixtime = parseInt(enddate.getTime() / 1000);


					var timeDifference = date2_unixtime - date1_unixtime;
					var timeDifferenceInHours = timeDifference / 60 / 60;
					var timeDifferenceInDays = timeDifferenceInHours  / 24;
					
					if (document.getElementById('graphtype') !=null){
						var graphtype = document.getElementById("graphtype").value;
					}else{
						var graphtype = 0;
					}
				if(graphtype==3){
					window.open('view/report/'+filereport+'_v.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
				}else{
					if(parseInt(timeDifferenceInDays) > 31) {
			swal({ title: "Information!", type: "error",  text: "Error: too large period.\n* max 30 days",   timer: 3000,   showConfirmButton: false });
					} else {
						// $.post('etc/param/insert_log.php', {filereport: filereport, d1:strdate, d2:enddate }, function(data) {
						//     $("#dbreportlog").html(data); 
						// });
						window.open('view/report/'+filereport+'_v.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					}
				}
				}
		} else {
			//alert("Select your Report List!");	
			swal({ title: "Information!", type: "error",  text: "Select Your Report List.",   timer: 3000,   showConfirmButton: false });
		}
			//alert(varS);
			return false;    
	});
	
    $("#btnView2").click(function(){

			var filereport = $('#sel_report').val();
			if(filereport != "") {
				var varS    = $("#frmDataDet").serialize()+'&type=html';
				var parts = varS.split('&');
				var p1 = parts[2];
				if(p1.length == "38") {
					var strdate = p1.substring(21, 11);
					var enddate = p1.substring(38, 28);
				} else {
					var strdate = p1.substring(21, 11);
					var enddate = p1.substring(43, 53);
				}
										
				var p2 = parts[2];
				var gptype = p2.substring(10, 11);

				var periodDate1 = new Date(strdate);
				var periodDate2 = new Date(enddate);    
					if(periodDate2 < periodDate1) {
				swal({ title: "Information!", type: "error",  text: "Start Date < End Date",   timer: 3000,   showConfirmButton: false });
					} else {
						strdate = strdate.split('-');
						enddate = enddate.split('-');

						strdate = new Date(strdate[0], strdate[1], strdate[2]);
						enddate = new Date(enddate[0], enddate[1], enddate[2]);
						date1_unixtime = parseInt(strdate.getTime() / 1000);
						date2_unixtime = parseInt(enddate.getTime() / 1000);


						var timeDifference = date2_unixtime - date1_unixtime;
						var timeDifferenceInHours = timeDifference / 60 / 60;
						var timeDifferenceInDays = timeDifferenceInHours  / 24;
						
						if (document.getElementById('graphtype') !=null){
							var graphtype = document.getElementById("graphtype").value;
						}else{
							var graphtype = 0;
						}
					if(graphtype==3){
						window.open('view/report/'+filereport+'_v.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					}else{
						if(parseInt(timeDifferenceInDays) > 31) {
				swal({ title: "Information!", type: "error",  text: "Error: too large period.\n* max 30 days",   timer: 3000,   showConfirmButton: false });
						} else {
							// $.post('etc/param/insert_log.php', {filereport: filereport, d1:strdate, d2:enddate }, function(data) {
							//     $("#dbreportlog").html(data); 
							// });
							window.open('view/report/'+filereport+'_v.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
						}
					}
					}
			} else {
				//alert("Select your Report List!");	
				swal({ title: "Information!", type: "error",  text: "Select Your Report List.",   timer: 3000,   showConfirmButton: false });
			}
				//alert(varS);
				return false;    
	});


	//button excel
	$("#btnExcel").click(function(){

    var filereport = $('#sel_report').val();
    if(filereport != "") {
	    var varS    = $("#frmDataDet").serialize()+'&type=excel';
		var parts = varS.split('&');
		var p1 = parts[1];
		var strdate = p1.substring(21, 11);
		var enddate = p1.substring(38, 28);
								
		var p2 = parts[2];
		var gptype = p2.substring(10, 11);

		var periodDate1 = new Date(strdate);
		var periodDate2 = new Date(enddate);      

			if(periodDate2 < periodDate1) {
				alert('Start Date < End Date');
			} else {
				strdate = strdate.split('-');
				enddate = enddate.split('-');

				strdate = new Date(strdate[0], strdate[1], strdate[2]);
				enddate = new Date(enddate[0], enddate[1], enddate[2]);
				date1_unixtime = parseInt(strdate.getTime() / 1000);
				date2_unixtime = parseInt(enddate.getTime() / 1000);


				var timeDifference = date2_unixtime - date1_unixtime;
				var timeDifferenceInHours = timeDifference / 60 / 60;
				var timeDifferenceInDays = timeDifferenceInHours  / 24;

				if(parseInt(timeDifferenceInDays) > 31) {
					alert("Error: too large period.\n* max 30 days");
				} else {
					// $.post('etc/param/insert_log.php', {filereport: filereport, d1:strdate, d2:enddate }, function(data) {
					//     $("#dbreportlog").html(data); 
					// });
					if(vsel == "his_teleupload_daily_activity") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					} else if(vsel == "his_teleupload_detail") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					} else if(vsel == "his_daily_activity_call") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					} else if(vsel == "his_data_global") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					} else if(vsel == "his_whatsapp_cust_detail") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					} else if(vsel == "his_cdr") {
						window.open('view/report/'+filereport+'_xlsx.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					}  else {
						window.open('view/report/'+filereport+'_v.php?'+varS, "mywindow", "location=1,status=1,scrollbars=1,  width=1200,height=1200");	
					}
				}
			}
    } else {
    	alert("Select your Report List!");	
    }
        //alert(varS);
        return false;    
	});
    </script>
    
<script type="text/javascript">
	$('.join_date').datetimepicker({
		format: 'YYYY-MM-DD',
	});
	$('.date_of_birth').datetimepicker({
		format: 'YYYY-MM-DD',
	});
</script>
