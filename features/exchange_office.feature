# language: ru

@exchange_office
Функционал: Тестируем создание редактирование и просмотр обменного пункта

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и простмотриваю данные обменного пункта
    Когда я нажимаю на ссылку "Обменные пункты"
    Когда я нажимаю на ссылку "Просмотр"
    И я вижу слово "Редактирование обменного пункта" где-то на странице
    И я вижу слово "Название обменного пункта:" где-то на странице
    И я вижу слово "Адрес:" где-то на странице
    И я вижу слово "Контакты:" где-то на странице
    И я вижу слово "Активность:" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и редактирую обменный пункт
    Когда я нажимаю на ссылку "Обменные пункты"
    Когда я нажимаю на ссылку "Редактировать запись Обменный пункт №1"
    И я вижу слово "Редактирование обменного пункта" где-то на странице
    Когда я заполняю поля формы:
      | exchange_office_name    | Обменный пункт #1 |
      | exchange_office_address | Ахунбаева 114    |
      | exchange_office_contact | 0312 212223      |
    Когда я отмечаю галочкой опцию "Активировать"
    Когда я нажимаю на кнопку "Сохранить"
    И я вижу слово "Обменный пункт #1" где-то на странице
    И я вижу слово "Ахунбаева 114" где-то на странице
    И я вижу слово "0312 212223" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и добавляю обменный пункт
    Когда я нажимаю на ссылку "Обменные пункты"
    Когда я нажимаю на ссылку "Добавить"
    И я вижу слово "Добавления нового обменного пункта" где-то на странице
    Когда я заполняю поля формы:
      | exchange_office_name    | Обменный пункт #2 |
      | exchange_office_address | Чуй 114          |
      | exchange_office_contact | 0312 254543      |
    Когда я отмечаю галочкой опцию "Активировать"
    Когда я нажимаю на кнопку "Добавить"
    И я вижу слово "Обменный пункт #2" где-то на странице
    И я вижу слово "Чуй 114" где-то на странице
    И я вижу слово "0312 254543" где-то на странице

