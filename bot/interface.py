from telebot import types

from enums import RequestStatus

remove_keyboard = types.ReplyKeyboardRemove(selective=False)

PARSE_MODE = 'Markdown'

PAGE_SIZE = 5


def create_markup_for_request(user_requests):
    markup = types.InlineKeyboardMarkup()
    for i, request in enumerate(user_requests):
        markup.row(
            types.InlineKeyboardButton(
                f"{request['id']}. {request['type']}, Статус: Одобрена{RequestStatus.RequestStatus.OK.value}\n",
                callback_data=f'request_{i}'))
    return markup
