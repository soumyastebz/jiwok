<form name="grpFrm" action="" method="post" enctype="multipart/form-data">
                                  <tr >
                                         
                                          <td style="padding-top:10px; color:#FFF" colspan="8" align="right"><strong>Group By : </strong><select id="grouping" name="grouping" style="font-size:11px;" onChange="this.form.submit();" >
                                          <option value="" <?php if(trim($_POST['grouping'])==''){ echo 'selected'; } ?>>All</option>
                                          <option value="brand_name" <?php if(trim($_POST['grouping'])=="brand_name"){ echo 'selected'; } ?>>Brand </option>
                                          <option value="Country" <?php if(trim($_POST['grouping'])=="Country"){ echo 'selected'; } ?>>Country</option>
                                   		  <option value="Gender" <?php if(trim($_POST['grouping'])=="Gender"){ echo 'selected'; } ?>>Gender</option>
                                          <option value="Age" <?php if(trim($_POST['grouping'])=="Age"){ echo 'selected'; } ?>>Age</option>
                                          <option value="origin" <?php if(trim($_POST['grouping'])=="4"){ echo 'selected'; } ?>>Origin</option>
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
                                           <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
                                           <input type="hidden" name="brand_select" id="brand_select" value="<?=trim($_POST['brand_select'])?>"/>
										   
                                           <input type="hidden" name="user_brand" id="user_brand" value="<?=trim($_POST['user_brand'])?>"/>
                                          <input type="hidden" name="num_months" id="num_months" value="<?=trim($_POST['num_months'])?>"/>
										  <input type="hidden" name="origin_select" id="origin_select" value="<?=trim($_POST['origin_select'])?>"/>
                                           <input type="hidden" name="user_type" id="user_type" value="<?=trim($_POST['user_type'])?>"/>
										   <input type="hidden" name="user_program" id="user_program" value="<?=trim($_POST['user_program'])?>"/>
										   <input type="hidden" name="user_language" id="user_language" value="<?=trim($_POST['user_language'])?>"/>
                                           
                                            <input type="hidden" name="user_country_1" id="user_country_1" value="<?=$countryCondn?>"/>
                                             <input type="hidden" name="user_gender_1" id="user_gender_1" value="<?=$genderCondn?>"/> 
                                              <input type="hidden" name="user_brand_1" id="user_brand_1" value="<?=$brandCondn?>"/>  
											  
											  <input type="hidden" name="user_origin_1" id="user_origin_1" value="<?=$originCondn?>"/>
											  <input type="hidden" name="user_type_1" id="user_type_1" value="<?=$typeCondn?>"/>
											  <input type="hidden" name="user_program_1" id="user_program_1" value="<?=$programCondn?>"/>
											  <input type="hidden" name="user_language_1" id="user_language_1" value="<?=$languageCondn?>"/>
                                          </td>
                                          </tr>
                                          </form>