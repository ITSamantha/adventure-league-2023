import enum


class UserRole(enum.Enum):
    USER = 3
    MODERATOR = 2
    ADMIN = 1

    def __str__(self):
        return str(self.value)
