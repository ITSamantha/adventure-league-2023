import os

import joblib

from app import app
from flask import jsonify, request

from model.model import create_dataframe_from_photos
from utils.images import base64_to_image
from os.path import join, dirname, realpath

#STATIC_PATH = os.getenv('STATIC_PATH')

@app.route('/upload_images', methods=['POST'])
def upload_images():
    try:
        data = request.get_json()
        if 'images' in data and isinstance(data['images'], dict):
            try:
                images = []
                images_containers = []
                images_ids = []
                for container_id, inner_dict in data['images'].items():
                    for image_id, base64_code in inner_dict.items():
                        images.append(base64_code)
                        images_containers.append(container_id)
                        images_ids.append(image_id)
                images = list(map(base64_to_image, images))
                df = create_dataframe_from_photos(images)
                lr = joblib.load('/app/app/logistic_regression_model.pkl')
                predicted_probs = lr.predict_proba(df).tolist()
                containers = {container: 1 for container in set(images_containers)}
                images_result = {image_id: 1 for image_id in set(images_ids)}
                for index, predicted_probs in enumerate(predicted_probs):
                    if predicted_probs[0] > 0.75:
                        containers[images_containers[index]] = 0
                    if predicted_probs[0] > 0.65:
                        images_result[images_ids[index]] = 0

                return jsonify({"iras": containers, "images": images_result})
            except Exception as e:
                print(e)
                return jsonify({"error": str(e)}), 400

        else:
            return jsonify({"error": "Invalid data format. 'images' must be an array of base64 encoded images."}), 400
    except Exception as e:
        return jsonify({"error": str(e)}), 500


#@app.route('/')
#def home():
#    lr = joblib.load(STATIC_PATH + 'logistic_regression_model.pkl')
#    return jsonify({'hello': 'world'})


#app.run(debug=True)