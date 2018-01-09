{include "app/home/view/retwis/header.php"}
<h2 class="username">{$username}</h2>

{if $follow_status}
<a href="/home/retwis/unFollow/user_id/{$user_id}" class="button">取消关注</a>  
{else}
<a href="/home/retwis/follow/user_id/{$user_id}" class="button">关注ta</a>  
{/if}   

{foreach $content_list(key,value)}
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/{@value['user_id']}">{@value['username']}</a>{@value['content']}<br>
<i>{@value['time']}前 通过 web发布</i>
</div>
{/foreach}
{include "app/home/view/retwis/footer.php"}
