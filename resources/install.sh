#!/bin/bash
touch /tmp/xiaomihome_dep
echo "Début de l'installation"

echo 0 > /tmp/xiaomihome_dep
echo "Installation des dépendances apt"
sudo apt-get -y install python-pip libffi-dev libssl-dev python-cryptography

echo 60 > /tmp/xiaomihome_dep
if [ $(pip list | grep future | wc -l) -eq 0 ]; then
    echo "Installation du module future pour python"
    sudo pip install future
fi

echo 70 > /tmp/xiaomihome_dep
if [ $(pip list | grep pycrypto | wc -l) -eq 0 ]; then
    echo "Installation du module pycrypto pour python"
    sudo pip install pycrypto
fi
echo 80 > /tmp/xiaomihome_dep
if [ $(pip list | grep construct | wc -l) -eq 0 ]; then
    echo "Installation du module construct pour python"
    sudo pip install construct
fi

echo 90 > /tmp/xiaomihome_dep

rm /tmp/xiaomihome_dep

echo "Fin de l'installation"
