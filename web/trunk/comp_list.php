<?php
// this src is written under the terms of the GPL-licence, see gpl.txt for futher details
include("include/standard.inc.php");
sstart();


if (isset($_GET['changeversion']) && $_GET['changeversion']==1)
{
	if (isset($_SESSION['userID'],$_POST['statusID'],$_POST['percent']))
	mysql_query("UPDATE status_games SET status=".intval($_POST['percent'])." WHERE status_games.ID=".intval($_POST['statusID']));

	Header("Location: comp_list.php?changeID=".intval($_POST['gameID'])."&letter=".letter_check($_POST['letter']));
}
if (isset($_GET['removeMSG_ID']))
{
	if (isset($user) && $user['priv']['compat_list_manage']==1)
	{
		$msgID =mysql_real_escape_string(intval(stripslashes($_GET['removeMSG_ID'])));
		mysql_query("DELETE FROM list_comment WHERE ID=$msgID");
	}
	Header("Location: comp_list.php?showID=".intval($_GET['gameID'])."&letter=".letter_check($_GET['letter']));
}
if (isset($_GET['removeVERSION_ID'],$_GET['gameID'],$_SESSION['userID']))
{
	$versionID	=mysql_real_escape_string(intval(stripslashes($_GET['removeVERSION_ID'])));
	$gameID		=intval($_GET['gameID']);
	$letter		=letter_check($_GET['letter']);
	$userID		=$_SESSION['userID'];

	if (isset($user) && $user['priv']['compat_list_manage']==1)
	mysql_query("DELETE FROM status_games WHERE status_games.ID=$versionID");

	Header("Location: comp_list.php?changeID=".$gameID."&letter=".$letter);
}
if (isset($_GET['addVersion'],$_POST['gameID'],$_POST['versionID'],$_POST['percent'],$_SESSION['userID']) && $_GET['addVersion']==1)
{
	$gameID		=mysql_real_escape_string(intval(stripslashes($_POST['gameID'])));
	$versionID	=mysql_real_escape_string(intval(stripslashes($_POST['versionID'])));
	$status		=mysql_real_escape_string(intval(stripslashes($_POST['percent'])));
	$userID		=$_SESSION['userID'];

	if (isset($_SESSION['userID']) AND $versionID != '-')
	{
		mysql_query("

		INSERT INTO
		status_games (gameID, versionID, status)
		VALUES ($gameID, $versionID, $status)
		");

		#mysql_query("UPDATE list_game SET ownerID=$userID WHERE ID=$gameID");
	}
	Header("Location: comp_list.php?changeID=".$gameID."&letter=".letter_check($_POST['letter']));
}
if (isset($_GET['post_comment'],$_POST['gameID'],$_SESSION['userID']) && $_GET['post_comment']==1)
{
	$gameID		=mysql_real_escape_string(intval(stripslashes($_POST['gameID'])));
	$subject	=mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['subject'])));
	$text		=mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['text'])));
	$userID		=$_SESSION['userID'];

	if (isset($_SESSION['userID']))
	{
		if ($_POST['subject'] == '' || $_POST['text'] == '' || strlen($_POST['subject'])>60 || strlen($_POST['text'])>1024)
		Header("Location: comp_list.php?problem=1&post_newMSG=1&showID=".$gameID."&letter=".letter_check($_POST['letter'])."#post_comment");
		else
		{
			mysql_query("

			INSERT INTO
			list_comment (gameID, ownerID, subject, text, datetime)
			VALUES
			($gameID, $userID, '$subject', '$text', NOW())
			");

			Header("Location: comp_list.php?showID=".$gameID."&letter=".letter_check($_POST['letter']));
		}
	}

}
if (isset($_GET['changing'],$_POST['ID'],$_POST['name'],$_POST['publisher'],$_POST['year'],$_SESSION['userID']) && $_GET['changing']==1)
{

	$changeID	= intval($_POST['ID']);
	$letter		= letter_check($_POST['letter']);
	$name 		= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['name'])));
	$publisher 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['publisher'])));
	$released 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['year'])));
	$moby 		= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['moby'])));
	$userID		= $_SESSION['userID'];
	$first_char 	= $name{0};


	if (check_game_owner($changeID, $_SESSION['userID'])==1 || (isset($user) && $user['priv']['compat_list_manage']==1))
	{
		if ($name == '' || $publisher == '' || !is_numeric($released))
		Header("Location: comp_list.php?letter=".$letter."&problem=1&changeID=".$changeID);
		else
		{

			if ($moby != '' AND verifyurl($moby)==false)
			Header("Location: comp_list.php?letter=".$letter."&problem=1&changeID=".$changeID);

			mysql_query("UPDATE list_game SET name='$name', publisher='$publisher', first_char='$first_char', released=$released, moby_url='$moby' WHERE ID=$changeID");
			Header("Location: comp_list.php?changeID=".$changeID."&letter=".$letter);
		}
	}
}

if (isset($_GET['removeID'],$_SESSION['userID']) && $_GET['removeID'])
{
	if (check_game_owner($_GET['removeID'], $_SESSION['userID'])==1 || (isset($user) && $user['priv']['compat_list_manage']==1))
	{
		$gameID = mysql_real_escape_string(intval(stripslashes($_GET['removeID'])));

		mysql_query("DELETE FROM list_game WHERE ID=$gameID");
		mysql_query("DELETE FROM status_games WHERE gameID=$gameID");
		mysql_query("DELETE FROM list_comment WHERE gameID=$gameID");

		if (is_numeric(letter_check($_GET['letter'])))
		Header("Location: comp_list.php?letter=num");
		else
		Header("Location: comp_list.php?letter=".letter_check($_GET['letter']));
	}
}
if (isset($_SESSION['userID']) && $_SESSION['userID'])
{
	if (isset($_GET['posting_new']) && ($_GET['posting_new']==1) && isset($_POST['name'],$_POST['publisher']))
	{
		$name 		= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['name'])));
		$publisher 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['publisher'])));
		$percent 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['percent'])));
		$released 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['year'])));
		$comment	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['note'])));
		$version 	= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['version'])));
		$moby 		= mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['moby'])));
		$userID		= $_SESSION['userID'];
		$first_char 	= $name{0};
		$letter = letter_check($_POST['letter']);
		$query = mysql_query("Select name from list_game where name='$name'");
		$num = mysql_num_rows($query);

		if ($num > 0 || $name == '' || $publisher == '' || !is_numeric($released) || strlen($comment)>1024)
		Header("Location: comp_list.php?post_new=1&error=1&letter=".$letter);
		else
		{
			if (verifyurl($moby)==0 AND $moby != '')
			Header("Location: comp_list.php?post_new=1&error=1&letter=".$letter);

			mysql_query("
			INSERT INTO list_game
			(ownerID, added, name, publisher, version, first_char, released, moby_url)
			VALUES($userID, NOW(), '$name', '$publisher', '$version', '$first_char', $released, '$moby_url')");

			$AUTO_INCREMENT_ID = mysql_insert_id();

			mysql_query("INSERT INTO status_games (gameID,versionID,status) VALUES($AUTO_INCREMENT_ID, $version, $percent)");

			if ($comment != '')
			{
				$subject = 'Note:';

				mysql_query("

				INSERT INTO list_comment
				(gameID, ownerID, subject, text, datetime)
				VALUES ($AUTO_INCREMENT_ID, $userID, '$subject', '$comment', NOW())
				");
			}

			Header("Location: comp_list.php?showID=".$AUTO_INCREMENT_ID."&letter=".$letter);
		}

	}


}

template_header();

echo '
<table width="100%">
<tr>
<td width="9">
&nbsp;
</td>
<td>';

comp_bar();


if (isset($_GET['showID']) && intval($_GET['showID']) > 0 AND (!isset($_GET['post_new']) || $_GET['post_new'] != 1))
{
	if (!isset($_SESSION['userID']))
	echo '<font face="Verdana, Arial, Helvetica, sans-serif" size="2">You must login before posting comments/adding games, <a href="login.php">click here</a> to get to the login page!</font><br><br>';

	comp_show_ID(intval($_GET['showID']));
}
echo '<br><br>';
if (isset($_GET['showID'])  && intval($_GET['showID']) > 0 AND (!isset($_GET['post_new']) || $_GET['post_new'] != 1))
{
	get_msg_threads(intval($_GET['showID']));


	if (isset($_GET['post_newMSG']) && $_GET['post_newMSG']==1)
	{
		echo '<br>';
		echo '<a name="post_comment">';
		write_comment();

		echo '<br>';
	}
echo '
<script type="text/javascript"><!--
google_ad_client = "pub-0841681205384106";
//games
google_ad_slot = "0465956936";
google_ad_width = 468;
google_ad_height = 60;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br><br>';

}

if (isset($_GET['changeID']) && $_GET['changeID'])
{
	$gameID = mysql_real_escape_string(intval(stripslashes($_GET['changeID'])));
	$query = mysql_query("SELECT ID, ownerID, name, publisher, version, released, moby_url FROM list_game WHERE ID = $gameID");

	$result = mysql_fetch_row($query);


	echo '<table width="730" cellspacing="0" cellpadding="1" bgcolor="#000000"><tr><td valign="top" align="left"><table cellspacing="4" cellpadding="0" width="100%" bgcolor="#355787"><tr><td>
	<table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Updating game-post</td></tr></table></td>
	</tr></table></td></tr></table><table width="730" cellspacing="0" cellpadding="1" bgcolor="#000000"><tr><td valign="top" align="left"><table cellspacing="4" cellpadding="0" width="100%" bgcolor="#113466"><tr>
	<td><table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';

	if ((isset($_SESSION['userID']) && check_game_owner($_GET['changeID'], $_SESSION['userID'])==1) || (isset($user) && $user['priv']['compat_list_manage']==1))
	{
		if ($_GET['error']==1)
		echo '<b>Error - this form must be filled in accurate!</b>';

		echo '<form action="comp_list.php?changing=1" method="POST">
		<input type="hidden" name="ID" value="'.$result[0].'">
		<input type="hidden" name="ownerID" value='.$result[1].'>
		<input type="hidden" name="letter" value="'.letter_check($_GET['letter']).'">
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Game title:
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Publisher/Developer:
		</td>

		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="name" size="38" maxlength="35" value="'.$result[2].'">
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="publisher" size="38" maxlength="20" value="'.$result[3].'">
		</td>

		</tr>
		<tr>
		<td colspan="3">
		&nbsp;
		</td>
		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Year released:
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<!--Moby-games link: -->
		</td>

		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="year" size="38" maxlength="4" value="'.$result[5].'">
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="hidden" name="moby" size="38" maxlength="65" value="'.$result[6].'">
		</td>

		</tr>
		<tr>
		<td colspan="3">
		&nbsp;
		</td>
		</tr>
		</table>
		<input type="submit" name="submit" value="Update post">
		<br><br><hr line width="650" color="#FFFFFF">
		</form><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
	}
	else
	{
		echo '<br>You are logged in as a "normal" user, and are not allowed to edit these settings, however.. you may still add/edit DOSBox version-support for this game.<br><br>click on the buttons to apply the new settings!<br><br>
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Game title:
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Publisher/Developer:
		</td>

		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="name" size="38" maxlength="35" value="'.$result[2].'" disabled>
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="publisher" size="38" maxlength="20" value="'.$result[3].'" disabled>
		</td>

		</tr>
		<tr>
		<td colspan="3">
		&nbsp;
		</td>
		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Year released:
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<!--Moby-games link: -->
		</td>

		</tr>
		<tr>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="text" name="year" size="38" maxlength="4" value="'.$result[5].'" disabled>
		</td>
		<td width="10">
		&nbsp;
		</td>
		<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<input type="hidden" name="moby" size="38" maxlength="65" value="'.$result[6].'">
		</td>

		</tr>
		<tr>
		<td colspan="3">
		&nbsp;
		</td>
		</tr>
		</table>
		<hr line width="650" color="#FFFFFF">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
	}



	change_version_compatibility_form($result[0]);

	echo '<hr line width="650" color="#FFFFFF"><br><form name="addversion" method="POST" action="comp_list.php?addVersion=1"><input type="hidden" name="gameID" value="'.intval($_GET['changeID']).'">';

	add_version_compatibility_form($result[0]);
	choose_percentage();
	echo '&nbsp;<input type="hidden" name="letter" value="'.letter_check($_GET['letter']).'"><input type="submit" value="Add version-support"><br><br>
	<br><a href="comp_list.php?showID='.intval($_GET['changeID']).'&letter='.letter_check($_GET['letter']).'">Click here to get back!<br></form>';


	echo '</td></tr></table></td></tr></table></td></tr></table><br>';
}

if (isset($_GET['post_new']) AND $_GET['post_new']==1 AND isset($_SESSION['userID']))
{
	echo '<table width="730" cellspacing="0" cellpadding="1" bgcolor="#000000"><tr><td valign="top" align="left"><table cellspacing="4" cellpadding="0" width="100%" bgcolor="#355787"><tr><td>
	<table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Adding new game</td></tr></table></td>
	</tr></table></td></tr></table><table width="730" cellspacing="0" cellpadding="1" bgcolor="#000000"><tr><td valign="top" align="left"><table cellspacing="4" cellpadding="0" width="100%" bgcolor="#113466"><tr>
	<td><table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';

	if ($_GET['error']==1)
	echo '<b>Error - this form must be filled in accurate!</b>';

        echo 'If the game is <b>already present</b> in the database.
	select that game and press the little <b>square</b> next to its name to add a new
	version or another comment';
	echo '<form action="comp_list.php?posting_new=1" method="POST">
	<input type="hidden" name="letter" value="'.letter_check($_GET['letter']).'">
	<table cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	Game title:
	</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	Publisher/Developer:
	</td>

	</tr>
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<input type="text" name="name" size="38" maxlength="35">
	</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<input type="text" name="publisher" size="38" maxlength="20">
	</td>

	</tr>
	<tr>
	<td colspan="3">
	&nbsp;
	</td>
	</tr>
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	Year released:
	</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<!--Moby-games link: -->
	</td>

	</tr>
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<input type="text" name="year" size="38" maxlength="4">
	</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<input type="hidden" name="moby" size="38" maxlength="20">
	</td>

	</tr>
	<tr>
	<td colspan="3">
	&nbsp;
	</td>
	</tr>
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	Running DOSBox version:
	</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	Compatibility: (specify DOSBox support in percent, 0-100%)
	</td>

	</tr>
	<tr>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
	get_versions();
	echo '</td>
	<td width="10">
	&nbsp;
	</td>
	<td valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
	choose_percentage();

	echo '</td>

	</tr>



	</table>
	<br>
	<font face="Verdana, Arial, Helvetica, sans-serif" size="2">


	Comment: (eg. what problems occurs when playing it in DOSBox)<br>
	<textarea name="note" cols="40" rows="6"></textarea>

	<br><br>
	<input type="submit" name="submit" value="Add game to database">
	</form>';


	echo '</td></tr></table></td></tr></table></td></tr></table><br>';
}



if (isset($_GET['search']))
search_results($_GET['search']);

if (isset($_GET['letter'])){
	if(/*$_GET['letter'] == 'a' && */ !isset($_GET['showID'])){
		echo '<div class="temp2">';
		echo '<a href="http://www.dosbox.com">DOSBox</a> does NOT host these games. This list is a compatibilitylist. If you are looking for games, you can visit  
<a href="http://www.classicdosgames.com" target=\'blank\'>www.classicdosgames.com</a> or 
 <a href="http://www.gog.com/en/frontpage/pp/b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f" class="goglink" target=\'_blank\'><span class="gog1">GOG</span><span class="gog2">.COM</span></a>.';
echo '</div></br>';
	}
	comp_mainlist(letter_check($_GET['letter']));
}


echo '<br>';
get_support_stats();




echo '</td></tr></table><br><br>';

template_end();

?>
