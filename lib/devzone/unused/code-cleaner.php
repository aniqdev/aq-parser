<?php
require_once __DIR__.'/simple_html_dom.php';
?>
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">text input</label>
    <textarea name="text" class="form-control" id="exampleInputEmail1" placeholder="text" style="height:300px"><?php 
      if(isset($_POST['text'])) echo $_POST['text']
      ?></textarea>
  </div>
<!--   <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox"> Check me out
    </label>
  </div> -->
  <button type="submit" class="btn btn-default" name="xmlclear">убрать XML</button>
  <button type="submit" class="btn btn-default" name="totalclear">полная очистка</button>
</form>
<br>
<div class="form-group">
  <label for="exampleInputEmail1">text output</label>
  <textarea name="text" class="form-control" id="exampleInputEmail1" placeholder="text" style="min-height:300px">
<?php

if (isset($_POST['xmlclear'])) {
  $output = preg_replace('/<!--.*?-->/ms', ' ', $_POST['text']);
  echo htmlspecialchars($output);
}
if (isset($_POST['totalclear'])) {
  $output = preg_replace('/<!--.*?-->/ms', ' ', $_POST['text']);
  $html = str_get_html($output);
  $clear = $html->plaintext;
  echo htmlspecialchars($clear);
}
?>
  
  </textarea>
</div>
<br>
<pre>
<?php

if (isset($_POST['xmlclear'])) {
  echo $output;
}
if (isset($_POST['totalclear'])) {
  echo $clear;
}

?>
</pre>
<br>


