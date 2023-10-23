from flask import Flask
from dotenv import load_dotenv

app = Flask(__name__)
# app.config['MAX_CONTENT_LENGTH'] = 100 * 1024 * 1024

load_dotenv()

from app import views
