import re

import telebot
from telebot import types

from converters.converter import StringConverter
from dictionaries.help import user_helps
from dictionaries.user_data import user_data, USER_DATA_LENGTH
from http_client.http_client import HttpClient
from validators.validator import Validator

users = {}

requests = [{'id': x, 'type': 'Транспортное средство', 'description': 'd', 'photos': None} for x in range(100)]

remove_keyboard = types.ReplyKeyboardRemove(selective=False)


def handle_start(message, bot):
    user_id = str(message.chat.id)
    bot.send_message(user_id, 'Здравствуйте, {last_name} {first_name}! Вас приветствует бот Совкомбанк Digital☺️\n'
                              'Я помогу Вам быстро и легко осуществить осмотр страхуемого имущества.'.format(
        first_name=(message.from_user.first_name if message.from_user.first_name else ''),
        last_name=(message.from_user.last_name if message.from_user.last_name else '')))

    id = str(1265630862)
    user = HttpClient.get('get_user', user_id)
    if user['data'] and user_id != id:
        bot.send_message(user_id, 'Вы уже зарегистированы в системе Совкомбанк Digital,'
                                  ' {name}!'
                                  '\nРады Вас видеть снова!☺️'.format(name=user['data']['name']))
        handle_menu(message, bot)
    else:
        markup = types.InlineKeyboardMarkup()
        registration = types.InlineKeyboardButton(text='Регистрация', callback_data='handle_registration')
        markup.add(registration)
        bot.send_message(user_id, 'Ух! Вы новичок! Необходимо пройти регистрацию☺️', reply_markup=markup)


def handle_registration(call, bot):
    """Регистрация пользователя в чате. Происходит при первичном запуске бота."""

    user_id = str(call.message.chat.id)

    if user_id not in users:
        users[user_id] = {}

    initial_data_step = 0

    bot.send_message(user_id, f"Введите {user_data[initial_data_step]['value']}:", reply_markup=remove_keyboard)
    bot.register_next_step_handler(call.message, get_text_user_data, initial_data_step, bot)


def approve_callback_registration(call, bot):
    mode = call.data.split('_')[1]
    user_id = str(call.message.chat.id)
    if mode == "yes":
        try:
            user = users[str(call.message.chat.id)]
            user['telegram_id'] = user_id
            request = HttpClient.post('register', str(call.message.chat.id), json=user)
            bot.send_message(call.message.chat.id,
                             "Отлично! Регистрация прошла успешно. Теперь можно приступать к работе!☺️")
            del users[user_id]
            users[user_id] = {}
        except Exception as e:
            print(e)
        handle_menu(call.message, bot)
    elif mode == "no":
        bot.send_message(call.message.chat.id, 'Окей! Попробуй еще раз☺️')
        handle_registration(call, bot)


def handle_menu(message, bot):
    user_id = message.chat.id
    markup = types.ReplyKeyboardMarkup(resize_keyboard=True, one_time_keyboard=True)
    if False:
        markup.add("Просмотр списка заявок", "Справка модератора")
    else:
        markup.add("Подача новой заявки", "Справка пользователя")
        markup.add("Просмотр истории заявок", "Просмотр статуса заявок")
    bot.send_message(user_id, "Выберите действие:", reply_markup=markup)


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
            bot.send_message(message.from_user.id, text, reply_markup=keyboard, parse_mode='Markdown')
        else:
            bot.send_message(message.from_user.id, f"Введите {user_data[type_id + 1]['value']}:")
            bot.register_next_step_handler(message, get_text_user_data, type_id + 1, bot)
    else:
        bot.send_message(message.from_user.id,
                         f'Что-то пошло не так, попробуйте ввести {user_data[type_id]["value"]} снова😢')
        bot.register_next_step_handler(message, get_text_user_data, type_id, bot)


def handle_help(message, bot):
    user_id = message.chat.id
    bot.send_message(user_id, "Доступны несколько разделов справки😌", reply_markup=remove_keyboard)
    user_id = message.chat.id
    keyboard = types.InlineKeyboardMarkup()
    # Тут разделение на модератора и пользователя
    for help in user_helps.keys():
        hp = types.InlineKeyboardButton(text=user_helps[help]['title'], parse_mode='Markdown',
                                        callback_data=f'helpuser_{help}')
        keyboard.add(hp)
    bot.send_message(user_id, "*Выберите нужный раздел:*", reply_markup=keyboard, parse_mode='Markdown')


def handle_help_button_pressed(call, bot):
    """
    general_photo_requirements_description = user_helps[GENERAL_PHOTO_REQUIREMENTS]["description"]
    help_category = call.data.split('_')[1]
    information = (f'{user_helps[help_category][HELP_TITLE_CATEGORY]}'
                   f'\n\n{user_helps[help_category][HELP_DESCRIPTION_CATEGORY]}'
                   f'{general_photo_requirements_description if help_category != GENERAL_PHOTO_REQUIREMENTS else ""}')
    bot.send_message(call.message.chat.id, information, parse_mode="Markdown")"""

    """
    user_id = message.chat.id
    bot.send_message(user_id, "Доступны несколько разделов справки😌")
    user_id = message.chat.id
    keyboard = types.InlineKeyboardMarkup()
    for help in helps_moderator.keys():
        hp = types.InlineKeyboardButton(text=f"{helps_moderator[help]['title']}", callback_data=f'help_{help}')
        keyboard.add(hp)
    bot.send_message(user_id, "Выберите нужный раздел:", reply_markup=keyboard)"""
    pass


def handle_page_inline_button_pressed(call, bot):
    """user_id = str(call.message.chat.id)
    if call.data == 'prev_page':
        # current_page -= 1
        send_history_page(user_id, bot)
    elif call.data == 'next_page':
        # current_page += 1
        send_history_page(user_id, bot)"""


def add_file(message, bot):
    print("YAY")
    file_name = message.document.file_name
    file_info = bot.get_file(message.document.file_id)
    downloaded_file = bot.download_file(file_info.file_path)
    # with open('documents' + file_name, 'wb') as new_file:
    # new_file.write(downloaded_file)


def command_default(m, bot):
    bot.send_message(m.chat.id, "Я не знаю данной команды😢\nПопробуйте другую...")


def register_handlers_common(bot):
    bot.register_message_handler(handle_start, commands=['start'], pass_bot=True)
    bot.register_message_handler(handle_help, func=lambda
        message: message.text == "Справка пользователя" or message.text == "Справка модератора", pass_bot=True)
    bot.register_message_handler(handle_menu, commands=['menu'], pass_bot=True)
    bot.register_message_handler(add_file, content_types=['document', 'photo', 'audio', 'video', 'voice'],
                                 pass_bot=True)
    bot.register_message_handler(command_default, content_types=['text'], pass_bot=True)
    bot.register_callback_query_handler(handle_help_button_pressed,
                                        func=lambda call: re.search(r'^help', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_page_inline_button_pressed,
                                        func=lambda call: re.search(r'page$', call.data), pass_bot=True)
    bot.register_callback_query_handler(handle_registration, func=lambda call: call.data == 'handle_registration',
                                        pass_bot=True)
    bot.register_callback_query_handler(approve_callback_registration,
                                        func=lambda call: re.search(r'^registration', call.data), pass_bot=True)
