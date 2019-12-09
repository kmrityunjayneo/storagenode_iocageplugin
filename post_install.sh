#!/bin/sh -x

sa="/root/storagenode"
pdir="${0%/*}"


if [ ! -d "/usr/local/www/storagenode" ]; then
  mkdir -p "/usr/local/www/storagenode"
fi; cp -R "${pdir}/overlay/usr/local/www/storagenode" /usr/local/www/

if [ "${1}" = "standard" ]; then    # Only cp files when installing a standard-jail

  mv /usr/local/etc/nginx/nginx.conf /usr/local/etc/nginx/nginx.conf.old 
  cp "${sa}"/overlay/usr/local/etc/nginx/nginx.conf /usr/local/etc/nginx/nginx.conf

  mv /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf.old
  cp "${sa}"/overlay/usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

  cp "${sa}"/overlay/etc/motd /etc/motd

  if [ ! -d "/root/bin" ]; then
    mkdir -p "/root/bin"
  fi; cp "${sa}"/overlay/root/bin/storj-pwreset /root/bin/storj-pwreset

fi

find /usr/local/www/storagenode -type f -name ".htaccess" -depth -exec rm -f {} \;
find /usr/local/www/storagenode -type f -name ".empty" -depth -exec rm -f {} \;

chown -R www:www /usr/local/www/storagenode
chmod +x /root/bin/storj-pwreset

# Enable the service
sysrc -f /etc/rc.conf nginx_enable=YES
sysrc -f /etc/rc.conf php_fpm_enable=YES
sysrc -f /etc/rc.conf storj_enable="YES"

service nginx start  2>/dev/null
service php-fpm start  2>/dev/null

if [ "${1}" = "standard" ]; then
  v2srv_ip=$(ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p')

  colors () {                               # Define Some Colors for Messages
    grn=$'\e[1;32m'
    blu=$'\e[1;34m'
    cyn=$'\e[1;36m'
    end=$'\e[0m'
  }; colors

  end_report () {                 # read all about it!
    echo; echo; echo; echo
        echo " ${blu}Status Report: ${end}"; echo
        echo "    $(service nginx status)"
        echo "  $(service php-fpm status)"
    echo
        echo " ${cyn}StorageNode$ode${end}: ${grn}http://${v2srv_ip}${end}"
    echo
    echo; exit
  }; end_report

fi



