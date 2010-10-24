<?
$pass = $_GET["pass"];
if ($pass!="stjzdjzx"){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://blog.qooza.hk/phoeagon");
	exit(0);
}
header("Content-type: application/rss+xml");
header("charset: UTF-8");
function curl_file_get_contents($durl){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $durl);
   curl_setopt($ch, CURLOPT_TIMEOUT, 5);
   curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
   curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $r = curl_exec($ch);
   curl_close($ch);
   return $r;
 }
?>

<rss version="2.0">
<channel>

<?
//settings
	$type = $_GET["site"];
	if (empty($type)) $type = "renren";
	if ($type=="renren"){
		//not ready
		$max_item = 5;
		$nick_reg = "/<title>.+?<\/title>/";
		$item_regex = "/\<div>.+?<\/div>/";
		$split_regex = "/<div>.+?<p class=\"time\">/";
		$name_format = "<title>手机人人网 - %[^的]s的状态</title>";
		$convert_encoding = 0;
		$default_user = "";
		$url_pre = "";
		$url_post = "";
	}
	else if ($type=="douban"){
		$max_item = 5;
		$nick_reg = "/<title>(.|\s)+?<\/title>/";
		$name_format = "<title>\n%[^-]s";
		$item_regex = "/\<div class=\"item\">(.|\s)+?<\/div>/";
		$split_regex = "/<div class=\"item\">(.|\s)+?<span class=\"rel\">/";
		$convert_encoding = 0;
		$default_user = "";
		$url_pre = "http://m.douban.com/people/";
		$url_post = "/miniblogs?type=saying&session=932ca0ee_42513437";
	}
	else if ($type=="163xq"){
		$max_item = 5;
		$nick_reg = "/<title>(.|\s)+?<\/title>/";
		$name_format = "<title>网易博客欢迎您-%[^<]s";
		$item_regex = "/\<div class=\"liitm\">(.|\s)+?<\/div>/";
		$split_regex = "/<div class=\"liitm\">(.|\s)+?<br\/>/";
		$convert_encoding = 0;
		$default_user = "";
		$url_pre="http://wap.blog.163.com/w2/feelings/feelingList.do?hostID=";
		$url_post = "";
	}
	else if ($type=="qq"){
		$max_item = 5;
		$nick_reg = "/name : '(.|\s)+?'\/*/";
		$name_format = "name : '%[^']s";
		$item_regex = "/remark=[^<]+?id=(.|\s)+?>(.|\s)+?<\/span>/";
		$split_regex = "/<span\s+?id=[^>]+?>(.|\s)+?<\/span>/";
		$convert_encoding = 0;
		$default_user = "";
		$url_pre="http://taotao.qq.com/cgi-bin/emotion_cgi_msglist?ftype=0&sort=0&start=0&num=20&uin=";
		$url_post = "";
	}
	else if ($type=="sina"){
		$max_item = 7;
		$nick_reg = "/<div class=\"userNm txt_b\">(.|\s)+?<\/div>
/";
		$name_format = "<div class=\"userNm txt_b\">%[^<]s";
		$item_regex = "/<p class=\"wgtCell_txt\">(.|\s)+?<div class=\"wgtCell_txtBot\">
/";
		$split_regex = "/<p class=\"wgtCell_txt\">(.|\s)+?<\/p>
/";
		$convert_encoding = 0;
		$default_user = "";
		$url_pre="http://v.t.sina.com.cn/widget/widget_blog.php?height=500&skin=wd_01&showpic=1&uid=";
		$url_post = "";
	}
	
?>

<?

$username=$_GET["id"];
$username=ereg_replace("[^A-Za-z0-9]", "", $username);
if ( empty($username) ) { 
         $username=$default_user;    // <-- change this to your username! 
		 //above is ylen
		 //329096140 is chen xinqi
 }
?>

<?

$url =$url_pre.$username.$url_post;
$content = curl_file_get_contents($url);
//now the page is retrieved.
if ($convert_encoding)
	$content = mb_convert_encoding($content,"UTF-8","GBK");
	//echo $content;
?>

<description><![CDATA[ this page is generated automatically.
php by phoeagon.]]></description>

<?
//now getting the tittle

preg_match($nick_reg, $content,$nick);
//print_r( $nick);
$nick = sscanf( $nick[0],$name_format  );
//$nick[0] = strip_tags($nick[0]);
echo "<title>".$nick[0]."的状态</title>\n";
?>

<?
//the URL to get what you need

	preg_match_all($item_regex,$content,$items);
	//print_r($items);
	
	foreach ($items[0] as $cc){
		$max_item--;
		if ($max_item<=0)break;
		
		preg_match($split_regex,$cc,$data);
		//print_r($data);
		$data[0] = strip_tags($data[0],"<img>");
		
		echo "<item>\n";
		echo "<link>http://blog.qooza.hk/phoeagon</link>\n";
		echo "<title>".$nick[0]."</title>\n";
		echo "<guid>".md5($data[0])."</guid>\n";
		echo "<description><![CDATA[".$data[0]."]]></description>
\n";
		echo "</item>\n";
		//now $data[0] is the line we need, if strip tagged.
	}
?>
</channel>
</rss>
