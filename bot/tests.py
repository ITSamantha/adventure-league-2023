import os
from bootstrap.bootstrap import bootstrap

def main():
    bootstrap()
    print(os.getenv('API_SECRET_TOKEN'))


if __name__ == '__main__':
    main()
