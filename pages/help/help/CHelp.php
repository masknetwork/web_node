<?
class CHelp
{
	function CHelp()
	{
	}
	
	function showMenu($sel=1)
	{
		?>
            
           <br>
           <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
           <tbody>
           <tr>
           <td width="45%">
           <a href="../../help/help/index.php" class="font_14">
           <?
		      if ($sel==1)
			     print "<strong>1. Overview</strong>";
			  else
			     print "1. Overview";
		   ?>
           </a>
           </td>
           <td width="10%">&nbsp;</td>
           <td width="45%">
           <a href="../../help/help/adr_names.php" class="font_14">
           <?
		      if ($sel==5)
			     print "<strong>5. Address Names</strong>";
			  else
			     print "5. Address Names";
		   ?>
           </a>
           </td>
           </tr>
           <tr>
           <td><hr></td>
           <td>&nbsp;</td>
           <td><hr></td>
           </tr>
           <tr>
           <td>
           <a href="../../help/help/transactions.php" class="font_14">
           <?
		      if ($sel==2)
			     print "<strong>2. Transactions</strong>";
			  else
			     print "2. Transactions";
		   ?>
           </a>
           </td>
           <td>&nbsp;</td>
           <td>
           <a href="../../help/help/messages.php" class="font_14">
           <?
		      if ($sel==6)
			     print "<strong>6. Messages</strong>";
			  else
			     print "6. Messages";
		   ?>
           
           </a>
           </td>
           </tr>
           <tr>
           <td><hr></td>
           <td>&nbsp;</td>
           <td><hr></td>
           </tr>
           <tr>
           <td>
           <a href="../../help/help/addresses.php" class="font_14">
           <?
		      if ($sel==3)
			     print "<strong>3. Addresses</strong>";
			  else
			     print "3. Addresses";
		   ?>
           
           </a>
           </td>
           <td>&nbsp;</td>
           <td>
           <a href="../../help/help/adv.php" class="font_14">
           <?
		      if ($sel==7)
			     print "<strong>7. Advertising</strong>";
			  else
			     print "7. Advertising";
		   ?>
           </a>
           </td>
           </tr>
           <tr>
           <td><hr></td>
           <td>&nbsp;</td>
           <td><hr></td>
           </tr>
           <tr>
           <td>
           <a href="../../help/help/adr_options.php" class="font_14">
           <?
		      if ($sel==4)
			     print "<strong>4. Address Options</strong>";
			  else
			     print "4. Address Options";
		   ?>
           </a>
           </td>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           </tr>
           </tbody>
           </table>
        
        <?
	}
}
?>