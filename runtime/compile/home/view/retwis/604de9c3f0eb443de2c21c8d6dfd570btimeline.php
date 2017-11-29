<?php include 'app/home/view/retwis/header.php';?>
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
    <?php foreach($this->_vars['user_list'] as $key=>$value){?>
    <a class="username" href="profile/user_id/<?php echo $key?>"><?php echo $value?></a>
    <?php } ?>
</div>

<br><i>最新的50条微博!</i><br>
<?php foreach($this->_vars['post_list'] as $key=>$value){?>
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/<?php echo $value['user_id']?>"><?php echo $value['username']?></a><?php echo $value['content']?><br>
<i><?php echo $value['time']?>前 通过 web发布</i>
</div>
<?php } ?>
<?php include 'app/home/view/retwis/footer.php';?>