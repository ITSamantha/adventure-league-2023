from app import app
from flask import jsonify
from flask import request
import telebot

bot = telebot.TeleBot(os.getenv('6415536990:AAGdJGicyXGsonzdd96I4ktvE_8XeQ5dqhk'), threaded=True)

@app.route('/')
def home():
   return jsonify({'hello': 'world'})

@app.route('/status_changed', methods=['POST'])
def status_changed():
    bot.send_message(request.json['user_id'], 'Статус одной из ваших заявок только что изменился')
