<?php
// this src is written under the terms of the GPL-licence, see gpl.txt for futher details
function template_header()
{
    $latest_version = get_latest_version();
    echo <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title>DOSBox, an x86 emulator with DOS</title>
 <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
 <link rel="icon" type="image/x-icon" href="site_images/favicon.ico"/>
 <link rel="shortcut icon" type="image/x-icon" href="site_images/favicon.ico"/>
 <link rel="stylesheet" type="text/css" href="test.css"/>
 <link rel="stylesheet" type="text/css" href="dosbox.css"/>
 <!--[if IE]><link rel="stylesheet" type="text/css" href="IE.css"/><![endif]-->
</head>	
<body>
 <h1 id="logo">DOSBox</h1>
 <ul id="menu">
  <li><a href="news.php?show_news=1">News</a></li>
  <li><a href="crew.php">Crew</a></li>
  <li><a href="information.php?page=0">Information</a></li>
  <li><a href="status.php?show_status=1">Status</a></li>
  <li><a href="wiki/">FAQ</a></li>
  <li><a href="download.php?main=1">Downloads</a></li>
  <li><a href="comp_list.php?letter=a">Compatibility</a></li>
  <li><a href="http://vogons.zetafleet.com/index.php?c=7" target="_blank">Forum</a></li>
  <li><a href="links.php">Links</a></li>
  <li><a href="login.php">Login</a></li>
 </ul>
 <span class="indent top">Latest version:
  <a class="bold" href="download.php?main=1">$latest_version</a>
 </span>
EOT;
    echo '<table class="main">
	<tr>
		<td valign="top"><br>
';
}
function template_end()
{
	echo '
		</td>
		<td valign="top">
		<br>
		<br>
		<div class="temp">
		<center><a href="/wiki/Basic_Setup_and_Installation_of_DosBox">DOSBox tutorial</a></center>
		</div>
		<br>
		<div class="temp">
		<center><a href="/wiki/Dosbox_and_Vista">DOSBox and Vista</a></center>
		</div>
		<br>
		<div class="gog">
		<a href="http://www.gog.com/en/frontpage/pp/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f" class="goglink">
		DRM-free PC classics - 
		<span class="gog1">GOG</span><span class="gog2">.COM</span>
		</a>
		</div>
		<br>


<script type="text/javascript"><!--
google_ad_client = "pub-0841681205384106";
//rechts
google_ad_slot = "5563129217";
google_ad_width = 120;
google_ad_height = 240;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</td>
	</tr>
</table>
		<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top" width="14">
 					&nbsp;
				</td>
				<td valign="top">
<script type="text/javascript"><!--
google_ad_client = "pub-0841681205384106";
/* Regular pages 468x15,  bottom */
google_ad_slot = "6666539821";
google_ad_width = 468;
google_ad_height = 15;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</br>&nbsp;</br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but11.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">

					&nbsp;&nbsp;&nbsp;<img src="site_images/compilations.jpg" alt="Compilations">&nbsp;&nbsp;&nbsp;
					<a href="http://www.gog.com/en/frontpage/pp/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f"><img src="gog.jpg" alt="DRM-free PC classics - GOG.com"></a>&nbsp;&nbsp;&nbsp;
					<a target="_blank" href="http://t.extreme-dm.com/?login=harekiet">
					<img name=im src="http://t1.extreme-dm.com/i.gif" height=38
					border=0 width=41 alt=""></a><script language="javascript" type="text/javascript"><!--
					an=navigator.appName;d=document;function
					pr(){d.write("<img src=\"http://t0.extreme-dm.com",
					"/0.gif?tag=harekiet&j=y&srw="+srw+"&srb="+srb+"&",
					"rs="+r+"&l="+escape(parent.document.referrer)+"\" height=1 ",
					"width=1>");}srb="na";srw="na";//-->
					</script><script language="javascript1.2" type="text/javascript"><!--
					s=screen;srw=s.width;an!="Netscape"?
					srb=s.colorDepth:srb=s.pixelDepth;//-->
					</script><script language="javascript" type="text/javascript"><!--
					r=41;d.images?r=d.im.width:z=0;pr();//-->
					</script><noscript><img height=1 width=1 alt="" 
					src="http://t0.extreme-dm.com/0.gif?tag=harekiet&amp;j=n"></noscript>	
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.sourceforge.net/projects/dosbox" target="_blank"><img src="http://sourceforge.net/sflogo.php?group_id=52551&amp;type=1" width="88" height="31" border="0" alt="SourceForge.net Logo"></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://sourceforge.net/donate/index.php?group_id=52551"><img src="http://images.sourceforge.net/images/project-support.jpg" width="88" height="32" border="0" alt="Support This Project"></a></form>
					<img src="site_images/copyright.gif" alt="Copyright 2008 DOSBox">
				</td>
			</tr>
		</table><br><br><br><br><br><br>
		
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3229677-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body>

	</html>



	';
}

function template_pagebox_start( $text, $width=640 )
{
    $width -= 10; // compensate for 1px border and 4px padding on each side
    echo <<<EOT
<div style="width: ${width}px;" class="caption">$text</div>
<div style="width: ${width}px;" class="content">
EOT;
}
function template_pagebox_end()
{
    echo "</div>";
}
?>
