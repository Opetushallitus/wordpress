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
fromhost='wordpress5.qa.oph.ware.fi'
fromdb='wpstudyinfo'
fromuser='wpstudyinfo'
frompw='ie8T3QXu'
frompath='/opt/www/wp_studyinfoqa/html/'
fromwpdir='/usr/share/wordpress-studyinfo'
fromsubstitute1='http://wordpress5.qa.oph.ware.fi/'
fromsubstitute2='wordpress5.qa.oph.ware.fi'
fromtitle='QA'


if [ "x$1" = "xtest" ]
    then 
    if [ "x$2" != "xreppu" ]
	then
	echo "Testiympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='test'
    tohost='wp-reppu3.oph.ware.fi'
    todb='studyinfowpreppu'
    touser='studyinfowp'
    topw='pQtYczPJ'
    tosubstitute1='http://wp-reppu3.oph.ware.fi/'
    tosubstitute2='wp-reppu3.oph.ware.fi'
    topath='/opt/www/wp_studyinforeppu/html'
    towpdir='/usr/share/wordpress-studyinfo'
    totitle='Testi (reppu)'
fi

if [ "x$1" = "xqa2" ]
    then
    if [ "x$2" != "xessee" ]
	then
	echo "QA2 ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi
    
    to='qa2'
    tohost='wordpress6.qa.oph.ware.fi'
    todb='wpstudyinfo'
    touser='wpstudyinfo'
    topw='4HDvx8h2'
    tosubstitute1='http://wordpress6.qa.oph.ware.fi/'
    tosubstitute2='wordpress6.qa.oph.ware.fi'
    topath='/opt/www/wp_studyinfoqa/html/'
    towpdir='/usr/share/wordpress-studyinfo'
    totitle=''
    setreadonly='yes'
fi

if [ ! -n "$to" ]
    then
    echo "ei voittoa."
    usage
    exit 1
fi

nys=`date +%FT%T`
dumpfile=studyinfowp-"$nys"-from-"$from".sql
importfile=studyinfowp-"$nys"-to-"$to".sql

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
if [ "x$setreadonly" == "xyes" ]
    then
    ssh $tohost "cd $topath/wp/ && sudo php wp_enable_plugins.php code-freeze/code-freeze.php"
fi
