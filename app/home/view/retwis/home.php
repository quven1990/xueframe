{include "app/home/view/retwis/header.php"}
<div id="postform">
<form method="POST" action="/home/retwis/publish">
{$username}, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="content"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
{$follower_count}  粉丝<br>
{$follow_count} 关注<br>
</div>
</div>
{foreach $content_list(key,value)}
<div class="post">
<a class="username" href="/home/retwis/profile/user_id/{@value['user_id']}">{@value['username']}</a>{@value['content']}<br>
<i>{@value['time']}前 通过 web发布</i>
</div>
{/foreach}
{include "app/home/view/retwis/footer.php"}
