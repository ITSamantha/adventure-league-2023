import os

import cv2
import joblib
import numpy as np
import pandas as pd

from processing.blur import get_blur_metrics_by_photos
from processing.contrast import get_contrast_metrics_by_photos
from utils.images import resize_images
from processing.light import get_light_metrics_by_photos
from processing.luminance import get_dark_metrics_by_photos
from processing.noise import get_noise_metrics_by_photos

from sklearn.linear_model import LogisticRegression

dataset_dir = os.getenv('DATASET_DIR')


def init_photo_data(x):
    return {
        'width': x.shape[0],
        'height': x.shape[1]
    }


def apply_random_transformation(image):
    random_transform = np.random.choice(['blur', 'noise', 'lighten', 'darken'])

    if random_transform == 'blur':
        image = cv2.GaussianBlur(image, (25, 25), 0)  # You can adjust the kernel size

    elif random_transform == 'noise':
        noise = np.random.normal(25, 30, image.shape).astype(np.uint8)  # You can adjust the noise level
        image = cv2.add(image, noise)

    elif random_transform == 'lighten':
        alpha = np.random.uniform(2.0, 3.5)  # You can adjust the alpha value
        image = cv2.convertScaleAbs(image, alpha=alpha, beta=0)

    elif random_transform == 'darken':
        beta = np.random.randint(-130, -110)  # You can adjust the beta value
        image = cv2.convertScaleAbs(image, alpha=1.0, beta=beta)

    return image


def train():
    images = []
    images_bad = []
    for index, filename in enumerate(os.listdir(dataset_dir)):
        if index > 100:
            break
        if filename.endswith('.jpg'):
            image_path = os.path.join(dataset_dir, filename)
            image = cv2.imread(image_path, cv2.IMREAD_COLOR)
            images.append(image)
            image_bad = apply_random_transformation(image)
            images_bad.append(image_bad)

    responses = [1 for _ in images] + [0 for _ in images_bad]
    images = images + images_bad

    data = pd.DataFrame(list(map(init_photo_data, images)))
    images = resize_images(images)
    data['blur'] = get_blur_metrics_by_photos(images)
    data['light'] = get_light_metrics_by_photos(images)
    data['contrast'] = get_contrast_metrics_by_photos(images)
    data['noise'] = get_noise_metrics_by_photos(images)
    data['darkness'] = get_dark_metrics_by_photos(images)

    lr = LogisticRegression()
    lr.fit(data, responses)
    model_filename = 'logistic_regression_model.pkl'
    joblib.dump(lr, model_filename)

    print(data)

