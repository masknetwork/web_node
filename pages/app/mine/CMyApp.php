<?
   class CMyApp
   {
	   function CMyApp($db, $template, $app)
	   {
		  $this->kern=$db;
		  $this->template=$template;
		  $this->app=$app;
	   } 
	   
	   function showMyAgents()
	   {
		   // Deploy to app store
		   $this->app->showPublishModal();
		   
		   // Update app
		   $this->app->showUpdateModal();
		
		   // Confirm modal
		   $this->template->showConfirmModal();
		
		   $query="SELECT ag.*, adr.balance, my_adr.ID AS adrID
		             FROM agents AS ag
					 JOIN adr ON adr.adr=ag.adr
					 JOIN my_adr ON my_adr.adr=adr.adr
				    WHERE ag.adr IN (SELECT adr 
					                   FROM my_adr 
								      WHERE userID='".$_REQUEST['ud']['ID']."') 
			     ORDER BY ag.ID DESC ";
	       $result=$this->kern->execute($query);	
	  
		?>
           
           <br>
           <table class="tbale-responsive" width="90%">
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="8%"><img src="./GIF/write.png" class="img-responsive"></td>
                 <td width="2%">&nbsp;</td>
                 <td width="50%"><a class="font_16" href="../directory/app.php?ID=<? print $row['aID']; ?>"><? print base64_decode($row['name']); ?></a>
                 <p class="font_12"><? print $this->template->formatAdr($row['adr']); ?></p></td>
                 <td width="20%" class="font_14">
                 <?
				    print $row['balance']." MSK";
                 ?>
                 </td>
                 <td width="20%">&nbsp;</td>
                 <td width="12%">
                 
                 <div class="btn-group">
                 <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">
                 <span class="glyphicon glyphicon-cog"><span class="caret"></span></button>
                 <ul role="menu" class="dropdown-menu">
                 
                 <?
                    if ($row['dir']==0) 
			        {
						if ($row['price']==0)
						{
				 ?>
                 
                       <li><a href="javascript:void(0)" onClick="$('#publish_modal').modal(); 
                                                                 $('#publish_appID').val('<? print $row['aID']; ?>');
                                                                 $('#txt_publish_name').val('<? print base64_decode($row['name']); ?>');
                                                                 $('#txt_publish_desc').val('<? print base64_decode($row['desc']); ?>');
                                                                 $('#txt_publish_website').val('<? print base64_decode($row['website']); ?>');
                                                                 $('#txt_publish_pic').val('<? print base64_decode($row['pic']); ?>');
                                                                 $('#txt_publish_ver').val('<? print base64_decode($row['ver']); ?>');
                                                                 $('#txt_publish_target').val('ID_DIR');
                                                                 $('#txt_publish_price_txt').css('display', 'none');
                                                                 $('#txt_publish_price').css('display', 'none');
                                                                 $('#tr_pay_adr').css('display', 'none');
                                                                 $('#img_publish').attr('src', '../write/GIF/directory.png');">Publish to Directory</a></li>
                                                           
                 <?
						}
					}
					else
					{
						?>
                        
                        <li><a href="javascript:void(0)" onClick="$('#update_modal').modal(); 
                                                                 $('#update_appID').val('<? print $row['aID']; ?>');
                                                                 $('#update_act').val('ID_REMOVE_DIR');
                                                                 $('#txt_update_days_txt').css('display', 'none');
                                                                 $('#txt_update_days').css('display', 'none');
                                                                 $('#img_update').attr('src', './GIF/uninstall.png');">Remove from Directory</a></li>
                        
                        <?
					}
					
				    if ($row['owner']==$row['adr'])
					{
                      if ($row['price']==0) 
			          {
						  if ($row['dir']==0)
						  {
				 ?>
                 
                       <li><a href="javascript:void(0)" onClick="$('#publish_modal').modal(); 
                                                                 $('#publish_appID').val('<? print $row['aID']; ?>');
                                                                 $('#txt_publish_target').val('ID_STORE');
                                                                 $('#txt_publish_price_txt').css('display', 'block');
                                                                 $('#txt_publish_price').css('display', 'block');
                                                                 $('#tr_pay_adr').css('display', 'block');
                                                                 $('#img_publish').attr('src', '../write/GIF/app_store.png');">Publish to App Store</a></li>
                                                           
                 <?
						  }
					}
					else
					{
						?>
                        
                        <li><a href="javascript:void(0)" onClick="$('#update_modal').modal(); 
                                                                 $('#update_appID').val('<? print $row['aID']; ?>');
                                                                 $('#update_act').val('ID_REMOVE_STORE');
                                                                 $('#txt_update_days_txt').css('display', 'none');
                                                                 $('#txt_update_days').css('display', 'none');
                                                                 $('#img_update').attr('src', './GIF/uninstall.png');">Remove from App Store</a></li>
                        
                        <?
					}
			     }
				 ?>
				 
                 <li><a href="globals.php?ID=<? print $row['aID']; ?>">Change Settings</a></li>
                                                           
					   
                 <li><a href="javascript:void(0)" onClick="$('#update_modal').modal(); 
                                                           $('#update_appID').val('<? print $row['aID']; ?>');
                                                           $('#update_act').val('ID_UNINSTALL');
                                                           $('#txt_update_days_txt').css('display', 'none');
                                                           $('#txt_update_days').css('display', 'none');
                                                           $('#img_update').attr('src', './GIF/uninstall.png');">Uninstall</a></li>
                                                           
                 
                 <li><a href="../../adr/options/index.php?ID=<? print $row['adrID']; ?>" >Seal Address</a></li>
                                                          
                 </div>
                 
                 </td>
                 </tr>
                 <tr><td colspan="6"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
        
        <?
	}
   }
?>