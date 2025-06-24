<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><? echo $_GET['id'];?></title>
<style type="text/css">
<!--
.style18 {font-family: Tahoma; font-size: 13px; }
.style19 {
	font-family: Tahoma;
	font-size: 14px;
	font-weight: bold;
	color: #CC3300;
}
.style20 {font-size: 16px}
.stylelink {
	font-family: Tahoma;
	font-size: 16px;
	font-weight: bold;
	color: #ff0007;
}
-->
</style>

<script type=”text/javascript” src=”js/jquery.js”></script>
<script type=”text/javascript” src=”js/jquery.lightbox-0.5.js”></script>
<script type="text/javascript">
$(function() {
$('#gallery a').lightBox();
});
</script>
<link rel=”stylesheet” type=”text/css” href=”css/jquery.lightbox-0.5.css” media=”screen” />

</head>
<?
$id=$_GET['id'];
$pr=$_GET['pr'];
$po=$_GET['po'];
$mat_group=$_GET['mat_group'];
$mat_des=$_GET['mat_des'];
$requis=$_GET['requis'];
$plant=$_GET['plant'];
$name1=$_GET['name1'];
$name2=$_GET['name2'];
$name3=$_GET['name3'];
$name4=$_GET['name4'];
$name5=$_GET['name5'];
$sname=$_GET['sname'];
$price1=$_GET['price1'];
$price2=$_GET['price2'];
$price3=$_GET['price3'];
$price4=$_GET['price4'];
$price5=$_GET['price5'];
$finalprice=$_GET['finalprice'];
//$itemtext=$_GET['itemtext'];
//mysql_query("SET NAMES utf8");
//mysql_query("SET collection_connection='tis620_thai_ci'");
/*
include "conn.php";
$db = "picture_sap";
$tb="images_test";
mysql_select_db($db) or die(" คำสั่งไม่ทำงาน");
mysql_query("SET NAMES UTF8");

$sql = " SELECT MAT_CODE as a,MAT_NAME as a2,PICTURE as b,MAT_GROUP as c,MAT_SIZE as d,REQUISTIONER as e,PR as f,PO as g,DEPARTMENT as h,DESCRIPTIONS as i,DIRECTION as j,VENDOR_1 as k,DETAIL_1 as l,VENDOR_2 as m,DETAIL_2 as n,VENDOR_3 as o,DETAIL_3 as p,VENDOR_4 as q,DETAIL_4 as r,VENDOR_5 as s,DETAIL_5 as t,VENDOR_SELECT as u,LAST_PO as v,FINAL_PRICE as w,REMARKS as x FROM $tb where MAT_CODE = '$id'";
$db_query=mysql_db_query($db,$sql);
$num_rows=mysql_num_rows($db_query);
$show=mysql_fetch_array($db_query);
$id_img=$show['a'];
$a2=$show['a2'];
$b=$show['b'];
$c=$show['c'];
$d=$show['d'];
$e=$show['e'];
$f=$show['f'];
$g=$show['g'];
$h=$show['h'];
$i=$show['i'];
$j=$show['j'];
$k=$show['k'];
$l=$show['l'];
$m=$show['m'];
$n=$show['n'];
$o=$show['o'];
$p=$show['p'];
$q=$show['q'];
$r=$show['r'];
$s=$show['s'];
$t=$show['t'];
$u=$show['u'];
$v=$show['v'];
$w=$show['w'];
$x=$show['x'];
*/
?>
<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">
	<div align="center">
	<? 
	//$filename = "material/$id.JPG";
	//$error_img = "error/no-image.JPG";
	//if (file_exists($filename)) {
    //echo "<img src=\"material/$id.JPG\" />";
	//}else{
    //echo "<img src=$error_img  width=\"400\" height=\"400\" />";
	//}


	$noimage = "images/no_Image_Available.jpg";
	$dirname = "material/$id/";
	$images = glob($dirname."*.{JPG,jpg,jpeg,png}", GLOB_BRACE); // แก้ไขการใช้ glob มาใช้แบบ GLOB_BRACE -- PONK 06-06-2020
	//$images = glob($dirname."*.JPG");
	
		foreach($images as $image) {
			echo '<a href="'.$image.'" target=_blank><img src="'.$image.'" width="200" /></a>&nbsp;';
		}

	?>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="2"style="text-align:center"><span class="stylelink"><a href="upload.php?id=<?=$id;?>" target="_blank">คลิกเพื่ออัพโหลดรูปเพิ่มเติม</a></span></td>
  </tr>
  <tr>
    <td colspan="2"><span class="style19"><br />**หากมีข้อผิดพลาดหรือข้อสงสัย กรุณาติดต่อแผนกไอที</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  <td colspan="2">
  <table width="640" border="1" cellpadding="0" cellspacing="0" bordercolor="#000066">
  <tr>
    <td width="165"><span class="style18">Material Code : </span></td>
    <td width="469"><span class="style18">&nbsp;<? echo $id;?></span></td>
  </tr>
  <tr>
    <td><span class="style18">Material Name : </span></td>
    <td><span class="style18">&nbsp;<? echo str_replace("@","\"",$mat_des);?></span></td>
  </tr>
  <tr>
    <td class="style18">Material Group :</td>
    <td><span class="style18">&nbsp;<? echo $mat_group;?></span></td>
  </tr>
  <tr>
    <td class="style18">ขนาด : </td>
    <td><span class="style18">&nbsp;<? //echo $d;?></span></td>
  </tr>
  <tr>
    <td class="style18">ผู้ขอซื้อ: </td>
    <td><span class="style18">&nbsp;<? echo $requis;?></span></td>
  </tr>
  <tr>
    <td class="style18">PR No. : </td>
    <td><span class="style18">&nbsp;<? echo $pr;?></span></td>
  </tr>
  <tr>
    <td><span class="style18">PO No.  : </span></td>
    <td><span class="style18">&nbsp;<? echo $po;?></span></td>
  </tr>
  <tr>
    <td class="style18">Plant : </td>
    <td class="style18">&nbsp;<? echo $plant;?></td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดสินค้า : </td>
    <td class="style18">&nbsp;<? //echo $itemtext;?></td>
  </tr>
  
  <tr>
    <td class="style18">แหล่งที่ซื้อ 1 : </td>
    <td class="style18">&nbsp;
	<? if($name1==''){
	echo "<b><font color=\"red\">ไม่มีประวัติการเปรียบเทียบ</font></b>";
	}else{
	echo $name1;
	}
	?>
	</td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดการซื้อ 1 : </td>
    <td class="style18">&nbsp;<? echo $price1;?></td>
  </tr>
  <tr>
    <td class="style18">แหล่งที่ซื้อ 2 : </td>
    <td class="style18">&nbsp;<? echo $name2;?></td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดการซื้อ 2 : </td>
    <td class="style18">&nbsp;<? echo $price2;?></td>
  </tr>
  <tr>
    <td class="style18">แหล่งที่ซื้อ 3 : </td>
    <td class="style18">&nbsp;<? echo $name3;?></td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดการซื้อ 3 : </td>
    <td class="style18">&nbsp;<? echo $price3;?></td>
  </tr>
  <tr>
    <td class="style18">แหล่งที่ซื้อ 4 : </td>
    <td class="style18">&nbsp;<? echo $name4;?></td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดการซื้อ 4 : </td>
    <td class="style18">&nbsp;<? echo $price4;?></td>
  </tr>
  <tr>
    <td class="style18">แหล่งที่ซื้อ 5 : </td>
    <td class="style18">&nbsp;<? echo $name5;?></td>
  </tr>
  <tr>
    <td class="style18">รายละเอียดการซื้อ 5 : </td>
    <td class="style18">&nbsp;<? echo $price5;?></td>
  </tr>
  <tr>
    <td class="style18">ซื้อที่ : </td>
    <td class="style18" style="font-weight:bold;">&nbsp;<? echo $sname;?></td>
  </tr>
  <tr>
    <td class="style18">ราคาที่ซื้อ :</td>
    <td class="style18"  style="font-weight:bold;">&nbsp;<? echo $finalprice;?></td>
  </tr>
  <!--
  <tr>
    <td class="style18">เอกสารสั่งซื้อครั้งสุดท้าย : </td>
    <td class="style18">&nbsp;<? //echo $v;?></td>
  </tr>
  <tr>
    <td class="style18">ราคาที่ซื้อครั้งสุดท้าย : </td>
    <td class="style18" style="font-weight:bold;">&nbsp;<? //echo $w;?></td>
  </tr>
  -->
  <tr>
    <td class="style18">หมายเหตุ : </td>
    <td class="style18">&nbsp;<? 
	if($po==''){
	echo "<b><font color=\"red\">ไม่มีประวัติการสั่งซื้อ</font></b>";
	}else{
	echo "-";
	}
	?>	
  </td> 
  </tr>
   </table> 
    </td>
  </tr>
</table>
  
</body>
</html>
