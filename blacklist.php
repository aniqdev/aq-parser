<!-- PMA-SQL-ERROR -->
<meta charset="utf-8">
    <div class="error"><h1>Ошибка</h1>
<p><strong>SQL запрос:</strong>
<a href="./url.php?url=http%3A%2F%2Fdev.mysql.com%2Fdoc%2Frefman%2F5.5%2Fen%2Fselect.html" target="mysql_doc"><img src="themes/dot.gif" title="Документация" alt="Документация" class="icon ic_b_help" /></a><a href="tbl_sql.php?sql_query=%0D%0ASELECT+new.name%2C+new.item1_price+as+newPrice%2C+old1.item1_price+as+Price2%2C+old2.item1_price+as+Price3%2C+old3.item1_price+as+Price4%2C+old4.item1_price+as+Price5%0D%0A%09%09%09%09%09FROM+%28SELECT+games.name%2C++items.game_id+as+newid%2C+items.item1_price%0D%0A%09%09%09%09%09%09%09%09%09%09%09FROM+games+INNER+JOIN+items+ON+games.id%3Ditems.game_id%0D%0A%09%09%09%09%09%09%09%09%09%09%09WHERE+items.scan%3D%27e1f4596a3d4764a705876a3415990b9d%27%29+as+new%0D%0A%09%09%09%09%09INNER+JOIN%0D%0A%09%09%09%09%09%28SELECT+items.game_id+as+oldid1%2C+items.item1_price%0D%0A%09%09%09%09%09%09%09%09%09%09%09FROM+items%0D%0A%09%09%09%09%09%09%09%09%09%09%09WHERE+items.scan%3D%2793a8ec6ac52a9190341bf19c2457cf5f%27%29+as+old1%0D%0A%09%09%09%09%09ON+new.newid+%3D+old1.oldid1%0D%0A%0D%0A%09%09%09%09%09INNER+JOIN%0D%0A%09%09%09%09%09%28SELECT+items.game_id+as+oldid2%2C+items.item1_price%0D%0A%09%09%09%09%09%09%09%09%09%09%09FROM+items%0D%0A%09%09%09%09%09%09%09%09%09%09%09WHERE+items.scan%3D%270db6adac4593b916fa4921ea5f5435b5%27%29+as+old2%0D%0A%09%09%09%09%09ON+new.newid+%3D+old2.oldid2%0D%0A%0D%0A%09%09%09%09%09INNER+JOIN%0D%0A%09%09%09%09%09%28SELECT+items.game_id+as+oldid3%2C+items.item1_price%0D%0A%09%09%09%09%09%09%09%09%09%09%09FROM+items%0D%0A%09%09%09%09%09%09%09%09%09%09%09WHERE+items.scan%3D%270b4508c8dabeaf4493c604f648e17ee7%27%29+as+old3%0D%0A%09%09%09%09%09ON+new.newid+%3D+old3.oldid3%0D%0A%0D%0A%09%09%09%09%09INNER+JOIN%0D%0A%09%09%09%09%09%28SELECT+items.game_id+as+oldid4%2C+items.item1_price%0D%0A%09%09%09%09%09%09%09%09%09%09%09FROM+items%0D%0A%09%09%09%09%09%09%09%09%09%09%09WHERE+items.scan%3D%27777ee29035cf1cda6674e5b19d492234%27%29+as+old4%0D%0A%09%09%09%09%09ON+new.newid+%3D+old4.oldid4&amp;show_query=1&amp;db=DB2213198&amp;table=blacklist&amp;token=b0a3e0b6fc1a86e3695510a20162a23b"><span class="nowrap"><img src="themes/dot.gif" title="Изменить" alt="Изменить" class="icon ic_b_edit" /> Изменить</span></a>    </p>
<p>
<code class="sql"><pre>

SELECT new.name, new.item1_price as newPrice, old1.item1_price as Price2, old2.item1_price as Price3, old3.item1_price as Price4, old4.item1_price as Price5
					FROM (SELECT games.name,  items.game_id as newid, items.item1_price
											FROM games INNER JOIN items ON games.id=items.game_id
											WHERE items.scan='e1f4596a3d4764a705876a3415990b9d') as new
					INNER JOIN
					(SELECT items.game_id as oldid1, items.item1_price
											FROM items
											WHERE items.scan='93a8ec6ac52a9190341bf19c2457cf5f') as old1
					ON new.newid = old1.oldid1

					INNER JOIN
					(SELECT items.game_id as oldid2, items.item1_price
											FROM items
											WHERE items.scan='0db6adac4593b916fa4921ea5f5435b5') as old2
					ON new.newid = old2.oldid2

					INNER JOIN
					(SELECT items.game_id as oldid3, items.item1_price
											FROM items
											WHERE items.scan='0b4508c8dabeaf4493c604f648e17ee7') as old3
					ON new.newid = old3.oldid3

					INNER JO[...]
</pre></code>
</p>
<p>
    <strong>Ответ MySQL: </strong><a href="./url.php?url=http%3A%2F%2Fdev.mysql.com%2Fdoc%2Frefman%2F5.5%2Fen%2Ferror-messages-server.html" target="mysql_doc"><img src="themes/dot.gif" title="Документация" alt="Документация" class="icon ic_b_help" /></a>
</p>
<code>
#1104 - The SELECT would examine more than MAX_JOIN_SIZE rows; check your WHERE and use SET SQL_BIG_SELECTS=1 or SET MAX_JOIN_SIZE=# if the SELECT is okay
</code><br />
</div>