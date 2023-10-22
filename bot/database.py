from http_client.http_client import HttpClient


def get_user(user_id):
    user = HttpClient.get('get_user', user_id)
    return user

