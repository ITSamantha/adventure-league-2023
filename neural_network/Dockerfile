FROM tiangolo/uwsgi-nginx:python3.10
ENV STATIC_URL /static
ENV STATIC_PATH /var/www/app/static
COPY ./requirements.txt /var/www/requirements.txt
RUN apt-get update && apt-get install ffmpeg libsm6 libxext6  -y
RUN pip install -r /var/www/requirements.txt
