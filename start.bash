#!/bin/bash

sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH

sudo ufw allow 80
sudo ufw allow 443

sudo ufw enable




