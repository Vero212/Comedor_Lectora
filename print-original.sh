#!/bin/sh
echo "? Ejecutado print.sh con ticket $1 a las $(date)" >> /var/www/html/comedor/writable/logs/script_trace.log
sudo /usr/bin/php /var/www/html/comedor/imprimir.php $1 > /dev/usb/lp0
echo "??? Resultado del comando de impresión: $?" >> /var/www/html/comedor/writable/logs/script_trace.log

