{include "app/home/view/retwis/header.php"}
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
    {foreach $user_list(key,value)}
    <a class="username" href="profile/user_id/{@key}">{@value}</a>
    {/foreach}
</div>

<br><i>最新的50条微博!</i><br>
{foreach $post_list(key,value)}
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/{@value['user_id']}">{@value['username']}</a>{@value['content']}<br>
<i>{@value['time']}前 通过 web发布</i>
</div>
{/foreach}
{include "app/home/view/retwis/footer.php"}