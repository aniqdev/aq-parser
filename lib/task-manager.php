<link rel="stylesheet" href="css/task-manager.css?t=<?= filemtime('css/task-manager.css'); ?>">

<h2 id="h-page" title="Main page">Task Manager</h2>

<div class="container" id="task_manager_root">
	<div id="app_here">Loading...</div>
</div>


<!-- Load React. -->
<!-- Note: when deploying, replace "development.js" with "production.min.js". -->
<!-- 16 -->
<script src="js/react.production.min.js"></script>
<script src="js/react-dom.production.min.js"></script>
<script src="js/babel.min.js"></script>
<!-- 15 -->
<!-- <script src="js/react.min.js"></script>
<script src="js/react-dom.min.js"></script>
<script src="js/babel-core.min.js"></script> -->

<script src="https://momentjs.com/downloads/moment.js"></script>

<!-- Load our React component. -->
<script src="js/task-manager.jsx?t=<?= filemtime('js/task-manager.jsx'); ?>" type="text/babel"></script>