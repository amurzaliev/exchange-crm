# language: ru

@currency
Функционал: Тестируем CRUD для валют

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор в личный кабинет и просматриваю валюту Доллар США
    Когда я перехожу по маршруту "Личный кабинет"
    Когда я нажимаю на ссылку "Валюта"
    Когда я нажимаю на ссылку "Просмотреть запись Доллар США"
    Тогда я вижу слово "Название валюты: Доллар США" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор в личный кабинет и добавляю новую валюту
    Когда я перехожу по маршруту "Личный кабинет"
    Когда я нажимаю на ссылку "Валюта"
    Когда я нажимаю на ссылку "Добавить"
    И я заполняю поле формы "currency_name" значением "Евро"
    И я прикрепляю файл "euro-icon.jpg" к полю "currency_imageFile"
    И я нажимаю на кнопку "Добавить"
    Тогда я вижу слово "Евро" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор в личный кабинет и редактирую валюту Евро
    Когда я перехожу по маршруту "Личный кабинет"
    Когда я нажимаю на ссылку "Валюта"
    Когда я нажимаю на ссылку "Редактировать запись Евро"
    И я заполняю поле формы "currency_name" значением "Евро (new)"
    И я прикрепляю файл "euro-icon.jpg" к полю "currency_imageFile"
    И я нажимаю на кнопку "Сохранить"
    Тогда я вижу слово "Евро (new)" где-то на странице