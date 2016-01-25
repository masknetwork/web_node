<?
   $symbol=$_REQUEST['symbol'];
   
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>PipsTycoon.com - <? print $_REQUEST['symbol']; ?></title>
</head>

<style>
html{
  height: 100%;
}
body {
  min-height: 100%;
}
</style>

<body>
<div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="1000px">
    
   
<!-- TradingView Widget BEGIN -->
<script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
<script type="text/javascript">
new TradingView.widget({
  "autosize": true,
  "symbol": "<? print $symbol; ?>",
  "interval": "D",
  "timezone": "exchange",
  "theme": "White",
  "style": "1",
  "toolbar_bg": "#f1f3f6",
  "withdateranges": true,
  "hide_side_toolbar": false,
  "allow_symbol_change": true,
  "interval" : "1D",
  "details": true,
  "hotlist": true,
  "calendar": true,
  "news": [
    "stocktwits",
    "headlines"
  ],
  "show_popup_button": true,
  "popup_width": "1000",
  "popup_height": "650"
});
</script>
<!-- TradingView Widget END -->

    
    </td>
  </tr>
</table>


</body>
</html>