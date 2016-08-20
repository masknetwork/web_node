<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CHelp.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $help=new CHelp();
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_14 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

.font_141 {font-size:20px;  }
.font_141 {font-size:22px;  }
</style>

</head>

<body>

<?
    $template->showTopBar("help");
?>
 

 <div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1">&nbsp;</div>
 <div class="col-md-8" align="center" style="height:100%; background-color:#ffffff">
 
 <?
     // Location
     $template->showLocation("../../help/help/index.php", "Help", "", "Overview");
	 
	 $help->showMenu();
 ?>
 
 <br><br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><span class="font_18"><strong>Quick Overview</strong></span></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is MaskNetwork ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskNetwork is a decentralized peer-to-peer social network that rewards users for the content they create. By content we mean blog posts, comments, decentralized applications and so on. Unlike Bitcoin network that allocates 100% of new coins to miners, MaskNetwork allocates 80% of new coins to users that post captivating content, as measured by aother users votes, while 20% goes to miners.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Are users paid in US Dollars?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">No. Users are paid in a cryptocurrency, called MaskCoin. MaskCoin is a decentralized cryptocurrency used inside the network for a wide range of operations like sending transactions or voting. MaskCoins currency units can be traded, bought, and sold on the open market just like all of the other digital currencies.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is MaskCoin ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskCoin is a digital currency (think Bitcoin, which is another kind of digital currency that has been around for a while) that empowers the network. Every day, new units of the currency are created by the network and distributed to its users, who can exchange these digital currency units for actual real money.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Is the number of MaskCoins limited ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Yes. There will only ever be 21 million MaskCoins produced. It has been designed as a deflationary currency, so it has a strictly limited money supply. Broadly speaking, a deflationary currency is one that increases in value over time. Goods and services priced in a deflationary currency will therefore tend to reduce in price - all other things being equal. </td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is the difference between Bitcoin and MaskCoin ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">With other cryptocurrencies like Bitcoin, the actual currency units that are created each day are distributed to the people who run a special bitcoin software program on their computers that perform a process called Bitcoin Mining. The amount of computing power you have dictates how much money you get. MaskNetwork allows for currency mining as well, but it&rsquo;s not the primary way to earn money. Every day, new Steemit currency units that are distributed by the network to the people who engage with content creation. The more you engage, the more you get.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>How exactly is MaskCoin distributed ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Every single day 0.013% (5% / year) of undistributed coins are moved from the default network address to content creators. For example at the time, 20 millions coins are undistributed. That means, miners and content creators will receive 2600 coins every day. People who create content like blog posts, applications or even assets are rewarded for their content. People who vote content are rewarded for helping to curate the best content available on the site. Commenters who add to the discussions are paid too.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is the default network address ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">The default network address is the address that hold all the undistributed coins. The software behind MaskNetwork can't create new coins. It can only move coins to / from the default address to other addresses. The default network address spends coins when the network rewards miners / content creators and receives all network fees.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Who owns the default network address ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Nobody. It's special address that has no private keys associated and only the network can spend coins from this address. </td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is a network fee ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Every time you send a transaction or rent an address name for example, the network will charge you a small fee. The fees goes to the default netowrk address and become again undistributed coins. The fee for a lot of netowrk services is flat (0.0001 / day). For transactions the fee is 0.01%.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Are the new coins distributed equally to miners and content creators ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">No. Content creators will receive 30% of the newly distrbuted coins. Miners will get 20%. People who vote content will receive 20%, while commenters and application creators receive each 15%.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>When are users paid ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Users are paid every 24 hours.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Who pays the users ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">In short, the users are paid by the protocol. No organization / company controls the network. No one runs MaskNetwork. It is run collectively by the users who runs network nodes, and any changes to the protoco system have to be approved by the majority of users before they are implemented. Rewards distribution is an automated hard coded process. </td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What happens when all coins are distributed ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Every day around ~0.13% of undistributed coins are rewarded to users. That means the undistributed coins will never end. The default network address will never reach 0 coins in balance but the number of daily distributed coins will be lower and lower each day. Not to mention that the network receives thousand of small fees every day. Do a simple test. Substract 5% from 100 MSK and the substract 5% from what's left. You will never reach 0.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Fewer and fewer coins will be distributed each day, forever ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">No. It's a little bit more complicated. Remember that the network spends coins when it rewards miners / content creators and receives coins when users pay network fees. The fees received by the default network address become undistributed coins again until they are paid as reward and the cycle repeats indefinitely. Now, if the fees received by the network in the last 24 hours are lower than what network has spend on rewards, the final network balance will be lower and the rewards will also be lower from day to day because they represent a percent of default address balance. But if the the fees are higher than what the netowrk spends, the network balance will be higher and the rewards will start to increase also. this will happen sooner or later. The  system was designed to reach equilibrum on the long term.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>Is MaskNetwork completely anonymous ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">No. It is possible for someone with significant dedicated resources like governments to track your transactions by examining the public blockchain. It won't be easy but it's possible. Overall, your anonimity is much closer to cash than to a credit card.</td>
                      </tr>
                      <tr>
                        <td class="font_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>How are MaskCoins generated / mined ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskCoins are generated bby a competitive and decentralized process called mining. The process involves that individuals are rewarded by the network for thei services. Bitcoin miners are processing transactions and securing the networks using regular computers or graphics cards and are collecting MaskCoins in exchange. 20% of all newly distributed coins are used by the network to reward miners.</td>
                      </tr>
                      <tr>
                        <td>&nbsp; </td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>I have a computer. How can i start mining ?</strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">The first step is to be elected as a miner. The consensus mechanism is called delegated proof of work (DPOW). Under DPOW, the stakeholders can elect any number of miners to generate new blocks. The top 100 elected miners are called delegates and are authorized to create new blocks. If you were elected as a delegate, all you have to do is to install the wallet on your computer and start mining. Go to Admin / CPU Mining for more informations.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_16"><strong>What is the difference between DPOW and the classic POW ? </strong></td>
                      </tr>
                      <tr>
                        <td class="font_14">Under the classic POW, any user can start minign with no stakeholder approval. A network that implements POW completely ignores stakeholders. They have nothing to say and can not participate in major network decisions. Miners are those who decide the network fate (see Bitcoin block size debate).  Delegated proof of work is more efficient, decentralized and flexible. Under DPOW, miners are elected by stakeholders that can penalize or completely deny a miner to create new blocks. Under DPOW, stakeholders can elect any number of miners to generate new blocks. The top 100 miners are called delegate miners and are allowed to generate new blocks. Depending on stakeholders support (power of votes) the network adjust the mining difficulty for each delegate. For example a miner sustained by 1000 MSK will have a much greater change to find new blocks than a miner voted by only 500 MSK (if both miners have the same hash rate power). </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
 
 
 </div>
 <div class="col-md-2" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>
