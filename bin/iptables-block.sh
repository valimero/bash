#!/bin/sh

USAGE="Usage: iptables-block.sh IP"

if test -z "$1"
then
    echo "Il me faut une adresse IP"
    echo $USAGE
    exit 255
fi
    
/sbin/iptables -A INPUT -p tcp --dest $1 -j DROP
/sbin/iptables -A INPUT -p udp --dest $1 -j DROP
/sbin/iptables -A OUTPUT -p tcp --dest $1 -j DROP
/sbin/iptables -A OUTPUT -p udp --dest $1 -j DROP
/sbin/iptables -A FORWARD -p tcp --dest $1 -j DROP
/sbin/iptables -A FORWARD -p udp --dest $1 -j DROP
