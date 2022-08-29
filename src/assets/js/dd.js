function unfold (triangle, id) {
    $(triangle).toggleClass('unfold')
    const container = $('#' + id)
    container.toggleClass('unfold')
  }
  
  $('#dd_hide').click(function () {
    $('#dd_content').toggleClass('fold')
  })
  