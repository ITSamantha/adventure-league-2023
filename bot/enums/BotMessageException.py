import enum


class BotMessageException(enum.Enum):
    CLIENT_EXCEPTION_MSG = 'Упс! Произошла какая-то ошибка на стороне клиента...😢'
    SERVER_EXCEPTION_MSG = 'Упс! Произошла какая-то ошибка на сервере...😢'
    OTHER_EXCEPTION_MSG = 'Хм, что-то непредвиденное случилось...Мы уже работаем над данной проблемой😢'

    def __str__(self):
        return self.value
