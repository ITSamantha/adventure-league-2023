import base64
import numpy as np
import cv2
import requests


def image_url_to_base64(url_or_path):
    try:
        if url_or_path.startswith('http') or url_or_path.startswith('https'):
            # It's a URL, download the image
            response = requests.get(url_or_path)
            if response.status_code == 200:
                image_content = response.content
                base64_image = base64.b64encode(image_content).decode('utf-8')
                return base64_image
            else:
                print("Failed to download the image. HTTP status code:", response.status_code)
                return None
        else:
            # It's a local file path, read the image from the file
            with open(url_or_path, 'rb') as file:
                image_content = file.read()
                base64_image = base64.b64encode(image_content).decode('utf-8')
                return base64_image
    except Exception as e:
        print("An error occurred:", str(e))
        return None


def base64_to_image(base64_str):
    image_data = base64.b64decode(base64_str)
    image_array = np.frombuffer(image_data, np.uint8)
    return cv2.imdecode(image_array, cv2.IMREAD_COLOR)


def resize_images(image_array, target_size=(400, 400)):
    resized_images = []
    for image in image_array:
        resized_image = cv2.resize(image, target_size)
        resized_images.append(resized_image)
    return resized_images
