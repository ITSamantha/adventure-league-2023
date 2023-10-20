import os
import re

import telebot

from bootstrap.bootstrap import bootstrap
from handlers.common import register_handlers_common
from handlers.client import register_handlers_client
from handlers.admin import register_handlers_admin
from handlers.moderator import register_handlers_moderator


bootstrap()

IS_ADMIN = False

# Глобальная переменная для хранения заявок


# Размер страницы для пагинации
page_size = 5

# Индекс текущей страницы
current_page = 0

GENERAL_PHOTO_REQUIREMENTS = "general-photo-requirements"
HELP_TITLE_CATEGORY = "title"
HELP_DESCRIPTION_CATEGORY = "title"



"""

@bot.callback_query_handler(func=lambda call: re.search(r'^photo', call.data))
def callback_photo(call):
    descriptions = car_photo
    print(call)
    mode = call.data.split('_')[1]
    if mode == "yes":
        bot.send_message(call.message.chat.id,
                         "Отлично! Приступим!☺️")
        handle_photos(call.message, descriptions)
    elif mode == "no":
        bot.send_message(call.message.chat.id, 'Окей! Тогда в другой раз☺️')
        handle_menu(call.message)




user_photo = {}


# Обработчик загрузки фотографий
def handle_photos(message, photos):
    user_id = message.chat.id
    if not photos:
        bot.send_message(user_id, f'Заявка отправлена! Ожидайте рассмотрения☺️.')
        handle_menu(message)
    bot.send_message(user_id, f'Отправьте фото, соответствующее следующему описанию. {photos[0]}')
    bot.register_next_step_handler(message, get_photo, photos)





def get_photo(message, photos):
    files = []
    user_id = message.chat.id

    photos.pop(0)

    for file in files:
        file_id = file.file_id
        file_info = bot.get_file(file_id)
        file_path = file_info.file_path
        downloaded_file = bot.download_file(file_info.file_path)
        src = file_info.file_path
        with open(src, 'wb') as new_file:
            new_file.write(downloaded_file)
        bot.send_message(user_id, f"Путь к файлу: {file_path}")

    handle_photos(message, photos)


statuses = {'OK': '✅', 'NOT OK': '❌', 'WAIT': '🕜'}





"""


def register_handlers_bot(_bot):

    register_handlers_client(_bot)
    register_handlers_moderator(_bot)
    register_handlers_admin(_bot)
    register_handlers_common(_bot)


bot = telebot.TeleBot(os.getenv('TELEGRAM_BOT_TOKEN'), threaded=True)
register_handlers_bot(bot)

bot.polling()
