# language: ru

@crud_transactions_balance @fixtures

Функционал: Тестируем пополнение и списание денежных средств с баланса касс

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и пополняю "сомовую кассу"
    Когда я нахожусь на странице "Управление обменными пунктами"
    И я нажимаю на ссылку "Управление обменным пунктом Обменный пункт Абдрахманова"
    И я вижу слово "Обменный пункт Абдрахманова" где-то на странице
    И я нажимаю на ссылку "Валютные кассы и баланс"
    Когда я заполняю поля формы:
      | cashbox_amount_KGS    | 200000 |
      | notes    | Пополнение баланса в связи с тем что у меня есть эта сумма |
    И я нажимаю на кнопку "Выполнить операцию"
    И я жду 2 секунд
    Тогда я вижу слово "Данные успешно сохранены!" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и списываю с "сомовой кассы"
    Когда я нахожусь на странице "Управление обменными пунктами"
    И я нажимаю на ссылку "Управление обменным пунктом Обменный пункт Абдрахманова"
    И я вижу слово "Обменный пункт Абдрахманова" где-то на странице
    И я нажимаю на ссылку "Валютные кассы и баланс"
    Когда я заполняю поля формы:
      | cashbox_amount_KGS    | 50000 |
      | notes    | Списание баланса в связи с тем что мене нужна это сумма |
    И я выбираю опцию "Списание" из поля выбора "type_transaction"
    И я нажимаю на кнопку "Выполнить операцию"
    И я жду 5 секунд
    Тогда я вижу слово "Данные успешно сохранены!" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и списываю бльше средств чем есть в "сомовой кассе"
    Когда я нахожусь на странице "Управление обменными пунктами"
    И я нажимаю на ссылку "Управление обменным пунктом Обменный пункт Абдрахманова"
    И я вижу слово "Обменный пункт Абдрахманова" где-то на странице
    И я нажимаю на ссылку "Валютные кассы и баланс"
    Когда я заполняю поля формы:
      | cashbox_amount_KGS    | 8000000 |
      | notes    | Списание баланса в связи с тем что мене нужна это сумма |
    И я выбираю опцию "Списание" из поля выбора "type_transaction"
    И я нажимаю на кнопку "Выполнить операцию"
    И я жду 5 секунд
    Тогда я вижу слово "Недостаточно средств в кассе" где-то на странице