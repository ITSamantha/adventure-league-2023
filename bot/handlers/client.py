import re

from telebot import types

import telebot

import handlers
from converters.converter import StringConverter
from enums.BotMessageException import BotMessageException
from enums.RequestStatus import RequestStatus
from enums.UserRole import UserRole
from exceptions.ClientException import ClientException
from exceptions.ServerException import ServerException

from http_client.http_client import HttpClient

remove_keyboard = types.ReplyKeyboardRemove(selective=False)

insurance_types = ["Транспортное средство", "Загородный дом"]

left = types.InlineKeyboardButton("⬅️", callback_data='prev_page')
right = types.InlineKeyboardButton("➡️", callback_data='next_page')


def callback_client_load_request(call, bot):
    current_req = requests[int(call.data.split('_')[1])]
    user_id = str(call.message.chat.id)
    try:
        user = HttpClient.get('get_user', user_id)
        message = f"        ___Заявка №{current_req['id']}___\n\n" \
                  f"___Статус___:       Одобрена{RequestStatus.OK.value}\n"\
                   f"___Владелец___:     {user['data']['name']}\n" \
                  f"___Тип страхуемого объекта___:      {current_req['type']}\n" \
                  f"___Описание___:     {current_req['description']}\n\n" \
                  f"Фото: {current_req['photos']}\n"
        bot.send_message(call.message.chat.id, message, parse_mode=handlers.common.PARSE_MODE)
        if user['data']['roles'][0] == UserRole.MODERATOR.value:
            keyboard = types.InlineKeyboardMarkup()
            key_1 = types.InlineKeyboardButton(text=f'Одобрена{RequestStatus.OK.value}', callback_data='req_yes')
            key_2 = types.InlineKeyboardButton(text=f'На рассмотрении{RequestStatus.WAIT.value}',
                                               callback_data='req_wait')
            key_3 = types.InlineKeyboardButton(text=f'Отклонена{RequestStatus.NOT_OK.value}', callback_data='req_no')
            keyboard.add(key_1)
            keyboard.add(key_2)
            keyboard.add(key_3)
            bot.send_message(call.message.chat.id, 'Необходимо выбрать статус заявки:', reply_markup=keyboard)
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


# Обработчик выбора типа страхования (inline кнопки)
def handle_insured_objects(call, bot):
    user_id = str(call.message.chat.id)
    insurance_object_type_id = int(call.data.split('-')[1])
    if user_id not in handlers.common.users:
        handlers.common.users[user_id]['current_request'] = {}
    handlers.common.users[user_id]['current_request']['insured_object_type_id'] = insurance_object_type_id
    bot.send_message(user_id, "Введите текстовое описание объекта:")
    bot.register_next_step_handler(call.message, handle_description, bot)


"""
# Обработчик загрузки фотографий
def handle_photos(message, user_data, photos):
    # print(f'Handle photos:{message}')
    user_id = message.chat.id
    if not photos:
        bot.send_message(user_id, f'Заявка отправлена! Ожидайте рассмотрения☺️.')
        handle_menu(message)
    bot.send_message(user_id, f'Отправьте фото, соответствующее следующему описанию. {photos[0]}')
    bot.register_next_step_handler(message, get_photo, photos)

def get_photo(message, photos):

    print(message)

    files = []
    user_id = message.chat.id
    if message.document:
        files.append(message.document)
    elif message.audio:
        files.append(message.audio)
    elif message.photo:
        files.extend(message.photo)  # Добавляем все фотографии из сообщения в список
    elif message.video:
        files.append(message.video)
    elif message.voice:
        files.append(message.voice)
    elif message.sticker:
        files.append(message.sticker)

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

    bot.register_next_step_handler(message, get_photo, photos)


"""


# Обработчик ввода текстового описания
def handle_description(message, bot):
    user_id = str(message.chat.id)
    if user_id in handlers.common.users:
        handlers.common.users[user_id]['current_request'] = {}
    comment = StringConverter.strip_str(message)
    handlers.common.users[user_id]['current_request']['comment'] = comment
    keyboard = types.InlineKeyboardMarkup()
    key_yes = types.InlineKeyboardButton(text='Приступить', callback_data='photo_yes')
    key_no = types.InlineKeyboardButton(text='Главное меню', callback_data='photo_no')
    keyboard.row(key_yes, key_no)
    bot.send_message(user_id,
                     "Сейчас будет происходить поэтапная загрузка фото.\nПожалуйста, обращайте внимание, что конкретно требуется запечатлеть на фото.",
                     reply_markup=keyboard)


def handle_new_request(message, bot):
    user_id = str(message.chat.id)
    try:
        # insurance_types = HttpClient.get('path', user_id)
        markup = types.InlineKeyboardMarkup()
        for obj in insurance_types:
            markup.add(types.InlineKeyboardButton(obj, callback_data='object_type-{id}'.format(id=id(obj))))
        bot.send_message(user_id, "Выберите объект страхования:", reply_markup=markup)
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


requests = [{'id': x, 'type': 'Транспортное средство', 'description': 'd', 'photos': None} for x in range(100)]


def handle_user_list_of_requests(message, bot):
    user_id = str(message.chat.id)
    if user_id not in handlers.common.users:
        handlers.common.users[user_id] = {}
    if user_id in handlers.common.users:
        handlers.common.users[user_id]['current_page'] = 0
    current_page = handlers.common.users[user_id]['current_page']
    start_index = current_page * handlers.common.PAGE_SIZE
    end_index = start_index + handlers.common.PAGE_SIZE
    user_requests = requests[start_index:end_index]
    if user_requests:
        status_message = "Статус текущих заявок на осмотр:\n"
        markup = handlers.common.create_markup_for_request(user_requests)

        if current_page > 0 and end_index < len(requests):
            markup.row(left, right)
        else:
            if end_index < len(requests):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, status_message, reply_markup=markup)
    else:
        bot.send_message(user_id, "Нет текущих заявок на осмотр.")
    handlers.common.handle_menu(message, bot)


def handle_requests_history(message, bot):
    user_id = str(message.chat.id)
    if user_id not in handlers.common.users:
        handlers.common.users[user_id] = {}
    if user_id in handlers.common.users:
        handlers.common.users[user_id]['current_page'] = 0
    if not requests:
        bot.send_message(user_id, "Нет истории заявок.")
        handlers.common.handle_menu(message, bot)
    else:
        send_history_page(user_id, bot)
    print(requests)


# Отправка страницы с историей заявок
def send_history_page(user_id, bot):
    current_page = handlers.common.users[user_id]['current_page']
    start_index = current_page * handlers.common.PAGE_SIZE
    end_index = start_index + handlers.common.PAGE_SIZE
    user_requests = requests[start_index:end_index]
    if user_requests:
        history_message = "История заявок:\n"
        markup = handlers.common.create_markup_for_request(user_requests)
        if current_page > 0 and end_index < len(requests):
            markup.row(left, right)
        else:
            if end_index < len(requests):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, history_message, reply_markup=markup)
    else:
        bot.send_message(user_id, "История заявок пуста.")


def register_handlers_client(bot):
    bot.register_message_handler(handle_user_list_of_requests,
                                 func=lambda message: message.text == "Просмотр статуса заявок", pass_bot=True)
    bot.register_message_handler(handle_requests_history,
                                 func=lambda message: message.text == "Просмотр истории заявок", pass_bot=True)
    bot.register_message_handler(handle_new_request,
                                 func=lambda message: message.text == "Подача новой заявки", pass_bot=True)
    bot.register_callback_query_handler(callback_client_load_request,
                                        func=lambda call: re.search(r'^request', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_insured_objects, pass_bot=True,
                                        func=lambda call: re.search(r'^object_type', call.data))
