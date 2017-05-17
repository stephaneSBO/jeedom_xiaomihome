#!/bin/bash
touch /tmp/xiaomihome_dep
echo "Début de l'installation"

echo 0 > /tmp/xiaomihome_dep

sudo apt-get -y install python-pip libffi-dev libssl-dev

echo 60 > /tmp/xiaomihome_dep
sudo pip install future --force-reinstall

echo 70 > /tmp/xiaomihome_dep
sudo pip install pycrypto --force-reinstall

echo 80 > /tmp/xiaomihome_dep
sudo pip install construct --force-reinstall

echo 90 > /tmp/xiaomihome_dep

rm /tmp/xiaomihome_dep

echo "Fin de l'installation"
