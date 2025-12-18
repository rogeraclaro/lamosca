<?php
	header('Content-type: application/rss+xml; charset=utf-8');

	require '../phpincludes/functions.php';
	require '../phpincludes/html2text/class.html2text.inc';

	db_connect();

	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

	$rss_date = "";
	$project_rss = "";
	$rss_items=12;
	$rss_count=0;

	$sqlstr = "SELECT id,inturl,rss_date,title,text_1 FROM $projecttable WHERE active = 1 AND id > 1 ORDER BY rss_date DESC";
	$erg = db_query($dbname, $sqlstr);

	while($row = db_fetch_array($erg)) {
		
		if($rss_count<$rss_items) {
		
			$perma = "http://www.lamosca.com/";
			
			$sqlstr2 = "SELECT id,inturl,content FROM $categorytable WHERE id";
			$erg2 = db_query($dbname, $sqlstr2);
			while($row2 = db_fetch_array($erg2)) {
				$projects = explode(",",$row2['content']);
				for($p=0;$p<count($projects);$p++) {
					if($projects[$p]==$row['id'])
						$cat_inturl = $row2['inturl'];
				}	
			}
			
			if($cat_inturl && $row['inturl']) {
				$perma .= $cat_inturl."/".$row['inturl'].".html";
				$rss_img = false;
				$get_img = buildModules($cat_inturl,$row['inturl']);
			}
			
			$project_rss .= "    <item>\n";
			$project_rss .= "      <title>".prepXml($row['title'])."</title>\n";
	
			if($perma)
				$project_rss .= "      <link>".$perma."</link>\n";
	
			if($rss_img) {
				$image_size = getimagesize($rss_img_srv);
				if($image_size[0] > 354) {
					$image_width = 354;
					$image_height = round($image_size[1] / $image_size[0] * $image_width);
				} else {
					$image_width = $image_size[0];
					$image_height = $image_size[1];
				}
				$rss_img_src = "<img src=\"".$rss_img."\" alt=\"\" width=\"".$image_width."\" height=\"".$image_height."\" />";
				if($perma)
					$rss_img_src = "<a href=\"".$perma."\">$rss_img_src</a>";
				$rss_img_src .= "<br /><br />";
			} else {
				$rss_img_src = "";
			}
	
			//$project_rss .="      <content:encoded><![CDATA[\n".$rss_img_src.prepHtml($subtitle)."\n]]></content:encoded>\n";
			$project_rss .= "      <description><![CDATA[\n".$rss_img_src.prepXml($row['text_1'])."\n]]></description>\n";
			$project_rss .= "      <pubDate>". date("r", $row['rss_date']) ."</pubDate>\n";
			if($perma)
				$project_rss .="      <guid isPermaLink=\"true\">".$perma."</guid>\n";
			$project_rss .= "    </item>\n\n";
			
			if(!$rss_date)
				$rss_date = $row['rss_date'];
				
			$rss_count++;
		
		}
		
	}

	if(!$rss_date)
		$rss_date = time();
		
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>LAMOSCA RSS feed</title>
    <link>http://www.lamosca.com/</link>
    <atom:link href="http://www.lamosca.com/rss.xml" rel="self" type="application/rss+xml" />
    <description>LAMOSCA RSS feed</description>
    <language>es</language>
    <pubDate><?php echo date("r", $rss_date); ?></pubDate>
    <lastBuildDate><?php echo date("r", $rss_date); ?></lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator>Lamosca SCCL</generator>
    <managingEditor>info@lamosca.com (Lamosca)</managingEditor>
    <webMaster>info@lamosca.com (Lamosca)</webMaster>
    <ttl>15</ttl>
 
<?php

	echo $project_rss;

?>

  </channel>
</rss>
<?php
	db_close();
?>