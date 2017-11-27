<?php include 'app/home/view/retwis/header.php';?>
<div id="postform">
<form method="POST" action="/home/retwis/publish">
<?php echo $this->_vars['username']; ?>, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="content"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $this->_vars['follower_count']; ?>  粉丝<br>
<?php echo $this->_vars['follow_count']; ?> 关注<br>
</div>
</div>
<?php foreach($this->_vars['content_list'] as $key=>$value){?>
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/<?php echo $value['user_id']?>"><?php echo $value['username']?></a><?php echo $value['content']?><br>
<i><?php echo $value['time']?>前 通过 web发布</i>
</div>
<?php } ?>
<?php include 'app/home/view/retwis/footer.php';?>
