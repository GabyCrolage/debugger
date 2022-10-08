function unfold (triangle, id) {
  $(triangle).toggleClass('unfold')
  const container = $('#' + id)
  container.toggleClass('unfold')
}
