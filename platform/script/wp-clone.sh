#!/bin/bash

# Kloonataan datat oph:n QA-wordpressistä toiseen.
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
	$0 dev luokka
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
fromhost='wordpress1.qa.oph.ware.fi'
fromdb='wordpress'
fromuser='wpophqa'
frompw='oy6FPvKqDj'
frompath='/opt/www/wp_ophqa/html'
fromsubstitute1='https://testi.opintopolku.fi/'
fromsubstitute2='testi.opintopolku.fi'
fromtitle='QA'

if [ "x$1" = "xdev" ]
    then 
    if [ "x$2" != "xluokka" ]
	then
	echo "dev-ympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='dev'
    tohost='wordpress1.dev.oph.ware.fi'
    todb='opintopolkuwpdev'
    touser='wpuser'
    topw='cuDK9am2'
    tosubstitute1='http://wordpress1.dev.oph.ware.fi/'
    tosubstitute2='wordpress1.dev.oph.ware.fi'
    topath='/opt/www/opintopolkuwpdev/html'
    totitle='Kehitys (luokka)'
    toblogname="Opintopolku WP $totitle"
fi

if [ "x$1" = "xtest" ]
    then 
    if [ "x$2" != "xreppu" ]
	then
	echo "Testiympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='test'
    tohost='wp-reppu.oph.ware.fi'
    todb='opintopolkuwpreppu'
    touser='wpuser'
    topw='NPuiLNWX'  
#    tosubstitute1='http://wp-reppu.oph.ware.fi/'
#    tosubstitute2='wp-reppu.oph.ware.fi'
    tosubstitute1='https://test-oppija.oph.ware.fi/'
    tosubstitute2='test-oppija.oph.ware.fi'
    topath='/opt/www/wp_reppu/html'
    totitle='Testi (reppu)'
    toblogname="Opintopolku WP $totitle"
fi

if [ "x$1" = "xkoulutus" ]
    then 
    if [ "x$2" != "xdokumenttikamera" ]
	then
	echo "Koulutusympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='koulutus'
    tohost='wordpress1.train.oph.ware.fi'
    todb='opintopolkuwptrain'
    touser='wpuser'
    topw='dvofmiFWuT'
    tosubstitute1='http://wordpress1.train.oph.ware.fi/'
    tosubstitute2='wordpress1.train.oph.ware.fi'
    topath='/opt/www/opintopolkuwptrain/html'
    totitle='Koulutus'
    toblogname="Opintopolku WP $totitle"
fi

if [ "x$1" = "xtuotanto" ]
    then 
    if [ "x$2" != "xlaskutikku" ]
	then
	echo "Tuotantoympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='tuotanto'
    tohost='opintopolkuwordpress1.prod.oph.ware.fi'
    todb='opintopolkuwordpress'
    touser='wpuser'
    topw='2nksYCzDCmJCEHeH'
    tosubstitute1='https://opintopolku.fi/'
    tosubstitute2='opintopolku.fi'
    topath='/opt/www/opintopolkuwordpress/html'
    totitle=''
    toblogname="Opintopolku"
fi

if [ "x$1" = "xqa2" ]
    then
    if [ "x$2" != "xessee" ]
	then
	echo "QA2 ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='qa2'
    tohost='wordpress3.qa.oph.ware.fi'
    todb='wordpress'
    touser='wpophqa'
    topw='rFxChkbXv7BadH8L'
    tosubstitute1="http://wordpress3.qa.oph.ware.fi/"
    tosubstitute2="wordpress3.qa.oph.ware.fi"
    topath="/opt/www/wp_ophqa/html"
    totitle='QA'
    toblogname="Opintopolku QA"
    setreadonly='yes'
fi

if [ ! -n "$to" ]
    then
    echo "ei voittoa."
    usage
    exit 1
fi

nys=`date +%FT%T`
dumpfile=opintopolkuwp-"$nys"-from-"$from".sql
importfile=opintopolkuwp-"$nys"-to-"$to".sql

ssh $fromhost mysqldump --skip-extended-insert --add-drop-table \
  -u "$fromuser" -p"$frompw" "$fromdb" > "$dumpfile"

cat $dumpfile | perl -p \
-e "s|$fromsubstitute1|$tosubstitute1|g;" \
-e "s|$fromsubstitute2|$tosubstitute2|g;" \
> $importfile

# runttaa kanta länään
ssh $tohost mysql -u "$touser" -p"$topw" "$todb" < "$importfile"
# päivitä saitin nimi kannasta
echo "update wp_options set option_value='$toblogname' where option_name = 'blogname';" | ssh $tohost mysql -u "$touser" -p"$topw" "$todb"

ssh $fromhost tar -cf - /usr/share/wordpress/wp-content \
    /usr/share/wordpress/.htaccess | \
ssh $tohost '(cd / && sudo tar -xpf -)'


ssh $fromhost cat "$frompath"/.htaccess | \
ssh $tohost "sudo tee $topath/.htaccess >/dev/null"
ssh $tohost "sudo perl -pi -e 's/$fromtitle/$totitle/' /usr/share/wordpress/wp-content/themes/ophver3/functions.php"
ssh $tohost sudo /sbin/restorecon -R /usr/share/wordpress/

if [ "x$setreadonly" == "xyes" ]
    then
    ssh $tohost "cd $topath/wp/ && php wp_enable_plugins.php code-freeze/code-freeze.php"
fi
