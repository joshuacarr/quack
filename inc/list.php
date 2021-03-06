<?php include("inc/header.php"); ?>

<?php
/*
 * Update
 */


if(strpos('/', $_GET['page'] !== false)){
    $controller = explode('/', $_GET['page']);
    
    if(file_exists('app/classes/' . $controller[0] . 'Handler.php')){
        require_once 'app/classes/'. $controller[0] . 'Handler.php';
    }
}
?>

    <?php if ($page == "register"){ include("inc/register.php"); }
    elseif ($page == "login") { include("inc/login.php"); } 
    elseif ($page == "usercp") { include("inc/members.php"); } 
    elseif ($page == "logout"){ include("inc/logout.php"); } 
    elseif ($page == "error"){ include("inc/errors.php"); } 
    elseif ($page == "registered"){ include("inc/registered.php"); }
    elseif ($page == "addthread") { include("inc/newthread.php"); } 
    elseif ($page == "editaccount"){ include("inc/editprofile.php"); }
    elseif ($page == "profile"){ include("inc/profile.php"); }
    elseif ($page == "thread"){ include("inc/viewthread.php"); }
    elseif ($page == "news"){ include("inc/news.php"); }
    else {  ?>

    <?php if (($bbsetting[tinybb_guest_access] == "0") && ($user[username] == null)){ echo "<center><span style='color:red; font-weight:bold;'>Guests are not allowed to browse $bbtitle</span></center>"; include("login.php"); } else { ?>
    <?php if (($bbsetting[tinybb_maintenance] == "0") && (!$user[admin] == "1")){ echo "<table id='forum'><th style=\"background-image:url('style/thread_header.png')\">Forum Maintenance</th><tr><td>$bbsetting[tinybb_maintenance_message]</td></tr></table>"; } else { ?>

<style>
.paginate {
font-family:Arial, Helvetica, sans-serif;
	padding: 3px;
	margin: 3px;
}

.paginate a {
	padding:2px 5px 2px 5px;
	border:1px solid #999;
	text-decoration:none;
	color: #000;
}
.paginate a:hover, .paginate a:active {
	border: 1px solid #999;
	color: #000;
}
.paginate span.current {
	padding: 2px 5px 2px 5px;
		border: 1px solid #999;
		
		font-weight: bold;
		background-color: #999;
		color: #000;
	}
	.paginate span.disabled {
		padding:2px 5px 2px 5px;
		margin:2px;
		border:1px solid #eee;
		color:#fff;
	}

	ul{margin:6px;
	padding:0px;}	

</style>
<?php
        // PAGINATION SCRIPT CREDITS
        // http://papermashup.com/easy-php-pagination/
	$tableName="tinybb_threads";
	$targetpage = "index.php";
	$limit = 20;
	
	$query = "SELECT COUNT(*) as num FROM $tableName";
	$total_pages = mysqli_fetch_array(mysqli_query($conn,$query));
	$total_pages = $total_pages[num];
	
	$stages = 3;
	$page = mysqli_real_escape_string($conn,$_GET['page']);
	if($page){
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
	
    // Get page data
	$query1 = "SELECT * FROM $tableName ORDER BY aid DESC LIMIT $start, $limit ";
	$result = mysqli_query($conn,$query1);
	
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	
	$paginate = '';
	if($lastpage > 1)
	{	
	

	
	
		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a href='$targetpage&page=$prev'>previous</a>";
		}else{
			$paginate.= "";	}
			

		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a href='$targetpage&page=$counter'>$counter</a>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage&page=$counter'>$counter</a>";}
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$targetpage&page=1'>1</a>";
				$paginate.= "<a href='$targetpage&page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage&page=$counter'>$counter</a>";}
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage&page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage&page=$lastpage'>$lastpage</a>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a href='$targetpage&page=1'>1</a>";
				$paginate.= "<a href='$targetpage&page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage&page=$counter'>$counter</a>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<a href='$targetpage&page=$next'>next</a>";
		}else{
			$paginate.= "";
			}
			
		$paginate.= "</div>";		
	
	
}echo "";
 // pagination
 echo $paginate;
?>
<table id="forum">
<th style="background-image:url('style/thread_header.png');"></th>
<th style="background-image:url('style/thread_header.png');"><center>Thread Title</center></th>
<th style="background-image:url('style/thread_header.png');"><center>Thread Author</center></th>
<th style="background-image:url('style/thread_header.png');"><center>Last Reply</center></th>
<th style="background-image:url('style/thread_header.png');"></th>
<?php
 while($row = @mysqli_fetch_array($result))
		{
                  ?>
                            
                            <tr>
                            <td align="center"><?php if ($row[thread_lock] == "1"){ echo "<img src='icons/lock.gif' border='0'>"; } else { ?><img src='icons/none.gif' border='0'><?php } ?></td>
                            <td align="center"><a href="?page=thread&post=<?php echo "$row[thread_key]"; ?>"><?php echo "$row[thread_title]"; ?></a></td>
                            <td align="center"><a href="?page=profile&id=<?php echo "$row[thread_author]"; ?>"><?php echo "$row[thread_author]"; ?></a></td>
                            <td align="center">
                            
 <?php
$sql2 = "SELECT * FROM tinybb_replies WHERE thread_key = '$row[thread_key]' ORDER BY aid DESC LIMIT 1";
$tt = mysqli_query($conn,$sql2) or die (mysqli_error());
while($p=mysqli_fetch_assoc($tt)){
  if ($p['reply_key'] == 0){ echo "No Replies"; } 
echo "<a href=\"?page=profile&id=".$p['reply_author']."\">".$p['reply_author']."</a>";
}
?>
                            
                            
                            </td>
                            <td align="center">
                            <?php
                            $result4 = mysqli_query($conn,"SELECT * FROM tinybb_replies WHERE thread_key = '$row[thread_key]'");
                            $treplies = mysqli_num_rows($result4);
                            echo "<strong>$treplies</strong> replies";
                            ?>
                            </td>
                            </tr>

                  <?php } ?>
                  </table>
<center>
<br /><br />
<table id="forum">
<th style="background-image:url('style/thread_header.png');">Statistics</th>
<tr><td>
<span style="font-size:15px; font-weight:bold;">Who's Online</span><br />
<?php
include("inc/online.php"); //get online configuration and such
$get_online_users = mysqli_query($conn,"SELECT * FROM `members` WHERE `online` >= '$offline' ORDER BY `id` ASC"); //get all online users
$total_users = mysqli_num_rows($get_online_users); 
if($total_users == 0){ //see if anyone is logged in 
    echo "There are no users online."; //there isn't =O 
}else{ //maybe.... 
    $i = 1; //the variable 'i' is 1
    while($online = mysqli_fetch_array($get_online_users)){ //loop online users 
        if(($i > 0) && ($i != $total_users)){ //see if i is the same or not of total online users 
            $comma = ', '; //if it isn't then theres a comma 
        }else{ //or.... 
            $comma = ''; //if there isn't theres no comma 
        } //end check 
        if ($online[admin] == 1){ 
          echo "<a href='index.php?page=profile&id=$online[username]'><span style='color:red'>$online[username]</span></a>$comma"; //echo the online users with the comma
        }
        elseif ($online[admin] == "mod"){
          echo "<a href='index.php?page=profile&id=$online[username]'><span style='color:green'>$online[username]</span></a>$comma"; //echo the online users with the comma
        }
        else {
        echo "<a href='index.php?page=profile&id=$online[username]'>$online[username]</a>$comma"; //echo the online users with the comma
        }
    } //end loop 
} //end 
?>
<br /><br />
<?php if ($bbsetting[tinybb_stats] == "1"){ ?>
<span style="font-size:15px; font-weight:bold;">Posts/Threads</span><br />
<span style="font-size:14px;"><?php echo "$stats"; ?></span><br /><br />
<?php
$sql = "SELECT * FROM members ORDER BY id DESC LIMIT 1";
$res = mysqli_query($conn,$sql) or die (mysqli_error());
while($r=mysqli_fetch_assoc($res)){
echo "Welcome to our latest member, <strong><a href=\"?page=profile&id=".$r['username']."\">".$r['username']."</a></strong>!<br>";
}
?>
<?php } ?>
</center>
</tr></td>
</table>
                  <?php } } } ?>
                  <br /><br />







