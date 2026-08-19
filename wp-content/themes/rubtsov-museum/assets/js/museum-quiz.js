document.addEventListener("DOMContentLoaded", function () {
  const startButton = document.getElementById("rubtsov-quiz-start");
  const intro = document.querySelector(".rubtsov-quiz-intro");
  const game = document.getElementById("rubtsov-quiz-game");

  if (!startButton || !intro || !game) {
    return;
  }

  let questions = [];
  let currentQuestion = 0;
  let score = 0;
  let startTime = 0;
  let timerInterval = null;

  const userAnswers = [];

  /*
   * Форматирование времени
   */

  function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;

    return (
      String(minutes).padStart(2, "0") + ":" + String(secs).padStart(2, "0")
    );
  }

  /*
   * Получение текущего времени
   */

  function getElapsedTime() {
    return Math.floor((Date.now() - startTime) / 1000);
  }

  /*
   * Запуск таймера
   */

  function startTimer() {
    startTime = Date.now();

    timerInterval = setInterval(function () {
      const timer = document.getElementById("rubtsov-quiz-timer");

      if (timer) {
        timer.textContent = formatTime(getElapsedTime());
      }
    }, 1000);
  }

  /*
   * Остановка таймера
   */

  function stopTimer() {
    if (timerInterval) {
      clearInterval(timerInterval);
      timerInterval = null;
    }
  }

  /*
   * Старт квиза
   */

  startButton.addEventListener("click", function () {
    intro.style.display = "none";
    game.style.display = "block";

    game.innerHTML = `
            <div class="rubtsov-quiz-loading">
                <div class="rubtsov-quiz-loading-title">
                    Загружаем вопросы
                </div>

                <div class="rubtsov-quiz-loading-text">
                    Подготавливаем путешествие по истории Николая Рубцова...
                </div>
            </div>
        `;

    fetch(
      "/wordpress/wp-json/wp/v2/quiz_question?per_page=100&orderby=date&order=asc",
    )
      .then(function (response) {
        if (!response.ok) {
          throw new Error("Ошибка загрузки вопросов");
        }

        return response.json();
      })

      .then(function (loadedQuestions) {
        if (!loadedQuestions.length) {
          game.innerHTML = `
                    <div class="rubtsov-quiz-empty">
                        <h2>Вопросов пока нет</h2>

                        <p>
                            Администратор ещё не добавил вопросы
                            для этого квиза.
                        </p>
                    </div>
                `;

          return;
        }

        questions = loadedQuestions;

        currentQuestion = 0;
        score = 0;

        userAnswers.length = 0;

        startTimer();

        showQuestion();
      })

      .catch(function (error) {
        console.error(error);

        game.innerHTML = `
                <div class="rubtsov-quiz-error">

                    <h2>
                        Не удалось загрузить вопросы
                    </h2>

                    <p>
                        Попробуйте обновить страницу
                        и запустить квиз ещё раз.
                    </p>

                </div>
            `;
      });
  });

  /*
   * Показываем вопрос
   */

  function showQuestion() {
    const question = questions[currentQuestion];
    const data = question.quiz_data;

    const totalQuestions = questions.length;

    const progress = (currentQuestion / totalQuestions) * 100;

    game.innerHTML = `

            <div class="rubtsov-quiz-header">

                <div class="rubtsov-quiz-progress-info">

                    <span>
                        Вопрос
                        <strong>
                            ${currentQuestion + 1}
                        </strong>
                        из
                        <strong>
                            ${totalQuestions}
                        </strong>
                    </span>

                    <span
                        id="rubtsov-quiz-timer"
                        class="rubtsov-quiz-timer"
                    >
                        ${formatTime(getElapsedTime())}
                    </span>

                </div>


                <div class="rubtsov-quiz-progress">

                    <div
                        class="rubtsov-quiz-progress-bar"
                        style="width:${progress}%"
                    ></div>

                </div>

            </div>


            <div class="rubtsov-quiz-question">

                <h2 class="rubtsov-quiz-question-title">

                    ${data.question}

                </h2>


                <div class="rubtsov-quiz-answers">

                    ${data.answers
                      .map(function (answer, index) {
                        return `

                                <button
                                    type="button"
                                    class="rubtsov-quiz-answer"
                                    data-answer="${index + 1}"
                                >

                                    <span
                                        class="rubtsov-quiz-answer-text"
                                    >
                                        ${answer}
                                    </span>

                                </button>

                            `;
                      })
                      .join("")}

                </div>

            </div>

        `;

    const answerButtons = game.querySelectorAll(".rubtsov-quiz-answer");

    answerButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const selectedAnswer = Number(button.dataset.answer);

        /*
         * Запоминаем ответ пользователя
         */

        userAnswers.push({
          question: data.question,

          answers: data.answers,

          selected: selectedAnswer,

          correct: data.correct,
        });

        /*
         * Проверяем ответ
         */

        if (selectedAnswer === Number(data.correct)) {
          score++;
        }

        /*
         * Блокируем кнопки
         */

        answerButtons.forEach(function (item) {
          item.disabled = true;
        });

        button.classList.add("selected");

        /*
         * Небольшая задержка
         * перед следующим вопросом
         */

        setTimeout(function () {
          currentQuestion++;

          if (currentQuestion < questions.length) {
            showQuestion();
          } else {
            showResult();
          }
        }, 350);
      });
    });
  }

  /*
   * Итоговый результат
   */

  function showResult() {
    const percentage = Math.round((score / questions.length) * 100);

    let level = "";
    let description = "";

    if (percentage >= 90) {
      level = "Знаток Рубцова";
      description =
        "Вы отлично знаете жизнь, творчество и судьбу Николая Рубцова.";
    } else if (percentage >= 70) {
      level = "Исследователь";
      description = "Вы хорошо знакомы с жизнью и творчеством Николая Рубцова.";
    } else if (percentage >= 40) {
      level = "Любитель";
      description =
        "Вы уже знакомы с творчеством Николая Рубцова, но впереди ещё много интересного.";
    } else {
      level = "Начинающий исследователь";
      description =
        "Вы только начинаете знакомство с жизнью и творчеством Николая Рубцова.";
    }

    game.innerHTML = `
    <div class="rubtsov-quiz-result">

      <div class="rubtsov-quiz-result-label">
        КВИЗ ЗАВЕРШЁН
      </div>

      <h2>${level}</h2>

      <div class="rubtsov-quiz-score">
        <strong>${score}</strong>
        <span>из ${questions.length}</span>
      </div>

      <div class="rubtsov-quiz-percent">
        Правильных ответов <strong>${percentage}%</strong>
      </div>

      <div class="rubtsov-quiz-description">
        ${description}
      </div>

      <div class="rubtsov-quiz-result-actions">

        <button
          type="button"
          class="rubtsov-quiz-details-button"
          id="rubtsov-quiz-details"
        >
          Посмотреть разбор ответов
        </button>

        <button
          type="button"
          class="rubtsov-quiz-restart"
          id="rubtsov-quiz-restart"
        >
          Пройти квиз ещё раз
        </button>

      </div>

      <div
        class="rubtsov-quiz-details"
        id="rubtsov-quiz-details-content"
        style="display:none;"
      >
        <h3>Разбор ответов</h3>

        <div class="rubtsov-quiz-review">
          ${questions
            .map(function (question, index) {
              const data = question.quiz_data;
              const userAnswer = userAnswers[index];

              const selectedAnswer = userAnswer
                ? Number(userAnswer.selected)
                : null;

              const isCorrect =
                selectedAnswer !== null &&
                selectedAnswer === Number(data.correct);

              return `
                <div class="
                  rubtsov-quiz-review-item
                  ${isCorrect ? "is-correct" : "is-wrong"}
                ">

                  <div class="rubtsov-quiz-review-number">
                    Вопрос ${index + 1}
                  </div>

                  <div class="rubtsov-quiz-review-question">
                    ${data.question}
                  </div>

                  <div class="rubtsov-quiz-review-answer">
                    <span>Ваш ответ</span>
                    <strong>
                      ${selectedAnswer !== null ? data.answers[selectedAnswer - 1] : "Нет ответа"}
                    </strong>
                  </div>

                  <div class="rubtsov-quiz-review-status ${isCorrect ? "correct" : "wrong"}">
                    ${isCorrect ? "✓ Ответ правильный" : "✕ Ответ неправильный"}
                    </div>

                </div>
              `;
            })
            .join("")}
        </div>
      </div>

    </div>
  `;

    document
      .getElementById("rubtsov-quiz-details")
      .addEventListener("click", function () {
        const details = document.getElementById("rubtsov-quiz-details-content");

        if (details.style.display === "none") {
          details.style.display = "block";
          this.textContent = "Скрыть разбор ответов";
        } else {
          details.style.display = "none";
          this.textContent = "Посмотреть разбор ответов";
        }
      });

    document
      .getElementById("rubtsov-quiz-restart")
      .addEventListener("click", function () {
        currentQuestion = 0;
        score = 0;

        userAnswers.length = 0;

        startTimer();

        showQuestion();
      });
  }
});
