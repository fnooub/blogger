<?php

$data = json_decode(file_get_contents('data.json'), true);

include "Paginate.php";

$total = count($data);
$current_page = isset($_GET['trang']) ? $_GET['trang'] : 1;

$config['current_page'] = $current_page;
$config['total_rows'] = $total;
$config['base_url'] = '?trang=(:num)';
$config['per_page'] = 200;
$config['num_links'] = 20;
$config['prev_link'] = '&laquo; Trước';
$config['next_link'] = 'Sau &raquo;';

$paginate = new Paginate();
$paginate->initialize($config);

$data = $paginate->get_array($data);
echo $paginate->create_links();

/**
 * save blogger
 */

ob_start();

?>
<?xml version='1.0' encoding='UTF-8'?> 
<ns0:feed xmlns:ns0="http://www.w3.org/2005/Atom"> 
<ns0:generator>Blogger</ns0:generator>

<?php foreach ($data as $key => $row): ?>

	<ns0:entry>
		<ns0:category scheme="http://www.blogger.com/atom/ns#" term="Convert" />
		<ns0:category scheme="http://www.blogger.com/atom/ns#" term="Full" />
		<ns0:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/blogger/2008/kind#post" />
		<ns0:id>post-<?= $key+2001 ?></ns0:id>
		<ns0:content type="html"><?= htmlspecialchars($row['count_chapter'] . ' chương, ' . myfilesize($row['size']) . '<br/>---o0o---<br>' . $row['mota']) ?></ns0:content>
		<ns0:published><?= date("c", $row['time']) ?></ns0:published>
		<ns0:title type="html"><?= htmlspecialchars($row['tieude']) ?></ns0:title>
		<ns0:link href="https://docs.google.com/uc?id=<?= $row['drive_id'] ?>" rel="enclosure" type="vi" length="0"/>
		<ns0:link href="https://docs.google.com/uc?id=<?= $row['drive_id_chinese'] ?>" rel="enclosure" type="cn" length="0"/>
	</ns0:entry>

<?php endforeach ?>

</ns0:feed>
<?php
file_put_contents("data/blogger_data_{$current_page}.xml", ob_get_contents());



function myfilesize($size, $precision = 2) {
	static $units = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
	$step = 1024;
	$i = 0;
	while (($size / $step) > 0.9) {
		$size = $size / $step;
		$i++;
	}
	return round($size, $precision).$units[$i];
}