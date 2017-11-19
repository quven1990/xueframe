<?php include 'app/home/view/retwis/header.php';?>
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
    <?php foreach($this->_vars['user_list'] as $key=>$value){?>
    <a class="username" href="profile.php?u=test"><?php echo $value?></a>
    <?php } ?>
</div>

<br><i>最新的50条微博!</i><br>
<div class="post">
<a class="username" href="profile.php?u=test">test</a>
world<br>
<i>22 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
<?php include 'app/home/view/retwis/footer.php';?>