import enum


class FileType(enum.Enum):
    PHOTO = 1
    VIDEO = 2
    DOCUMENT = 3
    TEXT = 4

    def __str__(self):
        return str(self.value)
