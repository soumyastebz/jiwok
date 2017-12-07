<table width="175" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="10" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
    <td  height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
    <td width="9" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
  </tr>
  <tr>
    <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
    <td valign="top"><table width="100%">
        <tr>
          <td colspan="3" align="center" class="bigfonts"><img src="images/st_m.gif" width="130" height="32"></td>
        </tr>
    <?php 
	error_reporting(E_ALL ^ E_NOTICE);//echo "<pre/>";print_r($mainmenu_permission);exit;
	
	
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_members.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>

        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_members.php" title="Members">Members</a></td>
        </tr><?php }?>
        <!-- <tr> 
    <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
    <td><a href="list_trainers.php" title="Trainers">Trainers</a></td>
  </tr>
  
  <tr> 
    <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
    <td><a href="list_groups.php" title="Member Groups">Groups</a></td>
  </tr>
  
  <tr> 
    <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
    <td width="83%"><a href="list_news.php" title="News Listings">News</a></td>
  </tr> -->
  <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_faqs.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2"><div align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></div></td>
          <td><a href="list_faqs.php" title="FAQ's">FAQ's</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_newsletters.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_newsletters.php" title="Newsletter">Newsletters</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_feedbacks.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_feedbacks.php" title="Feedbacks">Feedbacks</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_testimonials.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_testimonials.php" title="testimonials">Testimonials</a></td>
        </tr><?php }?>
        <!--<tr> 
    <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
    <td><a href="list_preproduction.php" title="Text Editor">Pre-production</a></td>
  </tr>-->
  <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("mail_contents.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0" /></td>
          <td><a href="mail_contents.php" title="Mail Contents">Mail Contents</a></td>
        </tr><?php }?>
     <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("contents.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="contents.php" title="Text Editor">Text Sections</a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_homepage.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_homepage.php" title="Homepage">Homepage </a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_toptenproducts.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_toptenproducts.php" title="Top Ten Products">Top Ten Products </a></td>
        </tr><?php }?>
     <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_services.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_services.php" title="Homepage">Services </a></td>
        </tr><?php }?>
         <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_services_new.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_services_new.php" title="Homepage">Services New</a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_caochCat.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_caochCat.php" title="Homepage">Coach Categories OLD</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_caochCat_newdesign.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_caochCat_newdesign.php" title="Homepage">Coach Categories </a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_caoches.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_caochCat_home.php" title="Homepage">Coach Details New </a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_caochCat_home.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_caoches.php" title="Homepage">Coach Profile </a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_newpage.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_newpage.php" title="Homepage">Dynamic  Page </a></td>
        </tr><?php }?>
    <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_req_unsubscribe.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_req_unsubscribe.php" title="Homepage">Membership unsubscribe </a></td>
        </tr><?php }?>
     <?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("program_queue_list.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>       
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="program_queue_list.php" title="Homepage">Manage program queue </a></td>
        </tr><?php }?>

        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>
        
          <!--  Added by Dijo on Nov 22-->
         <?php 
	$menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("Color Workouts", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
         <tr >
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">Color Workouts</td>
        </tr>
      <?php 
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("synchronisation.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>         
       <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="synchronisation.php" title="Synchronisation">Synchronize</a></td>
        </tr><?php }?>
	<?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("findandreplace.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>   
        <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="findandreplace.php" title="Find and Replace">Find and Replace</a></td>
        </tr><?php }?>

       <tr >
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>
        <!--  Added by Dijo on Nov 22 ends-->
        
         <?php }
	$menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("Referral System", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
        <tr >
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">Referral System</td>
        </tr>
	<?php	
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("referralSettings.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
       <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="referralSettings.php" title="Referral System">Settings</a></td>
        </tr><?php }?>
	<?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("referralMembers.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
       
         <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="referralMembers.php" title="Referral System">Referral Status</a></td>
        </tr><?php }?>
	<?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_referral.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_referral.php" title="Referral System">Report</a></td>
        </tr><?php }?>
	<?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_addword.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr >
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_addword.php" title="Referral System">Addword Report</a></td>
        </tr><?php }?>
         <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>
        
        
  <?php } $menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("Tools Manager", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
        <tr>
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">Tools Manager</td>
        </tr><?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("mass_mail.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="mass_mail.php" title="Mass Mailing">Mailing System</a></td>
        </tr><?php }?><?php
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("generatetraining.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="generatetraining.php" title="Generate Training">Generate Training</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("manage_giftcode.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="manage_giftcode.php" title="Manage Gift Code">Manage Gift Code</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("manage_campaign.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="manage_campaign.php" title="Manage campaign">Manage Campaign</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_reseller.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_reseller.php" title="Reseller">Reseller</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_limitcategories.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_limitcategories.php" title="">Limit Categories</a></td>
        </tr>   <?php }  ?> 
        
        <?php 
		  $page_permis	=	"true";
		  if($page_name!= "")
		  { 
			  
			  if (in_array("manage_giftcode_campaign.php", $page_permission))
			  {
				  $page_permis	=	"false";
			  }
		  }
		  if(($full_permission == "true" )|| ($page_permis	==	"false"))
		  {?> 
			  <tr>
				<td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
				<td><a href="manage_giftcode_campaign.php" title="">New Campaign for gift code users</a></td>
        </tr>   <?php }  ?> 
        
        
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr><?php }  ?> 
    <?php $menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("Brand Manager", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
        <tr>
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">Brand Manager</td>
        </tr>
     <?php $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_brands.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_brands.php" title="Brands">Manage Brands</a></td>
        </tr> <?php }  
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("brand_users.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="brand_users.php" title="Members">Brand Members</a></td>
        </tr><?php } $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("brand_report_payment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="brand_report_payment.php" title="Payment">Payment</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("brand_report_unsubscribed.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="brand_report_unsubscribed.php" title="Unsubscribed">Unsubscribed</a></td>
        </tr><?php } ?>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>       
        <tr><?php } ?>
       <?php $menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("New payment", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">New payment</td>
        </tr>
    <?php $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_plan.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_plan.php" title="Payment">Payment plans</a></td>
        </tr><?php } 
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("newPayment_revice.php", $page_permission))  
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{ 
	?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="newPayment_revice.php" title="Brands">Cancel/Refund</a></td>
        </tr><?php } 
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("paybox_unsubscription.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="paybox_unsubscription.php" title="Members">Unsubscription</a></td>
        </tr>  <?php } 
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("manage_30dayRule.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="manage_30dayRule.php" title="Members">Manage 30 day rule</a></td>
        </tr>    <?php } 
		$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("remove_paid.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="remove_paid.php" title="Unsubscription">Unsubscribe user from paybox</a></td>
        </tr><?php } 
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_newPayment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 				 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_newPayment.php" title="Unsubscribed">Report</a></td>
        </tr><?php } ?>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr><?php } ?>
        <?php $menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("Reports", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
        <tr>
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">Reports</td>
        </tr>
<?php 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_members.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>        
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_members.php" title="Members">Members</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_subscriptions.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>    
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_subscriptions.php" title="Subscription">Subscription</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_workouts.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>    		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_workouts.php" title="Generation">Generation</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_campaign.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_campaign.php" title="Campaign">Campaign</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_discount.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 			
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_discount.php" title="Generation">Discount</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_payment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_payment.php" title="Generation">Payment</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_giftcode_payment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 				
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_giftcode_payment.php" title="giftcodePayment">GiftcodePayment</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_unsubscribed.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 				
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_unsubscribed.php" title="Unsubscribed">Unsubscribed</a></td>
        </tr><?php }
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_transaction_issues.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 			
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_transaction_issues.php" title="Transaction Issues">Transaction Issues</a></td>
        </tr>   <?php } ?>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>
        <?php
		 } ?>  
          <!--================================================-->
		  <?php $menu_permis	=	"true";
	if($mainmenu!= "")
	{ 
		
		if (in_array("New Jiwok", $mainmenu_permission))
		{
			$menu_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($menu_permis	==	"false"))
	{?>      
        <tr>
          <td colspan="3" class="bigrheadings" align="left" valign="middle"><img src="images/tools.gif" width="15" height="15" border="0">New Jiwok</td>
        </tr>
     <?php $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("crop.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
<!--
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="crop.php" title="Cropping Tool">Cropping Tool</a></td>
        </tr> 
-->
        <?php }  
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("program_img.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="program_img.php" title="Program Image ">Program Image </a></td>
        </tr><?php }
		 $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("program_cat_img.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="program_cat_img.php" title="Category Image">Category Image</a></td>
        </tr><?php }
		 $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_slides_home.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_slides_home.php" title="Cropping Tool">Slider</a></td>
        </tr> <?php } 
			$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_stripe_Payment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_stripe_Payment.php" title="Program Image ">Stripe Payment Report </a></td>
        </tr><?php }
        	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_stripe_Payment_mobile.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_stripe_Payment_mobile.php" title="Program Image ">Stripe Mobile Payment Report </a></td>
        </tr><?php }
      
         $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("report_IAP_Payment.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="report_IAP_Payment.php" title="Cropping Tool">IAP Payment Report</a></td>
        </tr> <?php } 
     
     
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("crop/demo/back.html", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="newPayment_revice_stripe.php" title="Program Image ">Stripe cancel/Refund </a></td>
        </tr><?php }
      $page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("crop/demo/back.html", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="remove_paid_stripe.php" title="Program Image ">Unsubscribe user from stripe</a></td>
        </tr><?php }  
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("crop/demo/back.html", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?>  
<!--
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="crop/demo/back.html" title="Large crop">Large Crop</a></td>
        </tr>
-->
        <?php } ?>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="2" align="left" class="bigrheadings">&nbsp;</td>
        </tr>       
        <tr><?php } ?>
        <!--================================================-->
        <tr align="left" valign="middle">
          <td colspan="3">&nbsp;</td>
        </tr>
      </table></td>
    <td background="images/side2.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="10" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
    <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
    <td width="11" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
  </tr>
</table>
<table width="175" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="9" height="9"><img src="images/top_left.jpg" width="9" height="9"></td>
    <td height="9" background="images/top_mdl.jpg"><img src="images/top_mdl.jpg" width="9" height="9"></td>
    <td width="10" height="9"><img src="images/top_right.jpg" width="9" height="9"></td>
  </tr>
  <tr>
    <td background="images/side1.jpg"><img src="images/side1.jpg" width="9" height="9"></td>
    <td valign="top"><table width="91%">
        <tr>
          <td colspan="3" align="center"  class="bigfonts"><img src="images/ad_c.gif"  height="32"></td>
        </tr>
 <?php 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_admin.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		       
        
        <tr>
          <td colspan="2"><div align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></div></td>
          <td width="85%"><a href="list_admin.php" title="Administrator Settings">Administrator</a></td>
        </tr><?php 	}
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_menumaster.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 				
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_menumaster.php" title="Site Menus">Site Menus</a></td>
        </tr><?php 	}
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_seo.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
		 <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_seo.php" title="Site Menus">SEO Manage</a></td>
        </tr><?php 	}
	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_footer_links.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_footer_links.php" title="Site Menus">Footer Links</a></td>
        </tr><?php 	} ?>
        <!--<tr> 
    <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
    <td><a href="list_categories.php?masterId=0" title="Site Menus">Training Categories</a></td>
  </tr>--><?php 
 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("list_languages.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="list_languages.php" title="Site Menus">Languages</a></td>
        </tr><?php } 
 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("manage_discount.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 		
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="manage_discount.php" title="list Vocals">Discount Manage</a></td>
        </tr><?php }
 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("menu.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 			
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="menu.php" title="Settings">New Home Page Settings</a></td>
        </tr><?php }
 	$page_permis	=	"true";
	if($page_name!= "")
	{ 
		
		if (in_array("settings.php", $page_permission))
		{
			$page_permis	=	"false";
		}
	}
	if(($full_permission == "true" )|| ($page_permis	==	"false"))
	{?> 			
        <tr>
          <td colspan="2" align="right"><img src="images/bullet_011.gif" width="26" height="20" border="0"></td>
          <td><a href="settings.php" title="Settings">Settings</a></td>
        </tr><?php } ?>
        <tr>
          <td colspan="2"><div align="right"><a href="index.php?fuseaction=logout"><img src="images/bullet_011.gif" width="26" height="20" border="0"></a></div></td>
          <td><a href="logout.php" title="Logout">Logout</a></td>
        </tr>
      </table></td>
    <td background="images/side2.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="9" height="9"><img src="images/btm_left.jpg" width="9" height="9"></td>
    <td height="9" background="images/btm_mdl.jpg"><img src="images/btm_mdl.jpg" width="9" height="9"></td>
    <td width="10" height="9"><img src="images/btm_right.jpg" width="9" height="9"></td>
  </tr>
</table>
