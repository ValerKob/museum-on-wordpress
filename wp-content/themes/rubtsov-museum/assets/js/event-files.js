jQuery(document).ready(function ($) {
  $("#rubtsov-add-event-file").on("click", function (e) {
    e.preventDefault();

    const frame = wp.media({
      title: "Выберите файл",
      button: {
        text: "Добавить файл",
      },
      multiple: false,
    });

    frame.on("select", function () {
      const attachment = frame.state().get("selection").first().toJSON();

      const container = $("#rubtsov-event-files");

      const index = container.find(".rubtsov-event-file").length;

      const fileName = attachment.filename || attachment.title;

      const html = `
                <div
                    class="rubtsov-event-file"
                    style="
                        display:flex;
                        gap:10px;
                        align-items:center;
                        margin-bottom:10px;
                    "
                >

                    <input
                        type="text"
                        name="museum_event_files[${index}][url]"
                        value="${attachment.url}"
                        class="regular-text"
                        readonly
                    >

                    <input
                        type="text"
                        name="museum_event_files[${index}][name]"
                        value="${fileName}"
                        placeholder="Название файла"
                        class="regular-text"
                    >

                    <button
                        type="button"
                        class="button rubtsov-remove-event-file"
                    >
                        Удалить
                    </button>

                </div>
            `;

      container.append(html);
    });

    frame.open();
  });

  $(document).on("click", ".rubtsov-remove-event-file", function () {
    $(this).closest(".rubtsov-event-file").remove();
  });
});
