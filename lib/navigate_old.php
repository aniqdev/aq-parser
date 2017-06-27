<nav class="navigate">
	<ul>
        <li><a href="/index.php">Home</a></li>
        <li><a href="#">Plati.ru</a>
			<ul>
				<li><a href="?action=list">List</a></li>
				<li><a href="?action=table">Table</a></li>
				<li><a href="?action=table_changes">Changes</a></li>
				<li><a href="?action=orders-list">Orders&nbsp;list</a></li>
				<li><a href="?action=table_all&amp;lim=5">Full&nbsp;Table</a></li>
				<li><a href="?action=getjson">get&nbsp;JSON</a></li>
				<li><a href="?action=blackl">BlackList</a></li>
				<li><a href="?action=platiru_settings">Settings</a></li>
				<li><a href="?action=trustees">Trust list</a></li>
			</ul>
        </li>
        <li><a href="#">Ebay.com</a>
			<ul>
				<li><a href="?action=ebay-messages">Messages</a></li>
				<li><a href="?<?= query_to_orders_page();?>">Orders&nbsp;Page</a></li>
				<li><a href="?action=automatic-log&offset=0&limit=30">Automatic&nbsp;log</a></li>
<!-- 				<li><a href="?action=orders">Orders&nbsp;API</a></li>
				<li><a href="?action=orders-page-full">Orders&nbsp;Full</a></li> -->
				<li><a target="_blank" href="http://hot-body.net/panel/api/v1/counter">Counter</a></li>
				<li><a target="_blank" href="http://hot-body.net/panel/api/v1/update/gig-games">Update</a></li>
				<li><a href="?action=ebay_table">Table</a></li>
				<li><a href="?action=ebay_excel">Excel</a></li>
				<li><a href="?action=ebay_getprices">get&nbsp;Prices</a></li>
				<li><a href="?action=add-item">add&nbsp;Item</a></li>
			</ul>
        </li>
        <li><a href="#">Hood.de</a>
			<ul>
				<li><a href="?action=hood-sync">Hood import</a></li>
			</ul>
        </li>
        <li><a href="#">Parkovka</a>
			<ul>
				<li><a href="?action=park-table">Table</a></li>
				<li><a href="?action=park-table-airport">Chart</a></li>
			</ul>
        </li>
        <li><a href="#">Steam full</a>
			<ul>
				<li><a href="?action=steam">get&nbsp;Steam</a></li>
				<li><a href="?action=steam-list&offset=0&limit=300">Steam&nbsp;list</a></li>
				<li><a href="?action=getjson-steam-page">getjson&nbsp;Steam</a></li>
			</ul>
        </li>
        <li><a href="#">Steam lite</a>
			<ul>
				<li><a href="?action=slist">get List</a></li>
				<li><a href="?action=slistshow">show&nbsp;List</a></li>
			</ul>
        </li>
        <li><a href="?action=sql">SQL</a></li>
        <li><a href="#">Other</a>
			<ul>
		        <li><a href="?action=csv">Скачать&nbsp;Exel</a></li>
		        <li><a href="?action=search-charts">Search</a></li>
		        <li><a href="?action=exporter">exporter</a></li>
        		<li><a href="?action=imagine">Picture</a></li>
			</ul>
        </li>
        <?= devzone_link(); ?>
	</ul>
	<a href="?logout=true" class="logout-nav" title="logout <?php echo $_SESSION['username'];?>">×</a>
	<h2 class="ppp-title">Plati.ru Product Parser</h2>
</nav>