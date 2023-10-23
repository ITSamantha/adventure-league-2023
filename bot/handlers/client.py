import re

from telebot import types

import database
import handlers
import interface
from converters.converter import StringConverter
from enums.BotMessageException import BotMessageException
from dictionaries.request_statuses import request_statuses
from exceptions.ClientException import ClientException
from exceptions.ServerException import ServerException

from http_client.http_client import HttpClient

remove_keyboard = types.ReplyKeyboardRemove(selective=False)

user_photo_upload_stage = {}


def callback_client_load_request(call, bot):
    user_id = str(call.message.chat.id)

    current_request_id = int(handlers.common.split_callback_data(call.data))

    try:
        user = HttpClient.get('get_user', user_id)['data']
        request = database.get_request(user_id, current_request_id)['data']

        status_name = request['status']
        status_id = request['status_id']

        text = ["___Заявка №{request_id}___\n".format(request_id=request['id']),
                "___Статус___: {status_name}{status_char} \n\n".format(status_name=status_name,
                                                                       status_char=request_statuses[status_id]),
                "___Владелец___: {name}\n".format(name=user['name']),
                "___Тип страхуемого объекта___: {type} ".format(type=request['insurance_object_type_name']), ]

        message = ''.join(text)

        bot.send_message(call.message.chat.id, message, parse_mode=interface.PARSE_MODE)
        """if user['data']['roles'][0] == UserRole.MODERATOR.value:
            keyboard = types.InlineKeyboardMarkup()
        key_1 = types.InlineKeyboardButton(text=f'Одобрена{RequestStatus.OK.value}', callback_data='req_yes')
        key_2 = types.InlineKeyboardButton(text=f'На рассмотрении{RequestStatus.WAIT.value}',
                                           callback_data='req_wait')
        key_3 = types.InlineKeyboardButton(text=f'Отклонена{RequestStatus.NOT_OK.value}', callback_data='req_no')
        keyboard.add(key_1)
        keyboard.add(key_2)
        keyboard.add(key_3)
        bot.send_message(call.message.chat.id, 'Необходимо выбрать статус заявки:', reply_markup=keyboard)"""
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
        handle_request_information(call.message, bot)
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


def handle_approve_request(message, bot):
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
            'insurance_object_id': handlers.common.users[str(user_id)]['current_request']['insured_object_type_id']
        }
        global iofts
        iofts = HttpClient.post('insurance_object_file_types/get', user_id, json=json)['data']
        markup = types.ReplyKeyboardMarkup(True, True)
        markup.add("Попробовать", "Не требуется")
        bot.send_message(user_id, '\nВАЖНО. Фото для осмотра должны быть сделаны только с помощью телефона. '
                                  'Для корректной обработки фото Вам необходимо предоставить доступ к геолокации в приложении "Камера". Это можно сделать в разделе "Настройки" камеры.'
                                  '("Камера" -> "Настройки" -> "Сохранять место съемки"). \n\n'
                                  'Редактирование фотоматериалов объекта страхования строго запрещено. '
                                  'Разрешение фото должно быть не ниже 1600 x 1200 px. ', )
        bot.send_message(user_id,
                         "Давайте проверим, правильно ли у вас настроено устройство, чтобы загружать фото/видео материалы осмотра. Для этого мы предлагаем вам пройти небольшую процедуру проверки - сделайте перед осмотром тестовое фото и загрузите его следующим сообщением как документ. Если всё пройдёт успешно, мы выдадим вам полный список того, что необходимо будет зафиксировать для совершения акта осмотра.",
                         reply_markup=markup)
        user_photo_upload_stage[user_id] = 'asked'
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


def handle_user_list_of_requests(message, bot):
    user_id = str(message.chat.id)
    define_current_page(user_id)
    handlers.common.handle_requests(message, bot, False)


def handle_requests_history(message, bot):
    user_id = str(message.chat.id)
    define_current_page(user_id)
    handlers.common.handle_requests(message, bot, True)


def define_current_page(user_id):
    handlers.common.check_user_in_users(user_id)
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


def handle_test_photo_request(message, bot):
    user_id = str(message.chat.id)
    if user_id in user_photo_upload_stage:
        if user_photo_upload_stage[user_id] == 'asked':
            bot.send_message(message.chat.id, "Можете загружать фото следующим сообщением. Убедитесь, что вы "
                                              "загружаете его именно файлом, а не фотографией (отключите сжатие).",
                             reply_markup=interface.remove_keyboard)
            user_photo_upload_stage[user_id] = 'test'


def handle_photos_request(message, bot):
    pass


def add_file(message, bot):
    print(message)
    print(message.document.file_name)
    user_id = str(message.chat.id)

    bot.send_message(user_id, 'Фото отправлено. Ожидайте окончания обработки😌', reply_markup=interface.remove_keyboard)

    if user_id in user_photo_upload_stage:
        if user_photo_upload_stage[user_id] == 'test':
            user_photo_upload_stage[user_id] = 'pending'
            print('got file')  # only once because of statuses
            # todo send to backend
            # todo ask Diana

    file_name = message.document.file_name
    file_info = bot.get_file(message.document.file_id)

    print(file_info)


def handle_techical_help(message, bot):
    user_id = str(message.chat.id)

    bot.send_message(user_id,
                     'Уважаемый клиент!\n\nВы всегда можете связаться с одним из модераторов с помощью кнопки ___"Связаться с модератором"___ для решения вопроса по поводу подачи заявки.\n\n'
                     'Если вы не нашли ответ на интересующий вопрос, свяжитесь с сотрудником поддержки в [онлайн-чате](https://sovcombank.ru/help). '
                     'Также вы можете отправить нам свой отзыв или вопрос через [форум обратной связи](https://idea.sovcombank.ru/forum/).\n\n'
                     '___Телефон горячей линии (для звонков по России (бесплатно) ):___\n +8 800 100 00 06\n'
                     '___Телефон горячей линии (для звонков из-за рубежа (платно) ):___\n +7 495 988 00 00\n',
                     parse_mode=interface.PARSE_MODE)

    handlers.common.handle_menu(message, bot)


def register_handlers_client(bot):
    bot.register_message_handler(handle_user_list_of_requests,
                                 func=lambda message: message.text == "Заявки в обработке", pass_bot=True)
    bot.register_message_handler(handle_requests_history,
                                 func=lambda message: message.text == "Просмотр истории заявок", pass_bot=True)
    bot.register_message_handler(handle_new_request,
                                 func=lambda message: message.text == "Подача новой заявки", pass_bot=True)
    bot.register_message_handler(handle_approve_request,
                                 func=lambda message: False)
    bot.register_message_handler(start_chat,
                                 func=lambda message: message.text == "Связаться с модератором", pass_bot=True)
    """
@bot.register_message_handler(relay_message,
                             func=lambda message: False, pass_bot=True)
"""
    bot.register_message_handler(add_file, content_types=['document'],
                                 pass_bot=True)
    bot.register_message_handler(handle_test_photo_request, func=lambda message: message.text == "Попробовать",
                                 pass_bot=True)
    bot.register_message_handler(handle_photos_request, func=lambda message: message.text == "Не требуется",
                                 pass_bot=True)
    bot.register_message_handler(handle_techical_help, func=lambda message: message.text == "Техническая поддержка",
                                 pass_bot=True)

    bot.register_callback_query_handler(callback_client_load_request,
                                        func=lambda call: re.search(r'^request', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_insured_objects, pass_bot=True,
                                        func=lambda call: re.search(r'^object_type', call.data))
    bot.register_callback_query_handler(handle_request_information, pass_bot=True,
                                        func=lambda call: re.search(r'^request_information', call.data))
    bot.register_callback_query_handler(callback_enter_request_info, pass_bot=True,
                                        func=lambda call: re.search(r'^enter_request_info', call.data))
