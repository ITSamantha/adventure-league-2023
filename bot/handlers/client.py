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


def callback_client_load_request(call, bot):
    current_req = requests[int(call.data.split('_')[1])]
    user_id = str(call.message.chat.id)
    try:
        user = HttpClient.get('get_user', user_id)
        message = f"        ___Заявка №{current_req['id']}___\n\n" \
                  f"___Статус___:       Одобрена{RequestStatus.OK.value}\n" \
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
        handlers.common.users[user_id] = {}
    handlers.common.users[user_id]['current_request'] = {}
    handlers.common.users[user_id]['current_request']['insured_object_type_id'] = insurance_object_type_id
    try:
        json = {
            'object_type_id': insurance_object_type_id,
            'comment': 'comment'
        }
        response = HttpClient.post('insurance_requests', user_id, json=json)['data']
        handlers.common.users[user_id]['current_request']['id'] = response['id']
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))
    handle_request_information(call.message, bot)


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
def handle_approve_request(message, bot):
    user_id = str(message.chat.id)
    user_id = str(message.chat.id)
    if user_id in handlers.common.users:
        handlers.common.users[user_id]['current_request'] = {}
    comment = StringConverter.strip_str(message.text)
    handlers.common.users[user_id]['current_request']['comment'] = comment
    keyboard = types.InlineKeyboardMarkup()
    key_yes = types.InlineKeyboardButton(text='Приступить', callback_data='enter_request_info-yes')
    key_no = types.InlineKeyboardButton(text='Главное меню', callback_data='enter_request_info-no')
    keyboard.row(key_yes, key_no)
    bot.send_message(user_id,
                     "___Вам необходимо ввести данные для отправки заявки.___"
                     "Пожалуйста, **внимательно изучайте**, что необходимо прикрепить или ввести.",
                     reply_markup=keyboard, parse_mode=handlers.common.PARSE_MODE)


def handle_request_information(message, bot):
    user_id = str(message.chat.id)
    try:

        json = {
            'insurance_object_id': handlers.common.users[user_id]['current_request']['insured_object_type_id']
        }
        insurance_object_files = HttpClient.post('insurance_object_file_types/get', user_id, json=json)['data']

        for file_type in insurance_object_files:
            # Здесь пользователь может загружать несколько файлов для текущего file_type
            handlers.common.users[user_id]['current_request']['files_uploaded'] = 0
            # Проверьте, сколько файлов нужно загрузить для текущего file_type
            num_files_to_upload = file_type['min_photo_count']  # Замените это на ваше значение
            bot.send_message(user_id,
                             f"Загрузите файл для {file_type['file_description']} (минимум {num_files_to_upload}):")
            while handlers.common.users[user_id]['current_request']['files_uploaded'] < num_files_to_upload:
                @bot.message_handler(content_types=['document'])
                def handle_document(message):
                    if message.chat.id == int(user_id):
                        # Добавьте загруженный файл в список
                        handlers.common.users[user_id]['current_request']['files_uploaded'] += 1
                        print('Photo handled')

                        # Если все файлы загружены, завершите цикл
                        if handlers.common.users[user_id]['current_request']['files_uploaded'] == num_files_to_upload:
                            return


    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


def callback_enter_request_info(call, bot):
    user_id = str(call.message.chat.id)
    mode = call.data.split('-')[1]
    if mode == 'yes':
        bot.send_message(user_id, 'Отлично! Приступим к заполнению заявки.☺️')
        handle_request_information(call.message, bot)
    elif mode == 'no':
        handlers.common.handle_menu(call.message, bot)


def handle_new_request(message, bot):
    user_id = str(message.chat.id)
    try:
        insurance_types = HttpClient.get('insurance_objects', user_id)['data']
        markup = types.InlineKeyboardMarkup()
        for type in insurance_types:
            markup.add(types.InlineKeyboardButton(type['name'], callback_data='object_type-{id}'.format(id=type['id'])))
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
    define_current_page(user_id)
    handlers.common.handle_requests(message, bot, False)


def handle_requests_history(message, bot):
    user_id = str(message.chat.id)
    define_current_page(user_id)
    handlers.common.handle_requests(message, bot, False)


def define_current_page(user_id):
    if user_id not in handlers.common.users:
        handlers.common.users[user_id] = {}

    handlers.common.users[user_id]['current_page'] = 0


# Задаем модератора и юзера (их chat_id)
moderator_chat_id = 1265630862  # Замените на chat_id модератора
user_chat_id = 840181920  # Замените на chat_id юзера


def start_chat(message, bot):
    if message.chat.id == moderator_chat_id or message.chat.id == user_chat_id:
        markup = types.ReplyKeyboardMarkup()
        markup.add("Свернуть", "Завершить чат")
        bot.send_message(moderator_chat_id, f"Запрос на чат от {user_chat_id}.")
        bot.send_message(user_chat_id, "Чат начат. Напишите свое сообщение:", reply_markup=markup)
    else:
        bot.send_message(message.chat.id, "У вас нет доступа к чату.")


is_chat = False


def relay_message(message, bot):
    if is_chat:
        if message.chat.id == moderator_chat_id:
            bot.send_message(user_chat_id, message.text)
        elif message.chat.id == user_chat_id:
            bot.send_message(moderator_chat_id, message.text)


def register_handlers_client(bot):
    bot.register_message_handler(handle_user_list_of_requests,
                                 func=lambda message: message.text == "Просмотр статуса заявок", pass_bot=True)
    bot.register_message_handler(handle_requests_history,
                                 func=lambda message: message.text == "Просмотр истории заявок", pass_bot=True)
    bot.register_message_handler(handle_new_request,
                                 func=lambda message: message.text == "Подача новой заявки", pass_bot=True)
    bot.register_message_handler(handle_approve_request,
                                 func=lambda message: False)
    bot.register_message_handler(handle_request_information,
                                 func=lambda message: False)
    bot.register_message_handler(start_chat,
                                 func=lambda message: message.text == "Связаться с модератором", pass_bot=True)
    """bot.register_message_handler(relay_message,
                                 func=lambda message: False, pass_bot=True)"""

    bot.register_callback_query_handler(callback_client_load_request,
                                        func=lambda call: re.search(r'^request', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_insured_objects, pass_bot=True,
                                        func=lambda call: re.search(r'^object_type', call.data))

    bot.register_callback_query_handler(callback_enter_request_info, pass_bot=True,
                                        func=lambda call: re.search(r'^enter_request_info', call.data))
