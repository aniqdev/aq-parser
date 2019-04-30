<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/index.php">Home</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Plati.ru <span class="caret"></span></a>
          <ul class="dropdown-menu">
      			<li><a href="?action=list">List</a></li>
      			<li><a href="?action=table">Table</a></li>
      			<li><a href="?action=table_changes&tab=s">Changes</a></li>
      			<li><a href="?action=orders-list">Orders&nbsp;list</a></li>
      			<!-- <li><a href="?action=table_all&amp;lim=5">Full&nbsp;Table</a></li> -->
      			<li><a href="?action=getjson">get&nbsp;JSON</a></li>
      			<li><a href="?action=blackl">BlackList</a></li>
      			<li><a href="?action=platiru_settings">Settings</a></li>
      			<li><a href="?action=trustees">Trust list</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">eBay.com <i class="caret"></i></a>
          <?= draw_unread();?>
          <ul class="dropdown-menu">
            <li><a href="?action=ebay-messages">Messages</a></li>
            <li><a href="?<?= query_to_orders_page();?>">Orders&nbsp;Page</a></li>
            <li><a href="?action=awaiting-list-clear">Bonus&nbsp;list</a></li>
            <li><a href="?action=automatic-log&offset=0&limit=30">Automatic&nbsp;log</a></li>
      <!--    <li><a href="?action=orders">Orders&nbsp;API</a></li>
            <li><a href="?action=orders-page-full">Orders&nbsp;Full</a></li> -->
            <li><a target="_blank" href="http://hot-body.net/panel/api/v1/counter">Counter</a></li>
            <li><a target="_blank" href="http://hot-body.net/panel/api/v1/update/gig-games">Update</a></li>
            <li><a href="?action=ebay_table">Table</a></li>
            <!-- <li><a href="?action=order-messages">Order messages</a></li> -->
            <li><a href="?action=text-templates">Text templates</a></li>
            <!-- <li><a href="?action=ebay_excel">Excel</a></li> -->
            <li><a href="?action=ebay_getprices">get&nbsp;Prices</a></li>
            <li><a href="?action=ebay-panels">Top Sales</a></li>
            <li><a href="?action=price-upper">Price upper</a></li>
            <li><a href="?action=white-list">White list</a></li>
            <li><a href="?action=game-editor">Game editor</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hood.de <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=hood-sync">Hood import</a></li>
            <li><a href="?action=hood-orders&offset=0&limit=100">Hood orders</a></li>
            <li><a href="?action=hood-messages&show=all">Hood messages</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="http://hood.gig-games.de">Hood update</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">cdVet <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=cdvet-feed">Cdvet feed</a></li>
            <li><a href="?action=devzone/jpegs">jpegs</a></li>
            <li><a href="?action=add-cdvet-february">Add products</a></li>
            <li><a href="?action=cdvet-continues">skipped products</a></li>
            <li><a href="?action=cdvet-list">Item list</a></li>
            <li><a href="?action=cdvet-checker-report">Checker report</a></li>
            <li><a href="/a.php?action=cdvet-feed-report">Feed report</a></li>
            <li><a href="?action=cdvet-categories">Categories</a></li>
            <li><a href="?action=cdvet-filter-log">Filter log</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Parkovka <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=park-table">Table</a></li>
            <li><a href="?action=park-table-airport">Chart</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Keys<i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=ak-add-key">add keys</a></li>
            <li><a href="?action=ak-keys">keys list</a></li>
            <li><a href="?action=ak-sellers">sellers</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Steam<i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=steam">get&nbsp;Steam</a></li>
            <li><a href="?action=steam-list&offset=0&limit=300">Steam&nbsp;list</a></li>
            <li><a href="?action=getjson-steam-page">getjson&nbsp;Steam</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="?action=slist">get List</a></li>
            <li><a href="?action=slistshow">show&nbsp;List</a></li>
          </ul>
        </li>

        <?= dz('<li><a href="?action=sql">SQL</a></li>');?>
        
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Other <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="?action=csv">Скачать&nbsp;Exel</a></li>
            <li><a href="?action=search-charts">Search</a></li>
            <li><a href="?action=exporter">exporter</a></li>
            <li><a href="?action=imagine">Picture</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filter <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="http://info-rim.ru/filter/">product-filter</a></li>
            <li><a href="?action=filter-log">filter log</a></li>
            <li><a href="?action=filter-langs">filter langs (ebay)</a></li>
            <li><a href="?action=filter-langs&for=gig-games">filter langs (gig-games)</a></li>
            <li><a href="?action=cron-filter-price-updater">cron-filter-price-updater</a></li>
            <li><a href="?action=cron-filter-price-updater-multi">cron-filter-price-updater-multi</a></li>
            <li><a href="?action=cron-filter-advantages-updater">cron-filter-advantages-updater</a></li>
            <li><a href="?action=cron-filter-values-updater">cron-filter-values-updater</a></li>
            <li><a href="?action=cron-filter-tops">cron-filter-tops</a></li>
          </ul>
        </li>

        <?= devzone_link(); ?>
      </ul>

	<a href="?logout=true" class="logout-nav2" title="logout <?php echo $_SESSION['username'];?>">×</a>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>