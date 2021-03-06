# language: ru

  @crud_vipClient @fixtures

Функционал: Тестируем создание, редактирование и просмотр VIP клиентов

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и простмотриваю данные VIP клиентов
    Когда я нажимаю на ссылку "VIP клиенты"
    Когда я нажимаю на ссылку "Просмотреть запись Гарри Поттер"
    И я вижу слово "Данные VIP клиента" где-то на странице
    И я вижу слово "E-mail:" где-то на странице
    И я вижу слово "Номер телефона:" где-то на странице
    И я вижу слово "Время создания:" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и редактирую данные VIP клиентов
    Когда я нажимаю на ссылку "VIP клиенты"
    Когда я нажимаю на ссылку "Редактировать запись Гарри Поттер"
    И я вижу слово "Редактирование VIP клиента" где-то на странице
    И я заполняю поле формы "E-mail" значением "harry.potter@google.com"
    Когда я нажимаю на кнопку "Сохранить"
    И я вижу слово "harry.potter@google.com" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и добавляю новую касу
    Допустим я нахожусь на странице "Добавление ВИП-клиента"
    И я вижу слово "Добавление VIP клиента" где-то на странице
    И я заполняю поле формы "ФИО" значением "Рональд Уизли"
    И я заполняю поле формы "E-mail" значением "ronald@google.com"
    И я заполняю поле формы "Контакты" значением "996555555555"
    Когда я нажимаю на кнопку "Добавить"
    И я вижу слово "Рональд Уизли" где-то на странице