<?php
$array_date = array(
					"Minggu",
					"Senin",
					"Selasa",
					"Rabu",
					"Kamis",
					"Jumat",
					"Sabtu",
				);
$w = date("w", strtotime($tunggal->date));
$html = "
<html>
<style>
.big{
	font-family: 'Courier';
	font-size: 10px;
	font-weight: bold;
}
.small{
	font-family: 'Courier';
	font-size: 9px;
}
p{
	margin-bottom: -5px
}
</style>
<body>
	<table border=\"0\">
		<tr>
			<td align=\"center\" class=\"big\" colspan=\"5\" width=\"195px\"><p style=\"font-size: 15px;margin-bottom: -10px\"><img width=\"120px\" src=\"".base_url()."assets/image/logo_receipt.png\" /></p></td>
		</tr>
		<tr>
			<td align=\"center\" class=\"big\" colspan=\"5\"><p>Jl. Pancurawis No 905<br />Dpn Balai Desa</p></td>
		</tr>
		<tr>
			<td align=\"center\" class=\"big\" colspan=\"5\" style=\"font-size:8px\">Telp (0281) 632522, HP/WA 085726100714</td>
		</tr>
		<tr>
			<td class=\"small\" colspan=\"5\" style=\"line-height:10px\"><p><br /><!--".$tunggal->id." - -->Pancurawis</p></td>
		</tr>
		<tr>
			<td class=\"small\" colspan=\"5\"><p>".$array_date[$w].", ".date("d-m-Y H:i:s", strtotime($tunggal->date))."</p></td>
		</tr>
		<tr>
			<td class=\"small\" colspan=\"5\" style=\"line-height:5px\"><br /><hr style=\"height:0.05em\"/></td>
		</tr>";
$i = 1;
$total_amount = 0;
$ext_height = 0;
foreach($jamak_detail->result() as $row){
	$sub_total = $row->quantity * $row->price;
	$total_amount += $sub_total;
	$decimal_cnt = strlen(substr(strrchr($row->quantity, "."), 1));
	$str_qty = number_format($row->quantity, $decimal_cnt, ',', '.');

	$str_pad_cnt = 0;
	if($decimal_cnt == 0)
		$str_pad_cnt = $decimal_cnt * -1 + 3;
	else
		$str_pad_cnt = $decimal_cnt * -1 + 2;
	$str_pad_add = str_pad(null, $str_pad_cnt , 'a', STR_PAD_RIGHT);
	$html .= "
		<tr style=\"line-height:7px\">
			<td align=\"center\" class=\"small\" width=\"20px\" ><p>".$i.".</p></td>
			<td class=\"small\" width=\"175px\" colspan=\"4\" ><p>".$row->name."</p></td>
		</tr>
		<tr style=\"line-height:7px\">
			<td align=\"right\" class=\"small\" width=\"90px\"  colspan=\"2\"><p>".$str_qty."<span style=\"color:#FFF\">".$str_pad_add."</span></p></td>
			<td align=\"left\" class=\"small\" width=\"15px\" ><p>x</p></td>
			<td align=\"right\" class=\"small\" width=\"40px\" ><p>".number_format($row->price, 0, ',', '.')."</p></td>
			<td align=\"right\" class=\"small\" width=\"50px\" ><p>".number_format($sub_total, 0, ',', '.')."</p></td>
		</tr>";
	$ext_height = 8 + $ext_height;
	$i++;
}
$html .= "
		<tr>
			<td class=\"small\" colspan=\"5\" style=\"line-height:5px\"><br /><hr style=\"height:0.05em\"/></td>
		</tr>
		<tr>
			<td align=\"left\" class=\"small\" colspan=\"4\"><p>Total</p></td>
			<td align=\"right\" class=\"small\" ><p>".number_format($total_amount, 0, ',', '.')."</p></td>
		</tr>";
$html .= "
		<tr>
			<td align=\"left\" class=\"small\" colspan=\"4\"><p>Bayar</p></td>
			<td align=\"right\" class=\"small\"><p>".number_format($tunggal->paid, 0, ',', '.')."</p></td>
		</tr>
		<tr>
			<td align=\"left\" class=\"small\" colspan=\"4\"><p>Kembali</p></td>
			<td align=\"right\" class=\"small\"><p>".number_format($tunggal->paid - $total_amount, 0, ',', '.')."</p></td>
		</tr>
		<tr>
			<td align=\"center\" class=\"small\" colspan=\"5\"><p># Terima kasih #</p></td>
		</tr>
	</table>
</body>
</html>";
//echo $html;

//var_dump(scandir('application/third_party/TCPDF-master'));
require_once('application/third_party/TCPDF-master/tcpdf.php');
$pageLayout = array(72, 85 + $ext_height); //  or array($width, $height) 
$pdf = new TCPDF(null, PDF_UNIT, $pageLayout, true, 'UTF-8', false);
//$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(-2, 0);
$pdf->SetAutoPageBreak(false, 0);
$pdf->SetFont('courier', '', 7);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
$pdf->Output('nota_'.$tunggal->id.'.pdf', 'I');
?>