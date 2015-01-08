#!/bin/sh

process_panicmail () {
# de-armor the mail first, strip away the headers and such
/usr/bin/perl -e 'while (<>) { last if (/ENCRYPTED FREEBSD PANIC DATA STARTS HERE/) };
    while (<>) { exit 0 if (/ENCRYPTED FREEBSD PANIC DATA ENDS HERE/); print };' < $1 |
    /usr/bin/perl -ne 'print "$1\n" if (/\|([^|]+)\|/)' |
    /usr/bin/sed -e 's/=3D/=/g; s/[[:space:]]//' > $1.stripped

# decrypt and remove stripped file
/usr/local/bin/pkesh dec /usr/local/etc/panicmail.key $1.stripped $1.txt
rm $1.stripped

HTTP_ROOT=/usr/local/www/panics
PANICDB=$HTTP_ROOT/panics.db

if [ ! -f $PANICDB ]; then
	/usr/local/bin/sqlite3 $PANICDB "create table panics (hostname, osrev, panicstr, date, epocdate, url);";
	chown www:www $PANICDB;
fi

if [ -z $1 ]; then
	exit 1
elif [ ! -f $1 ]; then
	exit 1
fi


HOSTNAME=`grep Hostname $1.txt | awk -F": " '{print $2}'`
VERSION=`grep "Version String" $1.txt | awk -F": " '{print $2}' | awk '{print $2" "$4}'`
PANICSTR=`grep "Panic String" $1.txt | awk -F": " '{print $2}'`
PANICDATE=`grep Dumptime $1.txt | awk -F": " '{print $2}'`
EPOCDATE=`date -j -f "%a %b %d %T %Z %Y" "${PANICDATE}" "+%s"`
PANICDIR=/panictext/`date -j -f "%a %b %d %T %Z %Y" "${PANICDATE}" "+%Y-%m/%d"`

URL=${PANICDIR}/${HOSTNAME}.${EPOCDATE}.txt
if [ ! -d ${HTTP_ROOT}${PANICDIR} ]; then
	mkdir -p ${HTTP_ROOT}${PANICDIR};
fi

mv $1.txt ${HTTP_ROOT}${URL}
/usr/local/bin/sqlite3 $PANICDB "insert into panics (hostname, osrev, panicstr, date, epocdate, url) \
	values ('$HOSTNAME', '$VERSION', '$PANICSTR', '$PANICDATE', '$EPOCDATE', '$URL');"
}

if [ -f /var/panicmail/msg.* ]; then
	for file in /var/panicmail/msg.*; do
		process_panicmail $file;
		rm $file;
	done
fi
