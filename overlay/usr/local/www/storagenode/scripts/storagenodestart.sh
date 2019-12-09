#!/bin/bash
# This script starts storagenode 
SYNOPKG_PKGNAME="StorJ"
LOG="/var/log/$SYNOPKG_PKGNAME"
echo `date` "Storagenode is starting" >> $LOG
#DESTDIR=/volume1/@appstore/StorJ/scripts/docker-compose.yml
ERRLOG="$LOG"_ERR
rm -f "$ERRLOG"
#if [ -f "$DESTDIR" ]
#then
#	docker-compose -f docker-compose.yml
#fi
#${DOCKER} run -d --restart unless-stopped -p "$2":28967 -p 14002:14002 -e WALLET="$3" -e EMAIL="dummy@gmail.com" -e ADDRESS="68.55.169.100:${2}" -e BANDWIDTH="${5}TB" -e STORAGE="${4}GB" --mount type=bind,source="${6}",destination=/app/identity --mount type=bind,source="/share/CACHEDEV1_DATA/storj/",destination=/app/config --name ${QPKG_NAME} storjlabs/storagenode:latest 2>&1
#docker run -d --restart unless-stopped -p "$1":28967 -p 14002:14002 -e WALLET="0x9318Ee545bC1b05859ac714682684A4F08EDF762" -e EMAIL="partnerships@storj.io" -e ADDRESS="173.225.183.160:28967" -e BANDWIDTH="20TB" -e STORAGE="2TB" --mount type=bind,source="/volume1/homes/Identity/storagenode",destination=/app/identity --mount type=bind,source="/volume1/StorJ",destination=/app/config --name storagenode storjlabs/storagenode:latest
#bash storagenodestart.sh "28967" "0x9318Ee545bC1b05859ac714682684A4F08EDF762" "partnerships@storj.io" "20" "40" "/volume1/homes/Identity/storagenode" "/volume1/StorJ"
if [[ "$(docker images -q storjlabs/storagenode:latest 2> /dev/null)" == "" ]];  then
  # download the docker image
  docker pull storjlabs/storagenode:latest 2>&1
  #echo `date` " Docker image is already there " 
  echo `date`  " docker image is getting downloaded" >>$LOG
fi
#Checking if docker image exists and starting the same
if [[  "$(docker images -q storjlabs/storagenode:latest 2> /dev/null)" != "" ]]; then
  # download the docker image
  #docker pull storjlabs/storagenode:beta
  echo `date` " Starting Storagenode " >>$LOG
  docker run -d --restart unless-stopped -p "$1":28967 -p 14002:14002 -e WALLET="$2" -e EMAIL="$3" -e ADDRESS="173.225.183.160:$1" -e BANDWIDTH="$4TB" -e STORAGE="$5TB" --mount type=bind,source="$6",destination=/app/identity --mount type=bind,source="$7",destination=/app/config --name storagenode storjlabs/storagenode:latest 2>&1
fi
#Logging
if [ -s "$ERRLOG" ]; then
  echo `date` "----------------------------------------------------"
  cat $ERRLOG
  echo `date` "----------------------------------------------------"
#  # Add infor to the log to be displayed by the Catalog Manager
  echo `date` "Adding info to the  log file"
  sed -i 's/$/<br>/' "$ERRLOG"
  cat $ERRLOG >> $SYNOPKG_TEMP_LOGFILE
  exit 1
fi