#!/bin/bash

ECHO=/bin/echo;
SAVEDIR=/media/cameleon/SAUVEGARDE/rsync60

if !(test -e $SAVEDIR); then { $ECHO "Repertoire $SAVEDIR inexistant"; exit;} fi

/usr/bin/rsync --delete --exclude=.thumbnails --exclude=Vidéos --exclude=.local/share/Trash/ --exclude=.VirtualBox --exclude=Téléchargements --exclude=Images --exclude=Musique --exclude=tmp --exclude=.android -alHvzcpog --progress /home/cameleon/ $SAVEDIR

