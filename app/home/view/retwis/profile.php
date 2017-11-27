{include "app/home/view/retwis/header.php"}
<h2 class="username">{$username}</h2>

{if $follow_status}
<a href="/home/retwis/unFollow/user_id/{$user_id}" class="button">取消关注</a>  
{else}
<a href="/home/retwis/follow/user_id/{$user_id}" class="button">关注ta</a>  
{/if}   

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
{include "app/home/view/retwis/footer.php"}
