(function(d, log, c, aEL) {

  // список допустимых страниц
  var pages = [
    'https://www.cdvet.de/detail/index/sArticle/4915',
  ];

  // проверка
  if(pages.indexOf(location.origin+location.pathname) === -1) return;

  // подгрузка css
  (function(i,s,o,g,l,a,m){m=s.getElementsByTagName(o)[0],a=s.createElement(l);
    a.href=g+'css'+(location.hash.replace('#','?'));a.rel='stylesheet';m.parentNode.insertBefore(a,m);
  })(window,document,'script','https://hot-body.net/cdvet/modal/cdvet-modal.','link');

  // кнопка вызова модального окна
  var button = '<button type="button" id="aqs_modal_open_btn">Show info</button>';

  // заголовок модального окна
  var modal_title = 'Modal title!';

  // текст модального окна
  var modal_text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia nesciunt repudiandae, dolorum perspiciatis magni rem earum ipsa repellendus, unde dolor placeat ullam saepe incidunt recusandae impedit quos harum! Ab, ipsum.'; 

  var modal_html = 
  '<div class="modal fade" id="aqs_modal_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">'+
  '  <div class="modal-dialog" role="document" id="aqs_modal_dialog">'+
  '    <div class="modal-content">'+
  '      <div class="modal-header">'+
  '        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="aqs_modal_close_btn">×</button>'+
  '        <h4 class="modal-title" id="myModalLabel">'+modal_title+'</h4>'+
  '      </div>'+
  '      <div class="modal-body">'+modal_text+
  '      </div>'+
  '    </div>'+
  '  </div>'+
  '</div>';

  var e = d.createElement('div');
  e.innerHTML = modal_html;
  e.className = 'aqs-cdvet-modal-parent';
  d.body.appendChild(e);

  ael_poli('aqs_modal_open_btn', c, aqs_modal_show)
  ael_poli('aqs_modal_close_btn', c, aqs_modal_hide)
  ael_poli('aqs_modal_wrapper', c, aqs_modal_hide)
  ael_poli('aqs_modal_dialog', c, function(e) { e.stopPropagation() })

  setTimeout(aqs_modal_show, 1000)

  function ael_poli(el_id, action, callback) {
    if(d.all[el_id]) d.all[el_id][aEL](action, callback)
  }

  function aqs_modal_show() {
    d.all.aqs_modal_wrapper.classList.add('in')
  }

  function aqs_modal_hide() {
    d.all.aqs_modal_wrapper.classList.remove('in')
  }

  window.AqsObject = {
    aqs_modal_show: aqs_modal_show
  }

}(document, console.log, 'click', 'addEventListener'))