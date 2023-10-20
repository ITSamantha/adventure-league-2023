from app import app
from flask import jsonify


@app.route('/')
def home():
    return jsonify({'hello': 'world'})
