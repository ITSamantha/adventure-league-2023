import numpy as np


def get_light_metrics_by_photos(images):
    return list(map(np.mean, images))
