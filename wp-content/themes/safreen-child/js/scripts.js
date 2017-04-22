(function()) {

  $('.modalClick').on('click', function(event) {
    event.preventDefault();
    $('.modal')
    .fadeIn()
    .find('.modal-content')
    .fadeIn();
  });
})();
