#!/bin/bash
#!/bin/bash
TICKETID="$1"
/usr/bin/php /var/www/html/comedor/imprimir.php "$TICKETID" > /dev/usb/lp0
echo "?? Ticket completo enviado al puerto USB para el ID: $TICKETID a las $(date)" >> /var/www/html/comedor/writable/logs/script_trace.log

#echo -e "Texto de prueba\n\nGracias por su compra\n" > /dev/usb/lp0
#echo "?? Ticket de prueba enviado al puerto USB a las $(date)" >> /var/www/html/comedor/writable/logs/script_trace.log