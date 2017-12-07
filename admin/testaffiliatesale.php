<?php 

 $reffid = $_REQUEST['a_aid'];
?>
<html>
<head><title>test</title>

<script id="pap_x2s6df8d" src="http://91.0.0.99/jiwok_ver2/affiliate/scripts/salejs.php" type="text/javascript">
</script>
<script type="text/javascript">
var sale = PostAffTracker.createSale();
sale.setTotalCost('120');
sale.setAffiliateID('<?=$reffid?>');
PostAffTracker.register();
</script>

</head>
<body>

</body>
</html>