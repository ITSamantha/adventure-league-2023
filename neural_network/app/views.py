import joblib

from app import app
from flask import jsonify, request

from model.model import create_dataframe_from_photos
from utils.images import base64_to_image
from os.path import join, dirname, realpath

STATIC_PATH = '/app/static/'



@app.route('/upload_images', methods=['POST'])
def upload_images():
    try:
        data = request.get_json()
        if 'images' in data and isinstance(data['images'], list):
            try:
                images = list(map(base64_to_image, data['images']))
                df = create_dataframe_from_photos(images)
                lr = joblib.load(STATIC_PATH + 'logistic_regression_model.pkl')
                return jsonify({"result": lr.predict_proba(df).tolist()})
            except Exception as e:
                print(e)
                return jsonify({"error": str(e)}), 400

            return jsonify({"message": "Images uploaded successfully"}), 200
        else:
            return jsonify({"error": "Invalid data format. 'images' must be an array of base64 encoded images."}), 400
    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route('/')
def home():
    lr = joblib.load(STATIC_PATH + 'logistic_regression_model.pkl')
    return jsonify({'hello': 'world'})


if __name__ == '__main__':
    app.run(debug=True)
