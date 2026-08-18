jQuery(document).ready(function ($) {
  $("#museum_home_background_button").on("click", function (e) {
    e.preventDefault();

    var frame = wp.media({
      title: "Выберите фон главного блока",
      button: {
        text: "Использовать изображение",
      },
      multiple: false,
    });

    frame.on("select", function () {
      var attachment = frame.state().get("selection").first().toJSON();

      $("#museum_home_background").val(attachment.url);
    });

    frame.open();
  });
});
