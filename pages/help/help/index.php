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
                        <td><span class="font_18"><strong>Overview</strong></span></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td class="font_14">Mask Network is a decentralized peer to peer network that allows you to trade virtual or real goods. It is the first virtual market that is not coordinated by a central structure where you should not ask for anyone's approval to sell your products or transact virtual goods. MaskNetwork is a peer-to-peer community, impossible to censor, made up of people who want to trade directly with each other without any intermediate or restrictions.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="font_14"> To sell on eBay, you have to reveal your identity, to use PayPal as a payment method and follow a very long list of requirements regarding the products you can sell. If you meet the conditions, you will pay a minimum of 15% in sales commissions.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="font_14">As in MaskNetwork there is no central server or administrator, you should not reveal your identity or ask somebody’s approval before posting a notice of sale. MaskNetwork is an open network that does not give access to anyone, and the costs to have a decentralized store are minimal.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">The network can be accessed directly through a web browser, accessing one of the Web nodes or
through a program that you can download and install on your local computer (desktop node).</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"> To ease interaction of buyers with vendors, we integrated in the network a series of tools that help users trade or communicate in maximum security conditions. Tools such as escrow integrated service, multiple signatures, addresses protection or secure messages are the basic elements of MaskNEtwork.</td>
                      </tr>
                      <tr>
                        <td>&nbsp; </td>
                      </tr>
                      <tr>
                        <td class="font_14">Because it is a free market, traders are able to choose what payment methods they want and even in what currency to make the payment. You can ask to be paid in MaskCoin (cryptographic currency underlying the network) or in any alternative asset circulating in the network.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="font_16">MaskCoin</strong></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td><span class="font_14">MaskCoin (MSK) is the currency underlying the network. For any transaction or service you pay a small fee in MaskCoins. MaskCoin is a decentralized cryptographic currency, similar to Bitcoin, with a limited quantity by code to 100,000,000 coins, which will be distributed gradually at a maximum rate of 1,000,000 coins / year. Initially 1,000,000 coins will be available.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Creation of new coins is done in two ways. About half of the 1,000,000 coins annually created are given to miners (those who provide network security) and another half is distributed by interest to regular users. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="font_16">MaskCoin Mining</strong></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td class="font_14">Unlike Bitcoin, where absolutely all new coins created are given to the miners, in MaskNetwork any user who has an address with a minimum of 10 MSK will receive a daily interest. Interest is calculated based on the amount of MaskCoins in this market.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Just like Bitcoin, in order to be accepted by the rest of the network, a new block must contain a so-called<em> proff-of-work</em>. The proof-of-work requires miners to find a number called a nonce, such that when the block content is hashed along with the nonce, the result is numerically smaller than the network's <em>difficulty target</em>.  Difficulty target is basically a big number. This proof is easy for any node in the network to verify, but extremely time-consuming to generate. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Every  blocks (approximately 1440 / day), the minimum difficulty target is adjusted based on the network's recent performance (las 1000 blocks), with the aim of keeping the average time between new blocks at ten minutes. In this way the system automatically adapts to the total amount of mining power on the network</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">The proof-of-work system, alongside the chaining of blocks, makes modifications of the block chain extremely hard as an attacker must modify all subsequent blocks in order for the modifications of one block to be accepted. As new blocks are mined all the time, the difficulty of modifying a block increases as time passes and the number of subsequent blocks.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Unlike Bitcoin where the difficulty is the same for everyone, in MaskNetwork difficulty varies depending on the amount of coins owned by the address signing the block and is called minimal difficulty.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskCoin minimum difficulty is valid for addresses that contain 10,000 MSK and creates a new block. An address which owns for example 1000 MSK will have to do a job 10 times harder than an address that contains 10,000 MSK. As miners need addresses containing coins, besides specialized hardware, it is very difficult to form monopolies because miners must constantly balance the computing power with the coins they hold.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">An address can sign a block once in every 1000 blocks generated by miners. If the block is accepted by the network and wins the "race", the address cannot spend coins for 1,000 blocks. Basically, besides specialized hardware the miners need addresses to have a considerable amount of MaskCoins.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Even if a miner will hold over 50% of the computing power he/she will need the help of those who own coins to take control of the network as he/she can be easily surpassed by a miner with a computing capacity of 100 or less but who has access a quantity of coins of 100 times higher. Even if there is such a player to take control of more than 50% of the mined effective power (computing power plus coins) the network will be minimally affected and absolutely no transaction can be reversed. The widespread algorithm prevents this.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">The basic rule of Mask Network is that once a block was mined and added to blockchain, all contained transactions become final. In MaskNetwork the concept of rollback or of confirmations does not exist. If a transaction is supported by the network, no one can do anything to change it. We cannot say the same thing for example in Bitcoin, where a player with a capacity of mining representing 51% of total, may cancel transactions already included in blockchain with devastating consequences for Bitcoin.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_interest"><strong class="font_16">Distribution by interest</strong></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskCoin is also distributed through interest. Any address that owns at least 10 MSK will receive a daily interest. The only restriction is that immediately after receiving interest, that address will not be able to spend coins for 1440 blocks. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Interest varies permanently so it does not generate more than 500,000 coins / year in interests. The remaining 500,000 are generated by miners.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Interest formula is:</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" class="simple_red_22">D=50000000/Q</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="font_14">where D is the current annual interest and Q is the amount of currency in circulation. For example, if there are 1,000,000 in circulation, the annual interest rate is 50%.</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="font_16">Consensus Algorithm</strong></td>
                      </tr>
                      <tr>
                        <td ><hr></td>
                      </tr>
                      <tr>
                        <td class="font_14">MaskNetwork is a peer to peer network consisting of dozens of interconnected nodes that work together to keep the database synchronized. The same version of the distributed database must be at any time on all nodes participating in the network. This requires a clear set of rules that allow nodes to synchronize quickly after receiving a new block. The set of rules are called consensus algorithm. The best example to understand the algorithm is to describe step by step what happens when a user sends a new data package in the network. A data package may contain a transaction or other operations, such as renting a name for the address.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">So :</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 1</strong> : Maria wants to send 1 MSK to Bob and uses a web node to send the transaction. A data package is formed and injected in the network to the remaining nodes. Any node that will receive this package will send it on to other nodes, after having first verified the accuracy of the data.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 2</strong> : The package is received by miners who integrate the package in a block, along with other recently received packages. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 3</strong> : One of the miners finds a solution and sends the newly formed block to the network for verification. The block will be signed to an address containing a minimum of 1 MSK.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 4</strong> : The block is received by nodes one by one. Nodes verify correctness and if everything is okay send the block to other nodes without performing the instructions contained in the block. It just adds the current block to other blocks recently received with the same number.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 5</strong> :  Immediately after receiving a new valid block, the nodes send to the network a data package called confirmation package, announcing that the network has received a new valid block. The confirmation package is signed by the addresses containing a minimum X MSK where X is a variable figure, of minimum 10. In general nodes select all addresses that contain minimum X MSK and sign confirmation packages with each of them, which they inject into the network. The sending of the confirmation package is received only for the first block received. If for example the network is working on block number 1000, the nodes will send the confirmation only for the first block received with 
number 1000. The blocks with the same number that are received by node thereafter are only sent to other nodes without any confirmation package.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 6</strong> : After receiving the first new valid block and sending confirmation packages, all nodes wait 20 seconds, during which they collect confirmation packages from other nodes. Also this time other available blocks injected into the network by other miners can be received. The new packages received are automatically integrated into the next block.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 7</strong> : Step 7: After the first 20 seconds each node in part decides independently which block to execute. The decision is made based on confirmation packages received from the network. Nodes will execute the block that holds at least 75% of votes. If there no block has 75% of votes, than the block signed by the address the highest balance will be executed. The voting power of a confirmation package is given by the balance of the address who signed the package. A package signed by an address that holds 1000 MSK will have a voting power of 1,000 points, for example.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14"><strong>Step 8</strong> : All the transactions contained in the block found in step 7 are executed and it is impossible to come back on them. The block is added to the remaining blocks received (called blockchain). The chain of blocks has only one version and there is no concept of ramifications.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">If two or more miners inject a block in the network at the same time, the consensus algorithm will quickly decide the winning block based on the votes of the addresses that hold MSK. As we said, votes are not equal but depend on the amount of coins held by an address.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">If an attacker has over 50% of the mining power (computing power plus coins) he/she can only send 
blocks that do not contain all the packages received or which contain only the packages agreed by him/her. It will not greatly affect the network because the packages not included in a block are automatically re-injected in the network by senders until they are included in a block. Even if an attacker has 90% of the mining power, about once in 10 blocks there will be an honest miner to include a block in all transactions received.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Sooner or later two groups of users who will participate in mining will be formed. The first category is represented by those who have most coins. The second category is represented by those who hold the majority of computing power. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Those who hold the majority of computing power will never hold most coins because they have high 
fixed costs (electricity, new equipment, etc ...) and will be forced to sell much of the mined coins to cover these costs.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">Those who have most coins in circulation will never hold a very high computing power because they 
should make massive investments they will hardly recover. For them it will be more profitable to make money from interests or rental of the addresses they hold to those who have the computing power for a percentage of the profit.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="font_14">The situation where an attacker with an great mining power starts to "filter" the packages that he/she integrates in blocks will be easy to spot by the rest of the network and those who own coins will quickly stop working with the attacker because if prolonged, such an attack will affect the value in money of a coin and the first hit will be automatically the holders of currency.</td>
                      </tr>
                      <tr>
                        <td>&nbsp; </td>
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
