import enum


class Mode(enum.Enum):
    YES = 'yes'
    NO = 'no'

    def __str__(self):
        return self.value
