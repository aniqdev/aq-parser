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
			<li><a href="?action=table_changes&tab=2">Changes</a></li>
			<li><a href="?action=orders-list">Orders&nbsp;list</a></li>
			<li><a href="?action=table_all&amp;lim=5">Full&nbsp;Table</a></li>
			<li><a href="?action=getjson">get&nbsp;JSON</a></li>
			<li><a href="?action=blackl">BlackList</a></li>
			<li><a href="?action=platiru_settings">Settings</a></li>
			<li><a href="?action=trustees">Trust list</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">eBay.com <i class="caret"></i></a>
          <ul class="dropdown-menu">
      <li><a href="?action=ebay-messages">Messages</a></li>
      <li><a href="?<?= query_to_orders_page();?>">Orders&nbsp;Page</a></li>
      <li><a href="?action=automatic-log&offset=0&limit=30">Automatic&nbsp;log</a></li>
<!--    <li><a href="?action=orders">Orders&nbsp;API</a></li>
      <li><a href="?action=orders-page-full">Orders&nbsp;Full</a></li> -->
      <li><a target="_blank" href="http://hot-body.net/panel/api/v1/counter">Counter</a></li>
      <li><a target="_blank" href="http://hot-body.net/panel/api/v1/update/gig-games">Update</a></li>
      <li><a href="?action=ebay_table">Table</a></li>
      <li><a href="?action=ebay_excel">Excel</a></li>
      <li><a href="?action=ebay_getprices">get&nbsp;Prices</a></li>
      <li><a href="?action=add-item">add&nbsp;Item</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hood.de <i class="caret"></i></a>
          <ul class="dropdown-menu">
      <li><a href="?action=hood-sync">Hood import</a></li>
          </ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Parkovka <i class="caret"></i></a>
			<ul class="dropdown-menu">
				<li><a href="?action=park-table">Table</a></li>
				<li><a href="?action=park-table-airport">Chart</a></li>
			</ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Steam full <i class="caret"></i></a>
			<ul class="dropdown-menu">
				<li><a href="?action=steam">get&nbsp;Steam</a></li>
				<li><a href="?action=steam-list&offset=0&limit=300">Steam&nbsp;list</a></li>
				<li><a href="?action=getjson-steam-page">getjson&nbsp;Steam</a></li>
			</ul>
        </li>

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Steam lite <i class="caret"></i></a>
			<ul class="dropdown-menu">
				<li><a href="?action=slist">get List</a></li>
				<li><a href="?action=slistshow">show&nbsp;List</a></li>
			</ul>
        </li>

        <li><a href="?action=sql">SQL</a></li>
        
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Other <i class="caret"></i></a>
			<ul class="dropdown-menu">
		        <li><a href="?action=csv">Скачать&nbsp;Exel</a></li>
		        <li><a href="?action=search-charts">Search</a></li>
		        <li><a href="?action=exporter">exporter</a></li>
        		<li><a href="?action=imagine">Picture</a></li>
			</ul>
        </li>
        <?= devzone_link(); ?>
      </ul>

<!--       <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form> -->

<!--       <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul> -->

	<a href="?logout=true" class="logout-nav2" title="logout <?php echo $_SESSION['username'];?>">×</a>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>