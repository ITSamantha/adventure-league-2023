from telebot import types

"""
bot.register_message_handler(handle_request_information,
                                 func=lambda message: True)
"""


def handle_photo(message):
    print('got')


def register_handlers_photo(bot):
    bot.register_message_handler(handle_photo,
                                 func=lambda message: True)
