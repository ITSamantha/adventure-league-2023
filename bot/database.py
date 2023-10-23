from http_client.http_client import HttpClient


def get_user(user_id):
    user = HttpClient.get('get_user', user_id)
    return user


def get_requests_page(user_id, page=1):
    response = HttpClient.get('insurance_requests', user_id, json={
        'page': page
    })['data']
    return response


def get_user_roles(user_id):
    user_data = get_user(user_id)
    return list(map(lambda x: x['id'], user_data['data']['roles']))
