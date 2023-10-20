class Validator:

    @staticmethod
    def is_digit_in_str(s):
        return any(ch.isdigit() for ch in s)
