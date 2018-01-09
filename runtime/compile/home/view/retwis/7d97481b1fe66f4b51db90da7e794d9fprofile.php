<?php include 'app/home/view/retwis/header.php';?>
<h2 class="username"><?php echo $this->_vars['username']; ?></h2>

<?php if($this->_vars['follow_status']){ ?>
<a href="/home/retwis/unFollow/user_id/<?php echo $this->_vars['user_id']; ?>" class="button">取消关注</a>  
<?php }else{ ?>
<a href="/home/retwis/follow/user_id/<?php echo $this->_vars['user_id']; ?>" class="button">关注ta</a>  
<?php } ?>   

<?php foreach($this->_vars['content_list'] as $key=>$value){?>
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/<?php echo $value['user_id']?>"><?php echo $value['username']?></a><?php echo $value['content']?><br>
<i><?php echo $value['time']?>前 通过 web发布</i>
</div>
<?php } ?>
<?php include 'app/home/view/retwis/footer.php';?>
