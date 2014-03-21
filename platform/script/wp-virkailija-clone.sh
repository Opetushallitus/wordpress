#!/bin/bash

# Kloonataan datat oph:n virkailijapuolen QA-wordpressistä toiseen.
#
# * Dumpataan kanta, ajetaan sille perlillä kaksi substituutiota, importataan
#   toisessa päässä
# * Käydään hakemassa wordpressin teemat, plugarit, liitteet jne (wp-content)
#   ja tunnetut .htaccess-tiedostot, ja roiskitaan paikalleen
# * Käydään editoimassa tiedostoa, jossa lukee, mikä ympäristö on kyseessä
#
# Ajamiseen tarvitset:
# 1. Sellaisen paikan, josta pääset ssh:lla (mieluiten ilman salasanaa)
#    sekä QA-ympäristöön että kohdeympäristöön
# 2. sudo-natsat molemmissa päissä
# 3. Tiedon siitä, millä hostilla mikäkin ympäristö on. Löytyy dokuista
#    ja konffeistakin, tää kysytään vain vahinkojen vähentämiseksi.

usage () { 
   cat <<- EOF
	Usage:
	$0 <destination environment> <destination host>

	Examples:
	$0 koulutus dokumenttikamera
	$0 test reppu
        $0 qa2 essee
EOF

   exit 1
}

if [ "x$#" != "x2" ]; then usage; fi

# set -x
workdir='oph-wp-transfer'

mkdir -p "$workdir"
cd "$workdir"

from='qa'
fromhost='wordpress2.qa.oph.ware.fi'
fromdb='virkailijaqa'
fromuser='virkailijawpuser'
frompw='GKuRpEUB'
frompath='/opt/www/wp_virkailijaqa/html/'
fromwpdir='/usr/share/wordpress-virkailija'
fromsubstitute1='http://wordpress2.qa.oph.ware.fi/'
fromsubstitute2='wordpress2.qa.oph.ware.fi'
fromtitle='QA'


if [ "x$1" = "xtest" ]
quit    then 
    if [ "x$2" != "xreppu" ]
	then
	echo "Testiympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='test'
    tohost='wp-reppu2.oph.ware.fi'
    todb='virkailijawpreppu'
    touser='virkailija'
    topw='wXkTRBiT'
    tosubstitute1='http://wp-reppu2.oph.ware.fi/'
    tosubstitute2='wp-reppu2.oph.ware.fi'
    topath='/opt/www/wp_virkailijareppu/html'
    towpdir='/usr/share/wordpress-virkailija'
    totitle='Testi (reppu)'
fi

if [ "x$1" = "xkoulutus" ]
    then 
    if [ "x$2" != "xdokumenttikamera" ]
	then
	echo "Koulutusympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='koulutus'
    tohost='wordpress2.train.oph.ware.fi'
    todb='virkailijawptrain'
    touser='virkailija'
    topw='hunrZRbve'
    tosubstitute1='http://wordpress2.train.oph.ware.fi/'
    tosubstitute2='wordpress2.train.oph.ware.fi'
    topath='/opt/www/virkailijawptrain/html'
    towpdir='/opt/www/virkailijawptrain/html/wp'
    totitle='Koulutus'
fi

if [ "x$1" = "xtuotanto" ]
    then 
    echo "Tuotantoa ei ole asennettu, mene pois"
    exit 1

    if [ "x$2" != "xlaskutikku" ]
	then
	echo "Tuotantoympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='tuotanto'
    tohost='opintopolkuwordpress2.prod.oph.ware.fi'
    todb='virkailijawordpress'
    touser='virkailija'
    topw='???'
    tosubstitute1='https://virkailija.opintopolku.fi/'
    tosubstitute2='virkailija.opintopolku.fi'
    topath='/opt/www/virkailijawordpress/html'
    towpdir='/usr/share/wordpress-virkailija'
    totitle=''
fi

if [ "x$1" = "xqa2" ]
    if [ "x$2" != "xessee" ]
	then
	echo "QA2 ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi
    
    to='qa2'
    tohost='wordpress4.qa.oph.ware.fi'
    todb='virkailijaqa'
    touser='virkailijawpuser'
    topw='BRudttyEYf44KU9K'
    tosubstitute1='https://wordpress4.qa.oph.ware.fi/'
    tosubstitute2='wordpress4.qa.oph.ware.fi'
    topath='/opt/www/wp_virkailijaqa/html/'
    towpdir='/usr/share/wordpress-virkailija'
    totitle=''
fi

if [ ! -n "$to" ]
    then
    echo "ei voittoa."
    usage
    exit 1
fi

nys=`date +%FT%T`
dumpfile=virkailijawp-"$nys"-from-"$from".sql
importfile=virkailijawp-"$nys"-to-"$to".sql

ssh $fromhost mysqldump --skip-extended-insert --add-drop-table \
  -u "$fromuser" -p"$frompw" "$fromdb" > "$dumpfile"

cat $dumpfile | perl -p \
-e "s|$fromsubstitute1|$tosubstitute1|g;" \
-e "s|$fromsubstitute2|$tosubstitute2|g;" \
> $importfile

ssh $tohost mysql -u "$touser" -p"$topw" "$todb" < "$importfile"

ssh $fromhost "(cd $fromwpdir && tar -cf - wp-content .htaccess)" | \
  ssh $tohost "(cd $towpdir   && sudo tar -xpf -)"


ssh $fromhost cat "$frompath"/.htaccess | \
ssh $tohost "sudo tee $topath/.htaccess >/dev/null"
ssh $tohost "sudo perl -pi -e 's/$fromtitle/$totitle/' /usr/share/wordpress/wp-content/themes/ophver3/functions.php"
ssh $tohost sudo /sbin/restorecon -R "$topath"
ssh $tohost sudo /sbin/restorecon -R /usr/share/wordpress*
