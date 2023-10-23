from telebot import types

from dictionaries.request_statuses import request_statuses

remove_keyboard = types.ReplyKeyboardRemove(selective=False)

PARSE_MODE = 'Markdown'

PAGE_SIZE = 5
INITIAL_VALUE = 0


def create_markup_for_request(user_requests):
    markup = types.InlineKeyboardMarkup()
    for i, request in enumerate(user_requests):
        markup.row(
            types.InlineKeyboardButton(
                f"{request['id']}. {request['insurance_object_type_id']}, "
                f"Статус: {request['status']['name']}{request_statuses[request['status']['id']]}\n",
                callback_data=f'request_{i}'))
    return markup
