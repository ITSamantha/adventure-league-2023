import enum


class ResponseStatus(enum.Enum):
    OK = 200,
    REDIRECT = 300,
    CLIENT_ERROR = 400,
    SERVER_ERROR = 500

    def __str__(self):
        return str(self.value)
