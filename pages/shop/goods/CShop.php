<?
class CShop
{
	function CShop($db, $acc, $template)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->acc=$acc;
	}
	
	function newItem($tip,
	                 $net_fee_adr,
	                 $adr, 
	                 $categ, 
	                 $subcateg, 
					 $title, 
					 $description, 
					 $web_page, 
					 $internalID, 
					 $pic_1, 
					 $pic_2, 
					 $pic_3, 
					 $pic_4, 
					 $pic_5, 
					 $loc_country, 
					 $loc_town, 
					 $loc_delivery, 
					 $loc_delivery_reg, 
					 $package_cond, 
					 $package_delivery, 
					 $package_postage, 
					 $package_return, 
					 $price_price, 
					 $price_mkt_bid, 
					 $price_mkt_days, 
					 $price_escrowing, 
					 $price_escrower_1, 
					 $price_escrower_2, 
					 $price_escrower_3)
	{
		// Tip
		if ($tip!="ID_GOODS" && 
		    $tip!="ID_SERVICES" && 
			$tip!="ID_VIRTUAL")
		{
			 $this->template->showErr("Invalid type");
			 return false;
		}
		
	    // Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Net Fee Address 
		if ($this->template->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		// Mkt bid
		if ($price_mkt_bid<0.0001)
		{
			$this->template->showErr("Invalid market bid");
			return false;
		}
		
		// Mkt days
		if ($price_mkt_days<1)
		{
			$this->template->showErr("Invalid market days");
			return false;
		}
		 
		 // Net fee
		 $net_fee=$price_mkt_bid*$price_mkt_days;
		 
		 // Funds
		 if ($this->template->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		// Order address
		if ($this->template->adrExist($adr)==false)
		{
			$this->template->showErr("Invalid order address");
			return false;
		}
		
		// Check categ
		$query="SELECT * 
		          FROM mkt_categs 
				 WHERE name='".$categ."'";
	    $result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)>=0)
		{
			$this->template->showErr("Invalid category");
			return false;
		}
		
		// Check subcateg
		$query="SELECT * 
		          FROM mkt_categs 
				 WHERE name='".$subcateg."' 
				   AND categ='".$categ."'";
	    $result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid subcategory");
			return false;
		}
	  
	    // Title
		$title=base_64_decode($title);
		if (strlen($title)<5 || strlen($title)>50)
		{
			$this->template->showErr("Invalid title length");
			return false;
		}
		
		// Description   
		$description=base_64_decode($description);    
	    if (strlen($description)<5 || strlen($description)>250)
		{
			$this->template->showErr("Invalid description length");
			return false;
		}
		           
        // Web page 
		$web_page=base64_decode($web_page);
		if (strlen($web_page)>5)
		{
	      if (!filter_var($web_page, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid web page");
			return false;
		  }
		}
		
		// Internal ID
		if (strlen($internalID)>10)
		{
			$this->template->showErr("Invalid internal ID length");
			return false;
		}
		
		// Pic 1 
		$pic_1=base64_decode($pic_1);
		if (strlen($pic_1)>5)
		{
	      if (!filter_var($pic_1, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 1 link");
			return false;
		  }
		}
		
		// Pic 2 
		$pic_2=base64_decode($pic_2);
		if (strlen($pic_2)>5)
		{
	      if (!filter_var($pic_2, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 2 link");
			return false;
		  }
		}
		
		// Pic 3 
		$pic_3=base64_decode($pic_3);
		if (strlen($pic_3)>5)
		{
	      if (!filter_var($pic_3, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 3 link");
			return false;
		  }
		}
		
		// Pic 4 
		$pic_4=base64_decode($pic_4);
		if (strlen($pic_4)>5)
		{
	      if (!filter_var($pic_4, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 4 link");
			return false;
		  }
		}
		
		// Pic 1 
		$pic_5=base64_decode($pic_5);
		if (strlen($pic_5)>5)
		{
	      if (!filter_var($pic_5, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 5 link");
			return false;
		  }
		}
		
		// Location country
		$query="SELECT * 
		          FROM countries 
				 WHERE code='".$loc_country."'";
		$result=$this->kern->execute($query);	
	    if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid country");
			return false;
		}
		
		// Location town
		if (strlen($loc_town)>50)
		{
			$this->template->showErr("Invalid town");
			return false;
		}
		 
		// Location delivery
		if ($loc_delivery!="ID_ALL" && 
		   $loc_delivery!="ID_ALL_EXCEPT" && 
		   $loc_delivery!="ID_ONLY_FOLLOWING")
		 {
			 $this->template->showErr("Invalid location delivery");
		 	 return false;
		 }
		 
		 // Location delivery regions
		 $loc_delivery_reg=base64_decode($loc_delivery_reg);
		 if (strlen($loc_delivery_reg)>250)
		 {
			 $this->template->showErr("Invalid location delivery regions");
		 	 return false;
		 }
		 
		 // Condition
		 if ($package_cond!="ID_NEW" && 
		    $package_cond!="ID_USED")
		 {
			  $this->template->showErr("Invalid package condition");
		 	  return false;
		 }
					 
		 // Package delivery
		 $package_delivery=base64_decode($package_delivery);
		 if (strlen($package_delivery)>100)
		 {
			 $this->template->showErr("Invalid package delivery");
		 	 return false;
		 }
		 
		 // Postage
		 $package_postage=base64_decode($package_postage);
		 if (strlen($package_postage)>100)
		 {
			 $this->template->showErr("Invalid postage");
		 	 return false;
		 }
					
		// Return policy
		$package_return=base64_decode($package_return);
		if (strlen($package_return)>100)
		{
			 $this->template->showErr("Invalid postage");
		 	 return false;
		}
		
		// Price 
		if ($price<0.01)
		{
			 $this->template->showErr("Minimum price is $0.01");
		 	 return false;
		}
		
		// Market bid
		if ($price_mkt_bid<0.01)
		{
			 $this->template->showErr("Minimum price is $0.01");
		 	 return false;
		}
		
		// Market days
		if ($price_mkt_days<1)
		{
			 $this->template->showErr("Minimum days is 1");
		 	 return false;
		}
					 
		// Escrowing
		if ($price_escrowing!="ID_ESC_ANY" || 
		    $price_escrowing!="ID_ESC_FOLLOWING" || 
			$price_escrowing!="ID_ESC_DONT")
		 {
			 $this->template->showErr("Invalid escrowing policy");
		 	 return false;
		 }
		 
		 // Escrowers
		 if ($price_escrowing=="ID_ESC_FOLLOWING")
		 {
			 // Escrower 1
			 if (strlen($price_escrower_1)>10)
			 {
				 if ($this->template->adrExist($price_escrower_1)==false)
		         {
	   		        $this->template->showErr("Invalid escrower 1");
			        return false;
		         }
			 }
			 
			  // Escrower 2
			 if (strlen($price_escrower_2)>10)
			 {
				 if ($this->template->adrExist($price_escrower_2)==false)
		         {
	   		        $this->template->showErr("Invalid escrower 2");
			        return false;
		         }
			 }
			 
			  // Escrower 3
			 if (strlen($price_escrower_3)>10)
			 {
				 if ($this->template->adrExist($price_escrower_3)==false)
		         {
	   		        $this->template->showErr("Invalid escrower 3");
			        return false;
		         }
			 }
		 }
		 
		 try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Inserts a new market item.".$uid);
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NE_MKT_ITEM', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$tip."',
								par_2='".$categ."',
								par_3='".$subcateg."',
								par_4='".$title."',
								par_5='".$description."',
								par_6='".$web_page."',
								par_7='".$internalID."',
								par_8='".$pic_1."',
								par_9='".$pic_2."',
								par_10='".$pic_3."',
								par_11='".$pic_4."',
								par_12='".$pic_5."',
								par_13='".$loc_country."',
								par_14='".$loc_town."',
								par_15='".$loc_delivery."',
								par_16='".$loc_delivery_reg."',
								par_17='".$package_cond."',
								par_18='".$package_delivery."',
								par_19='".$package_postage."',
								par_20='".$package_return."',
								par_21='".$price_price."',
								par_22='".$price_escrowing."',
								par_23='".$price_escrower_1."',
								par_24='".$price_escrower_2."',
								par_25='".$price_escrower_3."',
								bid='".$price_mkt_bid."',
								days='".$price_mkt_days."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function showNewItemPanel()
	{
		?>
           
           <form name="form_new_item" id="form_new_item" action="new_item.php?act=new_item" method="post">
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="28%" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td align="center"><img src="../GIF/papers.png" width="130" height="106" alt=""/></td>
                            </tr>
                            <tr>
                              <td height="30" align="center" class="simple_maro_16"><strong class="simple_red_16">General Data</strong></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="72%" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Net Fee Address</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14"><? $this->template->showMyAdrDD("dd_net_fee_adr"); ?></td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Item address</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14"><? $this->template->showMyAdrDD("dd_adr"); ?></td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Main Category</td>
                            </tr>
                            <tr>
                              <td>
                              <select id="dd_categ" name="dd_categ" class="form-control">
                              <option value="ID_ANTIQUES">Antiques</option>
                              <option value="ID_ART">Art</option>
                              <option value="ID_BABY">Baby</option>
                              <option value="ID_BOOKS">Books, Comics and Magazines</option>
                              <option value="ID_BUSINESS">Business, Office & Industrial</option>
                              <option value="ID_CARPETS">Carpets/ Rugs</option>
                              <option value="ID_CAMERA">Cameras & Photography</option>
                               <option value="ID_CARS">Cars, Motorcycles & Vehicles</option>
                               <option value="ID_CLOTHES">Clothes, Shoes & Accessories</option>
                               <option value="ID_COINS">Coins</option>
                               <option value="ID_COLLECTABLES">Collectables</option>
                               <option value="ID_COMPUTERS">Computers/Tablets & Networking</option>
                               <option value="ID_CRAFTS">Crafts</option>
                               <option value="ID_DOLLS">Dolls & Bears</option>
                               <option value="ID_DVD">DVDs, Films & TV</option>
                               <option value="ID_EVENTS">Events Tickets</option>
                               <option value="ID_GARDEN">Garden & Patio</option>
                               <option value="ID_HEALTH">Health & Beauty</option>
                               <option value="ID_HOLYDAYS">Holidays & Travel</option>
                               <option value="ID_HOME">Home, Furniture & DIY</option>
                               <option value="ID_JEWELRY">Jewellery & Watches</option>
                               <option value="ID_MOBILE">Mobile Phones & Communication</option>
                               <option value="ID_MUSIC">Music</option>
                               <option value="ID_MUSICAL">Musical Instruments</option>
                               <option value="ID_PETS">Pet Supplies</option>
                               <option value="ID_POETRY">Pottery, Porcelain & Glass</option>
                               <option value="ID_SOUND">Sound & Vision</option>
                               <option value="ID_SPORTING">Sporting Goods</option>
                               <option value="ID_SPORTS">Sports Memorabilia</option>
                               <option value="ID_STAMPS">Stamps</option>
                               <option value="ID_TOYS">Toys & Games</option>
                               <option value="ID_VEHICLE">Vehicle Parts & Accessories</option>
                               <option value="ID_VIDEO">Video Games & Consoles</option>
                               <option value="ID_WHOLESALE">Wholesale & Job Lots</option>
                               <option value="ID_ELSE">Everything Else</option>
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Sub-Category</span></td>
                            </tr>
                            <tr>
                              <td>
                              <select id="dd_subcateg" name="dd_subcateg" class="form-control">
                                <option value="ID_ANTIQUE_CLOCKS">Antique Clocks</option>
                                <option value="ID_ANTIQUE_FURNITURE">Antique Furniture</option>
                                <option value="ID_ANTIQUITIES">Antiquities</option>
                                <option value="ID_ARHITECTURAL_ANTIQUES">Architectural Antiques</option>
                                <option value="">Asian/ Oriental Antiques</option>
                                <option value="">Carpets/ Rugs</option>
                                <option value="">Decorative Arts</option>
                                <option value="">Ethnographic Antiques</option>
                                <option value="">Fabric/ Textiles</option>
                                <option value="">Manuscripts</option>
                                <option value="">Maps</option>
                                <option value="">Marine/ Maritime</option>
                                <option value="">Metalware</option>
                                <option value="">Science/ Medicine</option>
                                <option value="">Silver</option>
                                <option value="">Woodenware</option>
                                <option value="">Periods/Styles</option>
                                <option value="">Reproduction Antiques</option>
                                <option value="">Other Antiques</option>
                             </select>
                             </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Title</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_title" name="txt_title" placeholder="Title (5-50 characters)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Description</span></td>
                            </tr>
                            <tr>
                              <td><textarea id="txt_desc" name="txt_desc" placeholder="Description" rows="5" class="form-control"></textarea></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Web Page</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_webpage" name="txt_webpage" placeholder="Title (5-50 characters)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Internal ID</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_internal_ID" name="txt_internal_ID" placeholder="Title (5-50 characters)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Visibility</span></td>
                            </tr>
                            <tr>
                              <td><select id="dd_delivery_reg2" name="dd_delivery_reg2" class="form-control">
                                <option value="ID_ALL">All countries and teritories</option>
                                <option value="ID_ALL_EXCEPT">All countries exepting the following</option>
                                <option value="ID_ONLY_FOLLOWING">Only in the following countries</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Countries</span></td>
                            </tr>
                            <tr>
                              <td><textarea id="txt_delivery_reg2" name="txt_delivery_reg2" placeholder="Description" rows="5" class="form-control"></textarea></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                              <tr>
                                <td align="center"><img src="../GIF/photo_cam.png" width="130" height="110" alt=""/></td>
                              </tr>
                              <tr>
                                <td height="30" align="center" class="simple_maro_16"><strong class="simple_red_16">Pics</strong></td>
                              </tr>
                            </tbody>
                        </table></td>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Pic 1 </td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_pic_1" name="txt_pic_1" placeholder="Pic (web link, max 1 Mb)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Pic 2</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_pic_2" name="txt_pic_2" placeholder="Pic (web link, max 1 Mb)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Pic 3</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_pic_3" name="txt_pic_3" placeholder="Title (5-50 characters)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Pic 4</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_pic_4" name="txt_pic_4" placeholder="Pic (web link, max 1 Mb)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Pic 5</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_pic_5" name="txt_pic_5" placeholder="Title (5-50 characters)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                        <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                              <tr>
                                <td align="center"><img src="../GIF/location.png" width="130" height="129" alt=""/></td>
                              </tr>
                              <tr>
                                <td height="30" align="center" class="simple_maro_16"><strong class="simple_red_16">Location</strong></td>
                              </tr>
                            </tbody>
                        </table></td>
                        <td align="center"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Item Location - Country</td>
                            </tr>
                            <tr>
                              <td>
                              <select id="dd_location" name="dd_location" class="form-control">
                                <option value="RO">Romania</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Item Location - Town</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_town" name="txt_town" placeholder="Pic (web link, max 1 Mb)"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Delivery </span></td>
                            </tr>
                            <tr>
                              <td>
                              <select id="dd_delivery_reg" name="dd_delivery_reg" class="form-control">
                                <option value="ID_ALL">All countries and teritories</option>
                                <option value="ID_ALL_EXCEPT">All countries exepting the following</option>
                                <option value="ID_ONLY_FOLLOWING">Only in the following countries</option>
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Delivery Regions</span></td>
                            </tr>
                            <tr>
                              <td><textarea id="txt_delivery_reg" name="txt_delivery_reg" placeholder="Description" rows="5" class="form-control"></textarea></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            </tbody>
                        </table></td>
                      </tr>
                        <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                              <tr>
                                <td align="center"><img src="../GIF/misc.png" width="130" height="122" alt=""/></td>
                              </tr>
                              <tr>
                                <td height="30" align="center" class="simple_maro_16"><strong class="simple_red_16">Delivery</strong></td>
                              </tr>
                            </tbody>
                        </table></td>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" valign="top" class="simple_maro_14">Condition</td>
                            </tr>
                            <tr>
                              <td><select id="dd_condition" name="dd_condition" class="form-control">
                                <option value="RO">New</option>
                                <option value="US">Used</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Delivery</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_delivery" name="txt_delivery" placeholder="Dispatched within 1 day"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Postage</span></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" id="txt_postage" name="txt_postage" placeholder="$10 Royal Mail International Signed"></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Return policy</span></td>
                            </tr>
                            <tr>
                              <td><textarea id="txt_return_policy" name="txt_return_policy" placeholder="14 days refund, buyer pays return postage" rows="5" class="form-control"></textarea></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                       <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                              <tr>
                                <td align="center"><img src="../GIF/money.png" width="130" height="108" alt=""/></td>
                              </tr>
                              <tr>
                                <td height="30" align="center" class="simple_maro_16"><strong class="simple_red_16">Price</strong></td>
                              </tr>
                          </tbody>
                        </table></td>
                        <td align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td width="33%" valign="top"><span class="simple_maro_14">Price (USD)</span></td>
                                    <td width="33%" height="30" valign="top"><span class="simple_maro_14">Market Bid</span></td>
                                    <td width="33%" height="30" valign="top"><span class="simple_maro_14">Market Days</span></td>
                                    </tr>
                                  <tr>
                                    <td><input class="form-control" id="txt_price2" name="txt_price2" placeholder="0.00" style="width:100px"></td>
                                    <td><input class="form-control" id="txt_mkt_bid" name="txt_mkt_bid" placeholder="0.00" style="width:100px"></td>
                                    <td><input class="form-control" id="txt_mkt_days" name="txt_mkt_days" placeholder="0.00" style="width:100px"></td>
                                    </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" valign="top"><span class="simple_maro_14">Escrowing Policy</span></td>
                            </tr>
                            <tr>
                              <td>
                              <select id="dd_escrow" name="dd_escrow" class="form-control">
                                <option value="ID_ESC_ANY">Accept any escrower</option>
                                 <option value="ID_ESC_FOLLOWING">Accept the following escrowers</option>
                                <option value="ID_ESC_DONT">Don't accept escrowers</option>
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td height="30" align="left" valign="top"><span class="simple_maro_14">Escrower 1</span></td>
                                  </tr>
                                  <tr>
                                    <td align="left">
                                    <input class="form-control" id="txt_escrower_1" name="txt_escrower_1" placeholder="Escrower address"></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="top"><span class="simple_maro_14">Escrower 2</span></td>
                                  </tr>
                                  <tr>
                                    <td align="left">
                                    <input class="form-control" id="txt_escrower_2" name="txt_escrower_2" placeholder="Escrower address"></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="top"><span class="simple_maro_14">Escrower 3</span></td>
                                  </tr>
                                  <tr>
                                    <td align="left">
                                    <input class="form-control" id="txt_escrower_3" name="txt_escrower_3" placeholder="Escrower address"></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td align="right"><a class="btn btn-primary" href="#" onclick="$('#form_new_item').submit()" style="width:120px"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Add Item</a></td>
                      </tr>
                    </tbody>
                  </table>
                  </form>
             
                  
        <?
	}
	
	function showItemPanel()
	{
		?>
        
           <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="21%"><img src="../../template/template/GIF/empty_pic_prod.png" width="100" height="100" class="img-rounded"/></td>
                        <td width="62%" align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td align="left"><a class="maro_14" href="#">Product 1</a></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_maro_12">vfd vdf vfd vfd vfdv fdv df bgb gfb gf bgf bgf bgfb gf bg gfb gf bgfb gfb gfb gfb g g gg g ggfb gf bgf bgfb fg </td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_red_10">Seller : ...csdcer5t54t54...</td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="17%" align="center" valign="top"><table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="50" align="center" class="simple_red_20"><strong>$2.12</strong></td>
                            </tr>
                            <tr>
                              <td align="center">
                                
                                <table width="80" border="0" cellspacing="0" cellpadding="0" id="tab_buts" name="tab_buts">
                                  <tbody>
                                    <tr>
                                      <td>
                                         <a class="btn btn-sm btn-primary" href="#" onclick="javascript:$('#tab_32').css('display', 'block'); $('#tab_buts').css('display', 'none');" title="Buy Now" data-toggle="tooltip" data-placement="top">
                                          <span class="glyphicon glyphicon-ok" ></span>
                                          </a>
                                        </td>
                                      <td>
                                        <a class="btn btn-sm btn-warning" href="#" title="Add to Cart" data-toggle="tooltip" data-placement="top" onclick="javascript:$('#tab_32').css('display', 'block'); $('#tab_buts').css('display', 'none');">
                                          <span class="glyphicon glyphicon-shopping-cart" ></span>
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                            </tr>
                            <tr>
                              <td align="center">
                              
                              <table width="90" border="0" cellspacing="0" cellpadding="0" id="tab_32" name="tab_32" style="display:none">
                                <tbody>
                                  <tr>
                                    <td align="center"><input class="form-control" style="width:40px" placeholder="0"></td>
                                    <td align="center">&nbsp;
                                     <a class="btn btn-sm btn-primary" href="#" title="Set Qty" data-toggle="tooltip" data-placement="top" style="height:32px">
                                          <span class="glyphicon glyphicon-ok" ></span>
                                          </a>
                                     </td>
                                  </tr>
                                </tbody>
                              </table>
                              
                              </td>
                            </tr>
                            </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
	
	function showItems()
	{
		?>
        
            <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="22%" align="center">&nbsp;</td>
                        <td width="78%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
	
	function showButs()
	{
		?>
        
           <table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="20%" align="right"> 
                  <a href="new_item.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Offert </a></td>
                  <td width="21%" align="right">
                  <a href="my_offerts.php" class="btn btn-warning"><span class="glyphicon glyphicon-th-list"></span>&nbsp;My Offerts </a>
                  </td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
}
?>