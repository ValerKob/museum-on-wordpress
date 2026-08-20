jQuery(document).ready(function ($) {
  $("#quiz_question_image_button").on("click", function (e) {
    e.preventDefault();

    var mediaUploader = wp.media({
      title: "Выберите изображение вопроса",
      button: {
        text: "Использовать это изображение",
      },
      multiple: false,
    });

    mediaUploader.on("select", function () {
      var attachment = mediaUploader.state().get("selection").first().toJSON();

      $("#quiz_question_image").val(attachment.url);
    });

    mediaUploader.open();
  });
});
