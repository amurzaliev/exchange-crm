# language: ru

@exchange_office
Функционал: Тестируем создание редактирование и просмотр обменного пункта

  Сценарий: Я захожу на сайт как владелец обменных пунктов и простмотриваю данные обменного пункта
    Допустим я нахожусь на главной странице
    Когда я захожу на сайт как "owner@mail.ru" с паролем "12345"
    И я вижу слово "Личный кабинет" где-то на странице
    Когда я нажимаю на ссылку: Обменные пункты
    Когда я нажимаю на ссылку: Просмотр
    И я вижу слово "Редактирование обменного пункта" где-то на странице
    И я вижу слово "Название обменного пункта:" где-то на странице
    И я вижу слово "Адрес:" где-то на странице
    И я вижу слово "Контакты:" где-то на странице
    И я вижу слово "Активность:" где-то на странице
    И я завершаю сеанс

  Сценарий: Я захожу на сайт как владелец обменных пунктов и редактирую обменный пункт
    Допустим я нахожусь на главной странице
    Когда я захожу на сайт как "owner@mail.ru" с паролем "12345"
    Когда я нажимаю на ссылку: Обменные пункты
    И я вижу слово "Обменные пункты" где-то на странице
    Когда я нажимаю на ссылку: Редактировать
    И я вижу слово "Редактирование обменного пункта" где-то на странице
    Когда я заполняю поле формы: exchange_office_name, значением: Обменный пункт 2
    Когда я заполняю поле формы: exchange_office_address, значением: Ахунбаева 114
    Когда я заполняю поле формы: exchange_office_contact, значением: 0312 212223
    Когда я отмечаю галочкой опцию: Активировать
    Когда я нажимаю на кнопку: Сохранить
    И я вижу слово "Обменный пункт 2" где-то на странице
    И я вижу слово "Ахунбаева 114" где-то на странице
    И я вижу слово "0312 212223" где-то на странице
    И я завершаю сеанс

  Сценарий: Я захожу на сайт как владелец обменных пунктов и добавлению обменный пункт
    Допустим я нахожусь на главной странице
    Когда я захожу на сайт как "owner@mail.ru" с паролем "12345"
    Когда я нажимаю на ссылку: Обменные пункты
    Когда я нажимаю на ссылку: Добавить
    И я вижу слово "Добавления нового обменного пункта" где-то на странице
    Когда я заполняю поле формы: exchange_office_name, значением: Обменный пункт 3
    Когда я заполняю поле формы: exchange_office_address, значением: Чуй 114
    Когда я заполняю поле формы: exchange_office_contact, значением: 0312 254543
    Когда я отмечаю галочкой опцию: Активировать
    Когда я нажимаю на кнопку: Добавить
    И я вижу слово "Обменный пункт 3" где-то на странице
    И я вижу слово "Чуй 114" где-то на странице
    И я вижу слово "0312 254543" где-то на странице
    И я завершаю сеанс


