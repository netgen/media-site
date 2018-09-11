#!/bin/sh

if [ -z "$1" ] || [ -z "$2" ]; then
  echo 'Please provide SSH login data and remote path to website!'
  echo 'USAGE:' $0 'user@host ~/remote/path/'
  echo ''
  exit 0
fi


SSH_LOGIN=$1
REMOTE_PATH=$2

DBSYNC_FOLDER='web/var/dbsync'
DATABASE_NAME='db.sql'
STORAGE_FOLDER='web/var/site/storage'

mkdir -p $DBSYNC_FOLDER
mkdir -p $STORAGE_FOLDER


echo "REMOTE ========================================="


ssh -T $SSH_LOGIN <<-ENDSSH

  cd $REMOTE_PATH

  php bin/console ngsite:database:dump $DBSYNC_FOLDER/$DATABASE_NAME

ENDSSH


echo "SYNC DOWN DATABASE ============================="


rsync -av "$SSH_LOGIN:$REMOTE_PATH/$DBSYNC_FOLDER/" $DBSYNC_FOLDER/
php bin/console doctrine:database:import $DBSYNC_FOLDER/$DATABASE_NAME


echo "SYNC DOWN VAR =================================="


rsync -av --delete "$SSH_LOGIN:$REMOTE_PATH/$STORAGE_FOLDER/" $STORAGE_FOLDER/


php bin/console cache:clear
