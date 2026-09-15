jQuery(document).ready(function ($) {
  $("#museum_site_background_button").on("click", function (e) {
    e.preventDefault();

    var mediaUploader = wp.media({
      title: "Выберите общий фон сайта",
      button: {
        text: "Использовать это изображение",
      },
      multiple: false,
    });

    mediaUploader.on("select", function () {
      var attachment = mediaUploader.state().get("selection").first().toJSON();

      $("#museum_site_background").val(attachment.url);
    });

    mediaUploader.open();
  });
});

jQuery(document).ready(function ($) {
  $("#museum_header_logo_button").on("click", function (e) {
    e.preventDefault();

    var mediaUploader = wp.media({
      title: "Выберите логотип",
      button: {
        text: "Использовать этот логотип",
      },
      multiple: false,
    });

    mediaUploader.on("select", function () {
      var attachment = mediaUploader.state().get("selection").first().toJSON();

      $("#museum_header_logo").val(attachment.url);
    });

    mediaUploader.open();
  });
});
