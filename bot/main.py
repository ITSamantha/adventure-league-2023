import os
import re

import telebot

from bootstrap.bootstrap import bootstrap
from handlers.common import register_handlers_common
from handlers.client import register_handlers_client
from handlers.admin import register_handlers_admin
from handlers.moderator import register_handlers_moderator

bootstrap()


def register_handlers_bot(_bot):
    register_handlers_client(_bot)
    register_handlers_moderator(_bot)
    register_handlers_admin(_bot)
    register_handlers_common(_bot)


bot = telebot.TeleBot(os.getenv('TELEGRAM_BOT_TOKEN'), threaded=True)
register_handlers_bot(bot)

bot.polling()
