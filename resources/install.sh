#!/bin/bash
touch /tmp/xiaomihome_dep
echo "Début de l'installation"

echo 0 > /tmp/xiaomihome_dep

sudo apt-get -y install python-pip libffi-dev libssl-dev

echo 70 > /tmp/xiaomihome_dep

if [ $(pip list | grep future | wc -l) -eq 0 ]; then 
    sudo pip install future
fi

echo 80 > /tmp/xiaomihome_dep
if [ $(pip list | grep pycrypto | wc -l) -eq 0 ]; then 
    sudo pip install pycrypto
fi

echo 90 > /tmp/xiaomihome_dep

rm /tmp/xiaomihome_dep

echo "Fin de l'installation"
