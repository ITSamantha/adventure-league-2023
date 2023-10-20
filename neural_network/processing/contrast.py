import cv2
import numpy as np


def contrast_score(
    img: np.ndarray,
    blur_kernel: tuple[int, int] = (11, 11)
) -> np.float64:
    gray = cv2.cvtColor(img, cv2.COLOR_RGB2GRAY)
    blurred = cv2.GaussianBlur(gray, blur_kernel, 0)
    contrast = blurred.std()
    return contrast


def get_contrast_metrics_by_photos(images):
    return list(map(contrast_score, images))
