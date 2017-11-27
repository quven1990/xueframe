<?php include 'app/home/view/retwis/header.php';?>
<h2 class="username"><?php echo $this->_vars['username']; ?></h2>

<?php if($this->_vars['follow_status']){ ?>
<a href="/home/retwis/unFollow/user_id/<?php echo $this->_vars['user_id']; ?>" class="button">取消关注</a>  
<?php }else{ ?>
<a href="/home/retwis/follow/user_id/<?php echo $this->_vars['user_id']; ?>" class="button">关注ta</a>  
<?php } ?>   

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
<?php include 'app/home/view/retwis/footer.php';?>
