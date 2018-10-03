# language: ru

  @crud_currency_rate @fixtures

Функционал: Тестируем функционал работы с курсами валют

#  @loginOwner @logout
#  Сценарий: Я захожу на сайт как владелец обменных пунктов и просматриваю курс валюты Тенге
#    Когда я нахожусь на странице "Личный кабинет"
#    Когда я нажимаю на ссылку "Кассы"
#    Когда я нажимаю на ссылку "Просмотреть курс валюты Доллар США"
#    Тогда я вижу слово "Управление курсом валюты: Доллар США" где-то на странице
#    Тогда я вижу слово "68.05" где-то на странице
#    Тогда я вижу слово "68.15" где-то на странице
#
#  @loginOwner @logout
#  Сценарий: Я захожу на сайт как владелец обменных пунктов и создаю новый курс для валюты Доллар США
#    Когда я нахожусь на странице "Личный кабинет"
#    Когда я нажимаю на ссылку "Кассы"
#    Когда я нажимаю на ссылку "Просмотреть курс валюты Доллар США"
#    Когда я нажимаю на ссылку "Новый курс"
#    И я вижу слово "Новый курс валюты: Доллар США" где-то на странице
#    Когда я заполняю поля формы:
#      | currency_rate_purchase    | 69.00 |
#      | currency_rate_sale        | 69.20 |
#    Когда я нажимаю на кнопку "Добавить"
#    Тогда я вижу слово "69.00" где-то на странице
#    Тогда я вижу слово "69.20" где-то на странице
#
#  @loginOwner @logout
#  Сценарий: Я захожу на сайт как владелец обменных пунктов и меняю курс влют Доллар США для всех обменных пунктов
#    Когда я нахожусь на странице "Личный кабинет"
#    Когда я жду 1 секунд
#    Когда я нажимаю на ссылку "Курсы вылют"
#    Когда я жду 1 секунд
#    Когда я заполняю поля формы:
#      | currency_rate_purchase    | 70.00 |
#      | currency_rate_sale        | 70.80 |
#    Когда я нажимаю на кнопку "Обменять курс валюты Доллар США"
#    Когда я жду 1 секунд
#    Тогда я вижу слово "Курс для всех касс Доллар США был успешно изменен во всех обменных пунктах" где-то на странице
#
#  @loginOwner @logout
#  Сценарий: Я захожу на сайт как владелец обменных пунктов и  меняю курс влют Доллар США для Обменного пуекта №1
#    Когда я нахожусь на странице "Личный кабинет"
#    Когда я нажимаю на ссылку "Курсы вылют"
#    Когда я жду 1 секунд
#    Когда я выбираю опцию "Обменный пункт №1" из поля выбора "selectExchange"
#    Когда я жду 1 секунд
#    Когда я заполняю поля формы:
#      | currency_rate_purchase    | 67.00 |
#      | currency_rate_sale        | 70.00 |
#    Когда я нажимаю на кнопку "Обменять курс валюты Доллар США"
#    Когда я жду 1 секунд
#    Тогда я вижу слово "Ваш курс Доллар США для обменного пункта Обменный пункт №1 успешно изменен" где-то на странице



    