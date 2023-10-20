import re

from telebot import types

import telebot

remove_keyboard = types.ReplyKeyboardRemove(selective=False)

insured_types = ["Транспортное средство", "Загородный дом"]


def callback_client_load_request(call, bot):
    # current_req = http_client[int(call.data.split('_')[1])]
    """bot.send_message(call.message.chat.id, f"Заявка №{current_req['id']}\n\n"
                                           f"Владелец: {user['surname']} {user['name']} {user['patronymic']}\n"
                                           f"Тип страхуемого объекта: {current_req['type']}\n"
                                           f"Описание: {current_req['description']}\n"
                                           f"Статус: Одобрена{statuses['OK']}\n"
                                           f"Фото: {current_req['photos']}\n")"""
    if True:
        keyboard = types.InlineKeyboardMarkup()  # наша клавиатура
        """key_1 = types.InlineKeyboardButton(text=f'Одобрена{statuses["OK"]}', callback_data='reg_yes')
        key_2 = types.InlineKeyboardButton(text=f'На рассмотрении{statuses["WAIT"]}', callback_data='reg_no')
        key_3 = types.InlineKeyboardButton(text=f'Отклонена{statuses["NOT OK"]}', callback_data='regs')
        keyboard.add(key_1)
        keyboard.add(key_2)
        keyboard.add(key_3)"""
        bot.send_message(call.message.chat.id, 'Необходимо выбрать статус заявки:', reply_markup=keyboard)


# Обработчик выбора типа страхования (inline кнопки)
def handle_insured_objects(call, bot):
    user_id = call.message.chat.id
    if call.data == 'object_type':
        user_data = [x for x in range(10)]
    elif call.data == 'object_type':
        user_data = [x for x in range(10)]

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
    user_id = message.chat.id
    # user_data['description'] = message.text
    # markup = types.ReplyKeyboardRemove(selective=False)
    keyboard = types.InlineKeyboardMarkup()  # наша клавиатура
    key_yes = types.InlineKeyboardButton(text='Приступить', callback_data='photo_yes')
    key_no = types.InlineKeyboardButton(text='Главное меню', callback_data='photo_no')
    keyboard.row(key_yes, key_no)
    bot.send_message(user_id,
                     "Сейчас будет происходить поэтапная загрузка фото.\nПожалуйста, обращайте внимание, что конкретно требуется запечатлеть на фото.",
                     reply_markup=keyboard)


def handle_new_request(message, bot):
    user_id = message.chat.id
    markup = types.InlineKeyboardMarkup()
    for obj in insured_types:
        markup.add(types.InlineKeyboardButton(obj, callback_data='object_type'))
    bot.send_message(user_id, "Выберите объект страхования:", reply_markup=markup)


def handle_user_list_of_requests(message, bot):
    """user_id = message.chat.id
    start_index = CURRENT_PAGE * PAGE_SIZE
    end_index = start_index + PAGE_SIZE
    user_requests = http_client[start_index:end_index]
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
        if CURRENT_PAGE > 0 and end_index < len(http_client):
            markup.row(left, right)
        else:
            if end_index < len(http_client):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, status_message, reply_markup=markup)
    else:
        bot.send_message(user_id, "Нет текущих заявок на осмотр.")
    handle_menu(message)"""


def handle_requests_history(message, bot):
    """user_id = message.chat.id
    if not http_client:
        bot.send_message(user_id, "Нет истории заявок.")
        handle_menu(message, bot)
    else:
        global current_page
        current_page = 0
        send_history_page(user_id, bot)
    print(http_client)"""


# Отправка страницы с историей заявок
def send_history_page(user_id, bot):
    """# Отправка страницы с историей заявок
    start_index = current_page * page_size
    end_index = start_index + page_size
    user_requests = http_client[start_index:end_index]
    if user_requests:
        history_message = "История заявок:\n"
        markup = types.InlineKeyboardMarkup()
        for i, request in enumerate(user_requests):
            markup.row(
                types.InlineKeyboardButton(
                    f"{request['id']}. {request['type']}, Статус: Одобрена{statuses['OK']}\n",
                    callback_data=f'request_{i}'))
        left = types.InlineKeyboardButton("⬅️", callback_data='prev_page')
        right = types.InlineKeyboardButton("➡️", callback_data='next_page')
        if current_page > 0 and end_index < len(http_client):
            markup.row(left, right)
        else:
            if end_index < len(http_client):
                markup.add(right)
            else:
                markup.add(left)
        bot.send_message(user_id, history_message, reply_markup=markup)
    else:
        bot.send_message(user_id, "История заявок пуста.")"""


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
