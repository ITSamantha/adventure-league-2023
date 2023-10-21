import telebot
from telebot import types
from handlers.common import requests, handle_menu

PAGE_SIZE = 5
CURRENT_PAGE = 0
bot = None


def handle_moderator_list_of_requests(message, bot):
    user_id = message.chat.id
    start_index = CURRENT_PAGE * PAGE_SIZE
    end_index = start_index + PAGE_SIZE
    user_requests = requests[start_index:end_index]
    if user_requests:
        status_message = "Статус текущих заявок на осмотр:\n"
        markup = types.InlineKeyboardMarkup()
        for i, request in enumerate(user_requests):
            markup.row(
                types.InlineKeyboardButton(
                    f"{request['id']}. {request['type']}, Статус: Одобрена\n",
                    callback_data=f'request_{i}'))
        left = types.InlineKeyboardButton("⬅️", callback_data='prev_page')
        right = types.InlineKeyboardButton("➡️", callback_data='next_page')
        if CURRENT_PAGE > 0 and end_index < len(requests):
            markup.row(left, right)
        else:
            if end_index < len(requests):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, status_message, reply_markup=markup)
    else:
        bot.send_message(user_id, "Нет текущих заявок на осмотр.")
    handle_menu(message, bot)


def register_handlers_moderator(bot):
    bot.register_message_handler(handle_moderator_list_of_requests,
                                 func=lambda message: message.text == "Просмотр списка заявок", pass_bot=True)
