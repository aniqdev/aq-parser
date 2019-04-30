(function(d, log, c, lnr) {

  d.all.aqs_modal_open_btn[lnr](c, aqs_modal_show)
  d.all.aqs_modal_close_btn[lnr](c, aqs_modal_hide)
  d.all.aqs_modal_wrapper[lnr](c, aqs_modal_hide)
  d.all.aqs_modal_dialog[lnr](c, function(e) {
    e.stopPropagation()
  })


  function aqs_modal_show() {
    d.all.aqs_modal_wrapper.classList.add('in')
    d.all.aqs_modal_wrapper.classList.add('fade')
  }

  function aqs_modal_hide(e) {
    d.all.aqs_modal_wrapper.classList.remove('in')
    d.all.aqs_modal_wrapper.classList.remove('fade')
  }

}(document, console.log, 'click', 'addEventListener'))
