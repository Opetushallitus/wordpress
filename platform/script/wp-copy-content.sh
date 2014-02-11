#!/bin/bash

# Kloonataan wordpressin sisältö (kanta ja wp:n liitehakemisto) tuotannosta QA:lle.
#
# * Dumpataan kanta, ajetaan sille perlillä pari substituutiota, importataan
#   toisessa päässä
# * Käydään hakemassa wordpressin liitteet (wp-content/uploads) ja roiskitaan paikalleen
#
# Ajamiseen tarvitset:
# 1. Sellaisen paikan, josta pääset ssh:lla (mieluiten ilman salasanaa)
#    sekä QA-ympäristöön että tuotantoon
# 2. sudo-natsat molemmissa päissä
# 3. Tiedon siitä, millä hostilla mikäkin ympäristö on. Löytyy dokuista
#    ja konffeistakin, tää kysytään vain vahinkojen vähentämiseksi.

usage () { 
   cat <<- EOF
	Usage:
	$0 <destination environment> <destination host>

	Examples:
	$0 qa harppi
EOF

   exit 1
}

if [ "x$#" != "x2" ]; then usage; fi

# set -x
workdir='oph-wp-transfer'

mkdir -p "$workdir"
cd "$workdir"

from='tuotanto'
fromhost='opintopolkuwordpress1.prod.oph.ware.fi'
fromdb='opintopolkuwordpress'
fromuser='wpuser'
frompw='2nksYCzDCmJCEHeH'
fromsubstitute1='https://opintopolku.fi/'
fromsubstitute2='opintopolku.fi'
frompath='/opt/www/opintopolkuwordpress/html'
fromtitle=''
fromblogname="Opintopolku"


if [ "x$1" = "qa" ]
    then 
    if [ "x$2" != "xharppi" ]
	then
	echo "QA-ympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi


    to='qa'
    tohost='wordpress1.qa.oph.ware.fi'
    todb='wordpress'
    touser='wpophqa'
    topw='oy6FPvKqDj'
    topath='/opt/www/wp_ophqa/html'
    tosubstitute1='https://testi.opintopolku.fi/'
    tosubstitute2='testi.opintopolku.fi'
    totitle='QA'
    toblogname="Opintopolku WP $totitle"
fi



if [ "x$1" = "xdev" ]
    then 
    if [ "x$2" != "xluokka" ]
	then
	echo "dev-ympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi
    echo Tällä skriptillä ei vielä saa päivittää kuin QA:ta!
    exit 1

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
    echo Tällä skriptillä ei vielä saa päivittää kuin QA:ta!
    exit 1

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
    echo Tällä skriptillä ei vielä saa päivittää kuin QA:ta!
    exit 1

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

ssh $fromhost tar -cf - /usr/share/wordpress/wp-content/uploads | \
    ssh $tohost '(cd / && sudo tar -xpf -)'

ssh $tohost sudo /sbin/restorecon -R /usr/share/wordpress/
