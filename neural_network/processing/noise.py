import numpy as np


def get_noise_metrics_by_photos(images):
    return list(map(np.std, images))
