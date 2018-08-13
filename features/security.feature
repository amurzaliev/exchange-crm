# language: ru

@security @fixtures
Функционал: Тестируем фукционал ограничения доступа для различных ролей и привилегий пользователей

  # Обменные пункты

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть обменный пункт, к которому я привязан
    Когда я перехожу на просмотр обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "Просмотр обменного пункта" где-то на странице
    Тогда я вижу слово "Название обменного пункта: Обменный пункт №1" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу отредактировать обменный пункт, к которому я привязан
    Когда я перехожу на редактирование обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff2 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть обменный пункт, не имеющий ко мне отношения
    Когда я перехожу на просмотр обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff2 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу отредактировать обменный пункт, не имеющий ко мне отношения
    Когда я перехожу на редактирование обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "404" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу просмотреть чужой обменный пункт
    Когда я перехожу на просмотр обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "Просмотр обменного пункта" где-то на странице
    Тогда я вижу слово "Название обменного пункта: Обменный пункт №1" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу отредактировать чужой обменный пункт
    Когда я перехожу на редактирование обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "Редактирование обменного пункта" где-то на странице
    Тогда поле формы "exchange_office_name" должно содержать значение "Обменный пункт №1"

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор и хочу просмотреть чужой обменный пункт
    Когда я перехожу на просмотр обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "Просмотр обменного пункта" где-то на странице
    Тогда я вижу слово "Название обменного пункта: Обменный пункт №1" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор и хочу отредактировать чужой обменный пункт
    Когда я перехожу на редактирование обменного пункта "Обменный пункт №1"
    Тогда я вижу слово "Редактирование обменного пункта" где-то на странице
    Тогда поле формы "exchange_office_name" должно содержать значение "Обменный пункт №1"

  # Типы валют

  @loginAdmin @logout
    Сценарий: Я захожу на сайт как администратор и хочу просмотреть тип валюты Доллар США
    Когда я перехожу на просмотр типа валюты "Доллар США"
    Тогда я вижу слово "Название валюты: Доллар США" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как администратор и хочу отредактировать тип валюты Доллар США
    Когда я перехожу на редактирование типа валюты "Доллар США"
    Тогда я вижу слово "Редактирование валюты" где-то на странице
    Тогда поле формы "currency_name" должно содержать значение "Доллар США"

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу просмотреть тип валюты Доллар США
    Когда я перехожу на просмотр типа валюты "Доллар США"
    Тогда я вижу слово "404" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу отредактировать тип валюты Доллар США
    Когда я перехожу на редактирование типа валюты "Доллар США"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть тип валюты Доллар США
    Когда я перехожу на просмотр типа валюты "Доллар США"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу отредактировать тип валюты Доллар США
    Когда я перехожу на редактирование типа валюты "Доллар США"
    Тогда я вижу слово "404" где-то на странице

  # Группы привилегий

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть группы привилегий
    Когда я перехожу на просмотр группы привилегий "оператор"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу отредактировать группы привилегий
    Когда я перехожу на редактирование группы привилегий "оператор"
    Тогда я вижу слово "404" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и простматриваю данные группы привилегий
    Когда я перехожу на просмотр группы привилегий "оператор"
    И я вижу слово "Название группы:" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец обменных пунктов и редактирую группу "оператор"
    Когда я перехожу на редактирование группы привилегий "оператор"
    Тогда я вижу слово "Редактирования Группы привилегий" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как админ и простматриваю данные группы привилегий
    Когда я перехожу на просмотр группы привилегий "оператор"
    И я вижу слово "Название группы:" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как админ и редактирую группу "оператор"
    Когда я перехожу на редактирование группы привилегий "оператор"
    Тогда я вижу слово "Редактирования Группы привилегий" где-то на странице

  # Персонал обменных пунктов

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть свою страницу
    Когда я перехожу на просмотр страницы сотрудника с логином "staff_1"
    Тогда я вижу слово "Управление персоналом" где-то на странице
    Тогда я вижу слово "Логин: staff_1" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу отредактировать страницу сотрудника
    Когда я перехожу на редактирование страницы сотрудника с логином "staff_2"
    Тогда я вижу слово "404" где-то на странице

  @loginStaff1 @logout
  Сценарий: Я захожу на сайт как сотрудник и хочу просмотреть страницу другого сотрудника
    Когда я перехожу на просмотр страницы сотрудника с логином "staff_3"
    Тогда я вижу слово "404" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу просмотреть страницу сотрудника
    Когда я перехожу на просмотр страницы сотрудника с логином "staff_1"
    Тогда я вижу слово "Управление персоналом" где-то на странице
    Тогда я вижу слово "Логин: staff_1" где-то на странице

  @loginOwner @logout
  Сценарий: Я захожу на сайт как владелец и хочу отредактировать страницу сотрудника
    Когда я перехожу на редактирование страницы сотрудника с логином "staff_2"
    Тогда я вижу слово "Управление персоналом" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт админ и хочу просмотреть страницу сотрудника
    Когда я перехожу на просмотр страницы сотрудника с логином "staff_1"
    Тогда я вижу слово "404" где-то на странице

  @loginAdmin @logout
  Сценарий: Я захожу на сайт как админ и хочу отредактировать страницу сотрудника
    Когда я перехожу на редактирование страницы сотрудника с логином "staff_2"
    Тогда я вижу слово "404" где-то на странице


