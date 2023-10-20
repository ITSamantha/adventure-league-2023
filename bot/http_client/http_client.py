import os
from typing import Any

import requests

from enums.ResponseStatus import ResponseStatus
from exceptions.ClientException import ClientException
from exceptions.ServerException import ServerException


class HttpClient:

    @staticmethod
    def get(uri: str, telegram_id: str, data=None, json=None):
        response = requests.get(os.getenv('API_PATH') + uri, data=data,
                                headers={"Content-Type": "application/json", 'Api-Token': os.getenv('API_SECRET_TOKEN'),
                                         'Telegram-Id': telegram_id, "Accept": "application/json"}, json=json)
        if response.status_code != ResponseStatus.OK.value[0]:
            response_json = response.json()
            if response.status_code >= ResponseStatus.SERVER_ERROR.value[0]:
                raise ServerException(f'ServerException occured.{response_json["message"]}')
            elif response.status_code >= ResponseStatus.CLIENT_ERROR.value[0]:
                raise ClientException(f'ClientException occured.{response_json["message"]}')
        return response.json()

    @staticmethod
    def post(uri: str, telegram_id: str, data: Any = None, json: dict = None):
        response = requests.post(os.getenv('API_PATH') + uri, data=data,
                                 headers={"Content-Type": "application/json", "Accept": "application/json",
                                          'Api-Token': os.getenv('API_SECRET_TOKEN'), 'Telegram-Id': telegram_id
                                          }, json=json)

        if response.status_code != ResponseStatus.OK.value[0]:
            response_json = response.json()
            if response.status_code >= ResponseStatus.SERVER_ERROR.value[0]:
                raise ServerException(f'ServerException occured.{response_json["message"]}')
            elif response.status_code >= ResponseStatus.CLIENT_ERROR.value[0]:
                raise ClientException(f'ClientException occured.{response_json["message"]}')
        return response.json()
