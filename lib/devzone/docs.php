<table class="table">
<tr>
	<th>script</th>
	<th>time</th>
	<th>description</th>
</tr>
<tr>
	<td>http://parser.modetoday.de/a.php?action=devzone/cron-moda-update
 	<td>*/1 * * * *
 	<td>парсит категорию "МОДА" ebay и обновляет/добавляет данные в базе modetoday.de
</tr>
<tr>
	<td>http://parser.zeckenmittelhund.de/a.php?action=devzone/cron-hund-update
	<td>*/10 * * * *
 	<td>парсит категорию "HUND" ebay и обновляет/добавляет данные в базе zeckenmittelhund.de
</tr>
<tr> 
	<td>http://marser.gig-games.de/a.php?action=devzone/cron-b2b-cdvet-auth	 
	<td>*/12 * * * *
	<td>скрипт делает запрос на b2b.cdvet.de с id сессии каждые 12 минут чтоб продлить сессию,
		а скрипт "cdvet-parser.gig-games.de/b2b/index.php" использует тотже id сессии для получения доступа и парсинга данных
</tr>
<tr>
	 <td>https://hundefutteruvm.de/cdvet/cdvet-sync.php	 
	 <td>*/25 * * * *
	 <td>Синхронизирует цены и наличе товаров <i>hundefutteruvm.de</i> с фидом 
	 	<br><b>не актуален</b> так как в старом фиде не правильные цены
</tr>
<tr>
	 <td>http://marser.modetoday.de/a.php?action=devzone/cron-moda-bing	 
	 <td>* */2 * * *
	 <td>заливает товары с "<i>modetoday.de</i>" в поисковик bing
</tr>
<tr>
	 <td>http://marser.gig-games.de/ajax.php?action=cron-cdvet-quantity-updater 
	 <td>3-53/10 0-2,4-23 * * *
	 <td>обновляет наличие товаров на ebay из фида <u>http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129</u>
</tr>
<tr>	
	 <td>http://parser.modetoday.de/a.php?action=devzone/cron-create-moda-file
 	 <td>Ежечасно (в 20 мин)
 	 <td>создает файл "<b>moda-arr.txt</b>"
</tr>
<tr>
	 <td>http://modetoday.de/moda-remote.php
 	 <td>Ежечасно (в 40 мин)
 	 <td>удаляет истекшие посты(товары) с <i>modetoday.de</i>
</tr>
<tr>
	 <td>/var/www/vhosts/gig-games.de/cdvet-parser.gig-games.de/index.php	 
	 <td>Ежедневно (01:00)
	 <td>парсит <i>www.cdvet.de</i> сохраняет данные в <b>http://cdvet-parser.gig-games.de/input.json</b>
</tr>
<tr>
	 <td>/var/www/vhosts/gig-games.de/cdvet-parser.gig-games.de/b2b/index.php
     <td>Ежедневно (03:00)
	 <td>парсит <i>b2b.cdvet.de</i> сохраняет данные в <b>http://cdvet-parser.gig-games.de/b2b/input.json</b>
</tr>
<tr>
	 <td>http://modetoday.de/moda-remote.php?action=clean-fashion
 	 <td>Ежедневно (03:05)
</tr>
<tr>
	 <td>https://marser.gig-games.de/a.php?action=cron-filter-tops
 	 <td>Ежедневно (03:10)
</tr>
<tr>
	 <td>/opt/plesk/php/7.3.26/bin/php /var/www/vhosts/gig-games.de/cdvet-parser.gig-games.de/index.php
	 <td>Ежедневно (03:15)	
</tr>
<tr>
	 <td>http://marser.gig-games.de/ajax.php?action=cron-cdvet-price-checker
 	 <td>Ежедневно (03:20)
</tr>
<tr>
	 <td>http://marser.gig-games.de/ajax.php?action=cron-cdvet-feed-updater
 	 <td>Ежедневно (03:35)
</tr>
<tr>
	 <td>https://marser.gig-games.de/a.php?action=cron-hundefutter-changes
	 <td>Ежедневно (05:00)
</tr>
</table>