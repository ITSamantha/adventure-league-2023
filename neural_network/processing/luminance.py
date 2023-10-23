import cv2
import numpy as np


def gray_score(img: np.ndarray) -> float:
    img = cv2.cvtColor(img, cv2.COLOR_RGB2GRAY)
    rating = cv2.mean(img)[0] / 255.0
    return rating


def get_dark_metrics_by_photos(photos):
    return list(map(gray_score, photos))
