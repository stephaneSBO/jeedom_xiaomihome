#!/bin/bash
touch /tmp/xiaomihome_dep
echo "Début de l'installation"

echo 0 > /tmp/xiaomihome_dep

sudo apt-get -y install python-pip python3-pip libffi-dev libssl-dev

echo 70 > /tmp/xiaomihome_dep
sudo pip install yeecli
sudo pip install mihome
sudo pip3 install python-mirobo
echo 80 > /tmp/xiaomihome_dep

sudo pip install future
sudo pip install pycrypto

echo 90 > /tmp/xiaomihome_dep

rm /tmp/xiaomihome_dep

echo "Fin de l'installation"
