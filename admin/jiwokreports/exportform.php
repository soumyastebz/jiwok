<form name="exportFrm" action="" method="post" enctype="multipart/form-data">
                                 <tr>
                                           <td  colspan="6" height="27" align="right" valign="bottom" style="padding-right:20px;">Export
                                           <select id="export_to" name="export_to" style="font-size:11px;">
                                            <option value="" <?php if($_REQUEST['export_to']=='') {?> selected <?php }?>>Select</option>
                                            <option value="1" <?php if($_REQUEST['export_to']=='1') {?> selected <?php }?>>Export to CSV</option>
											<option value="2" <?php if($_REQUEST['export_to']=='2') {?> selected <?php }?>>Export Details</option>
                                          </select>
                                          <input type="hidden" name="report_criteria" id="report_criteria" value="<?=$_POST['report_criteria']?>"/>
										  <input type="hidden" name="frD" id="frD" value="<?=$_POST['frD']?>"/>
                                           <input type="hidden" name="frM" id="frM" value="<?=$_POST['frM']?>"/>
                                            <input type="hidden" name="frY" id="frY" value="<?=$_POST['frY']?>"/>
											<input type="hidden" name="toD" id="toD" value="<?=$_POST['toD']?>"/>
                                             <input type="hidden" name="toM" id="toM" value="<?=$_POST['toM']?>"/>
                                              <input type="hidden" name="toY" id="toY" value="<?=$_POST['toY']?>"/>
                                              
                                               
                                              
                                           <input type="hidden" name="country_select" id="country_select" value="<?=trim($_POST['country_select'])?>"/>
                                           <input type="hidden" name="user_country" id="user_country" value="<?=trim($_POST['user_country'])?>"/>
                                          
                                           <input type="hidden" name="user_gender" id="user_gender" value="<?=trim($_POST['user_gender'])?>"/>
                                          
                                           <input type="hidden" name="user_origin" id="user_origin" value="<?=trim($_POST['user_origin'])?>"/>
                                           <input type="hidden" name="code" id="code" value="<?=trim(stripslashes($_POST['code']))?>"/>
                                           <input type="hidden" name="user_fromage" id="user_fromage" value="<?=trim(stripslashes($_POST['user_fromage']))?>"/>
                                           <input type="hidden" name="user_toage" id="user_toage" value="<?=trim(stripslashes($_POST['user_toage']))?>"/>                                                                                    
                                           <input type="hidden" name="brand_select" id="brand_select" value="<?=trim($_POST['brand_select'])?>"/>
										   <input type="hidden" name="user_brand" id="user_brand" value="<?=trim($_POST['user_brand'])?>"/>
										   <!--From-->
										   <input type="hidden" name="num_months" id="num_months" value="<?=trim($_POST['num_months'])?>"/>
										   <input type="hidden" name="origin_select" id="origin_select" value="<?=trim($_POST['origin_select'])?>"/>
										   <input type="hidden" name="type_select" id="type_select" value="<?=trim($_POST['type_select'])?>"/>
										   <input type="hidden" name="program_select" id="program_select" value="<?=trim($_POST['program_select'])?>"/>
										   <input type="hidden" name="cmp_select" id="cmp_select" value="<?=trim($_POST['cmp_select'])?>"/>
										   <input type="hidden" name="user_origin" id="user_origin" value="<?=trim($_POST['user_origin'])?>"/>
                                           <input type="hidden" name="user_type" id="user_type" value="<?=trim($_POST['user_type'])?>"/>
										   <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
										   <input type="hidden" name="user_cmp" id="user_cmp" value="<?=trim($_POST['user_cmp'])?>"/>
										    <input type="hidden" name="user_code" id="user_code" value="<?=trim($_POST['user_code'])?>"/>
											<input type="hidden" name="user_language" id="user_language" value="<?=trim($_POST['user_language'])?>"/>
										   <!--To-->                                           
                                           

                                            <input type="hidden" name="user_country_1" id="user_country_1" value="<?=trim($countryCondn)?>"/>
                                             <input type="hidden" name="user_gender_1" id="user_gender_1" value="<?=trim($genderCondn)?>"/> 
                                              <input type="hidden" name="user_brand_1" id="user_brand_1" value="<?=$brandCondn?>"/>  
                                             <input type="hidden" name="grouping" id="grouping" value="<?=trim($_POST['grouping'])?>"/>
											 <!--From-->
											 <input type="hidden" name="user_origin_1" id="user_origin_1" value="<?=$originCondn?>"/>
											 <input type="hidden" name="user_type_1" id="user_type_1" value="<?=$typeCondn?>"/>
											 <input type="hidden" name="user_program_1" id="user_program_1" value="<?=$programCondn?>"/>
											 <input type="hidden" name="user_cmp_1" id="user_cmp_1" value="<?=$cmpCondn?>"/>
											 <input type="hidden" name="user_language_1" id="user_language_1" value="<?=$languageCondn?>"/>
    										<!--To-->	
                                          <input name="export" type="submit" value="export" />
                                           </td>
                                        </tr>
                                        </form>