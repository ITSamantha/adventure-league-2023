from http_client.http_client import HttpClient


def get_user(user_id):
    user = HttpClient.get('get_user', user_id)
    return user


def get_requests_page(user_id, page=1):
    response = HttpClient.get('insurance_requests', user_id)['data']
    return response
