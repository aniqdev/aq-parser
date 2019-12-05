<pre>
	{$smarty.server.REQUEST_SCHEME}://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}
</pre>
<pre>
	{date('Y-m-d', time()+2592000)}
</pre>
<pre>
	{$smarty.server|@print_r}
</pre>