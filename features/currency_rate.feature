# language: ru

@currency_rate
Функционал: Тестируем функционал работы с курсами валют

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и просматриваю курс валюты Доллар США
    Когда я перехожу по маршруту "Личный кабинет"
    Когда я нажимаю на ссылку "Кассы"
    Когда я нажимаю на ссылку "Просмотреть курс валюты Доллар США"
    Тогда я вижу слово "Управление курсом валюты: Доллар США" где-то на странице
    Тогда я вижу слово "68.05" где-то на странице
    Тогда я вижу слово "68.15" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и создаю новый курс для валюты Доллар США
    Когда я перехожу по маршруту "Личный кабинет"
    Когда я нажимаю на ссылку "Кассы"
    Когда я нажимаю на ссылку "Просмотреть курс валюты Доллар США"
    Когда я нажимаю на ссылку "Новый курс"
    И я вижу слово "Новый курс валюты: Доллар США" где-то на странице
    Когда я заполняю поля формы:
      | currency_rate_purchase    | 69.00 |
      | currency_rate_sale | 69.20    |
    Когда я нажимаю на кнопку "Добавить"
    Тогда я вижу слово "69.00" где-то на странице
    Тогда я вижу слово "69.20" где-то на странице
