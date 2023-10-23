import re

from telebot import types

import database
import handlers
import interface
from dictionaries import request_statuses
from enums.BotMessageException import BotMessageException
from exceptions import ClientException, ServerException
from http_client.http_client import HttpClient
from dictionaries.request_statuses import request_statuses


def callback_moderator_load_request(call, bot):
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
                "___Тип страхуемого объекта___: {type} ".format(type=request['insurance_object_type_name']),
                ]

        # Здесь в цикле обрабатывать IOFT. Текстовые дописывать в text, фото выводить

        message = ''.join(text)

        bot.send_message(call.message.chat.id, message, parse_mode=interface.PARSE_MODE)
        keyboard = types.InlineKeyboardMarkup()
        key_1 = types.InlineKeyboardButton(
            text='Одобрена{request_status}'.format(request_status=request_statuses[1]), callback_data='req_yes')
        key_2 = types.InlineKeyboardButton(text='Нуждается в доработке{status}'.format(status=request_statuses[1]),
                                           callback_data='req_wait')
        key_3 = types.InlineKeyboardButton(text='Отклонена{status}'.format(status=request_statuses[1]),
                                           callback_data='req_no')
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


def register_handlers_moderator(bot):
    bot.register_callback_query_handler(callback_moderator_load_request,
                                        func=lambda call: re.search(r'^request', call.data), pass_bot=True)
