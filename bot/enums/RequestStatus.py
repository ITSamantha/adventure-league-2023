import enum


class RequestStatus(enum.Enum):
    OK = '✅'
    NOT_OK = '❌'
    WAIT = '🕜'

    def __str__(self):
        return str(self.value)
