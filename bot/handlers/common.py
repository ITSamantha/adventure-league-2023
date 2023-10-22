import re
from telebot import types

import database
import handlers.client
import interface
from converters.converter import StringConverter
from dictionaries.help import user_helps
from dictionaries.user_data import user_data, USER_DATA_LENGTH
from enums.Mode import Mode
from enums.BotMessageException import BotMessageException
from enums.UserRole import UserRole
from exceptions import ClientException, ServerException
from http_client.http_client import HttpClient
from validators.validator import Validator

users = {}

requests = [{'id': x, 'type': 'Транспортное средство', 'description': 'd', 'photos': None} for x in range(100)]


def handle_start(message, bot):
    """Запуск бота для пользователя."""

    user_id = str(message.chat.id)

    first_name = (message.from_user.first_name if message.from_user.first_name else '')
    last_name = (message.from_user.last_name if message.from_user.last_name else '')
    welcome_message = ('Здравствуйте, ___{last_name} {first_name}___! Вас приветствует бот Совкомбанк Digital☺️\n'
                       'Я помогу Вам быстро и легко осуществить осмотр страхуемого имущества.'.format(
        first_name=first_name,
        last_name=last_name))
    bot.send_message(user_id, welcome_message, parse_mode=interface.PARSE_MODE)

    try:
        user = database.get_user(user_id)
        if user['data']:
            check_user_in_users(user_id)
            welcome_message = ('Вы уже зарегистированы в системе Совкомбанк Digital,'
                               ' ___{name}___!'
                               '\nРады Вас видеть снова!☺️'
                               '\nВы авторизованы как ___{role}___.'.format(name=user['data']['name'],
                                                                            role=user['data']['roles'][0]['name']))
            bot.send_message(user_id, welcome_message, parse_mode=interface.PARSE_MODE)
            handle_menu(message, bot)
        else:
            markup = types.InlineKeyboardMarkup()
            registration = types.InlineKeyboardButton(text='Регистрация', callback_data='handle_registration')
            markup.add(registration)
            bot.send_message(user_id, 'Ух! Вы новичок! Необходимо пройти регистрацию☺️', reply_markup=markup)
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


def handle_registration(call, bot):
    """Регистрация пользователя в чате. Происходит при первичном запуске бота."""

    user_id = str(call.message.chat.id)

    try:
        user = database.get_user(user_id)

        if user['data']:
            welcome_message = ('Вы уже зарегистированы в системе Совкомбанк Digital,'
                               ' ___{name}___!'
                               '\nРады Вас видеть снова!☺️'
                               '\nВы авторизованы как ___{role}___.'.format(name=user['data']['name'],
                                                                            role=user['data']['roles'][0]['name']))
            bot.send_message(user_id, welcome_message, parse_mode=interface.PARSE_MODE)
            handle_menu(call.message, bot)

        initial_data_step = 0
        bot.send_message(user_id, "Введите поле ___\"{value}\"___:".format(value=user_data[initial_data_step]['value']),
                         reply_markup=interface.remove_keyboard, parse_mode=interface.PARSE_MODE)

        bot.register_next_step_handler(call.message, get_text_user_data, initial_data_step, bot)
    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))


def approve_callback_registration(call, bot):
    """Подтверждение регистрации."""

    user_id = str(call.message.chat.id)

    mode = split_callback_data(call.data)

    if mode == Mode.YES.value:
        try:
            user = users[str(call.message.chat.id)]
            user['telegram_id'] = user_id
            response = HttpClient.post('register', user_id, json=user)
            bot.send_message(call.message.chat.id,
                             "Отлично! Регистрация прошла успешно. Теперь можно приступать к работе!☺️")
            del users[user_id]

            user = HttpClient.get('get_user', user_id)
            if user_id not in users:
                users[user_id] = {}
            users[user_id]['roles'] = user['data']['roles']

        except ClientException as e:
            bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
            print(str(e))
        except ServerException as e:
            bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
            print(str(e))
        except Exception as e:
            bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
            print(str(e))

        handle_menu(call.message, bot)
    elif mode == Mode.NO.value:
        bot.send_message(call.message.chat.id, 'Окей! Попробуй еще раз☺️')
        handle_registration(call, bot)
    else:
        bot.send_message(call.message.chat.id, "Я не знаю данной команды😢\nПопробуйте другую...")
        handle_menu(call.message, bot)


def handle_menu(message, bot):
    user_id = str(message.chat.id)
    markup = types.ReplyKeyboardMarkup(resize_keyboard=True)

    try:
        if user_id not in users:
            users[user_id] = {}


    except ClientException as e:
        bot.send_message(user_id, BotMessageException.CLIENT_EXCEPTION_MSG)
        print(str(e))
    except ServerException as e:
        bot.send_message(user_id, BotMessageException.SERVER_EXCEPTION_MSG)
        print(str(e))
    except Exception as e:
        bot.send_message(user_id, BotMessageException.OTHER_EXCEPTION_MSG)
        print(str(e))

    role = users[user_id]['roles'][0]['id']

    if role == UserRole.MODERATOR.value:
        markup.add("Просмотр списка заявок", "Справка модератора")
    elif role == UserRole.USER.value:
        markup.add("Подача новой заявки", "Справка пользователя")
        markup.add("Просмотр истории заявок", "Просмотр статуса заявок")
        markup.row("Связаться с модератором")
    elif role == UserRole.ADMIN.value:
        markup.add("Работа с модераторами", "Просмотр заявок")
    markup.row("Техническая поддержка")

    bot.send_message(user_id, "Выберите действие:", reply_markup=markup)


def handle_requests(message, bot, is_history=False):
    user_id = str(message.chat.id)
    current_page = handlers.common.users[user_id]['current_page']
    start_index = current_page * handlers.common.PAGE_SIZE
    end_index = start_index + handlers.common.PAGE_SIZE

    if is_history:
        user_requests = requests[start_index:end_index]
        empty_message = "История заявок пуста."
        message_template = "История заявок:\n"
    else:
        user_requests = requests[start_index:end_index]
        empty_message = "Нет текущих заявок на осмотр."
        message_template = "Статус текущих заявок на осмотр:\n"

    if user_requests:
        status_message = message_template
        markup = handlers.common.create_markup_for_request(user_requests)
        left = types.InlineKeyboardButton("⬅️", callback_data=f'prevpage_{"history" if is_history else "status"}')
        right = types.InlineKeyboardButton("➡️", callback_data=f'nextpage_{"history" if is_history else "status"}')
        if current_page > 0 and end_index < len(requests):
            markup.row(left, right)
        else:
            if end_index < len(requests):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, status_message, reply_markup=markup)
    else:
        bot.send_message(user_id, empty_message)

    handlers.common.handle_menu(message, bot)


def get_text_user_data(message: types.Message, type_id: int, bot):
    """Ввод текстовых данных пользователя."""

    user_id = str(message.chat.id)

    data = StringConverter.strip_str(message.text)
    is_digit = Validator.is_digit_in_str(data)

    if not is_digit:
        users[user_id][user_data[type_id]['type']] = StringConverter.capitalize_str_from_lower_case(data)
        bot.send_message(message.from_user.id, 'Супер, принято!')
        if type_id == USER_DATA_LENGTH - 1:
            keyboard = types.InlineKeyboardMarkup()
            key_yes = types.InlineKeyboardButton(text='Верно', callback_data='registration_yes')
            key_no = types.InlineKeyboardButton(text='Исправить', callback_data='registration_no')
            keyboard.row(key_yes, key_no)
            user = users[user_id]
            text = f'___Проверьте, пожалуйста, правильность введенных данных___\n\nФамилия: {user["surname"]}\nИмя: {user["name"]}\nОтчество: {user["patronymic"]}'
            bot.send_message(message.from_user.id, text, reply_markup=keyboard, parse_mode=PARSE_MODE)
        else:
            bot.send_message(message.from_user.id, f"Введите {user_data[type_id + 1]['value']}:")
            bot.register_next_step_handler(message, get_text_user_data, type_id + 1, bot)
    else:
        bot.send_message(message.from_user.id,
                         f'Что-то пошло не так, попробуйте ввести {user_data[type_id]["value"]} снова😢')
        bot.register_next_step_handler(message, get_text_user_data, type_id, bot)


def handle_help(message, bot):
    user_id = str(message.chat.id)
    bot.send_message(user_id, "Доступны несколько разделов справки😌", reply_markup=interface.remove_keyboard)
    keyboard = types.InlineKeyboardMarkup()
    # Тут разделение на модератора и пользователя
    if user_id not in users:
        users[user_id] = {}
    role = users[user_id]['roles'][0]['id']
    help_list = user_helps[role]
    for k, v in help_list.items():
        hp = types.InlineKeyboardButton(text=v['title'], parse_mode=PARSE_MODE,
                                        callback_data=f'help_{k}')
        keyboard.add(hp)
    bot.send_message(user_id, "*Выберите нужный раздел:*", reply_markup=keyboard, parse_mode=PARSE_MODE)


def handle_page_inline_button_pressed(call, bot):
    user_id = str(call.message.chat.id)
    # bot.delete_message(user_id, call.message.message_id)
    mode = call.data.split('_')[1]
    mode = True if mode == 'history' else False
    if 'prevpage' in call.data:
        users[user_id]['current_page'] -= 1
    elif 'nextpage' in call.data:
        users[user_id]['current_page'] += 1
    handlers.client.handle_requests(call.message, bot, mode)


"""
def add_file(message, bot):
    file_name = message.document.file_name
    file_info = bot.get_file(message.document.file_id)
    print(file_info.path)
"""


def command_default(message, bot):
    bot.send_message(message.chat.id, "Я не знаю данной команды😢\nПопробуйте другую...")


def register_handlers_common(bot):
    bot.register_message_handler(handle_start, commands=['start'], pass_bot=True)
    bot.register_message_handler(handle_help, func=lambda
        message: message.text == "Справка пользователя" or message.text == "Справка модератора", pass_bot=True)
    bot.register_message_handler(handle_menu, commands=['menu'], pass_bot=True)
    """bot.register_message_handler(add_file, content_types=['document', 'photo', 'audio', 'video', 'voice'],
                                 pass_bot=True)"""
    bot.register_message_handler(command_default, content_types=['text'], pass_bot=True)
    bot.register_callback_query_handler(handle_page_inline_button_pressed,
                                        func=lambda call: re.search(r'^help', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_page_inline_button_pressed,
                                        func=lambda call: 'page' in call.data, pass_bot=True)
    bot.register_callback_query_handler(handle_registration, func=lambda call: call.data == 'handle_registration',
                                        pass_bot=True)
    bot.register_callback_query_handler(approve_callback_registration,
                                        func=lambda call: re.search(r'^registration', call.data), pass_bot=True)


def check_user_in_users(user_id):
    if user_id not in users:
        users[user_id] = {}


def split_callback_data(callback_data, separator='_'):
    return callback_data.split(separator)[1]
