#!/bin/bash

# Kloonataan datat oph:n tuotannon wordpressistä toiseen.
#
# * Dumpataan kanta, ajetaan sille perlillä kaksi substituutiota, importataan
#   toisessa päässä
# * Käydään hakemassa wordpressin teemat, plugarit, liitteet jne (wp-content)
#   ja tunnetut .htaccess-tiedostot, ja roiskitaan paikalleen
# * Käydään editoimassa tiedostoa, jossa lukee, mikä ympäristö on kyseessä
#
# Ajamiseen tarvitset:
# 1. Sellaisen paikan, josta pääset ssh:lla (mieluiten ilman salasanaa)
#    sekä tuotantoympäristöön että kohdeympäristöön
# 2. sudo-natsat molemmissa päissä
# 3. Tiedon siitä, millä hostilla mikäkin ympäristö on. Löytyy dokuista
#    ja konffeistakin, tää kysytään vain vahinkojen vähentämiseksi.

usage () { 
   cat <<- EOF
	Usage:
	$0 <destination environment> <destination host>

	Examples:
	$0 prod-standby laskutikku
EOF

   exit 1
}

if [ "x$#" != "x2" ]; then usage; fi

set -x
workdir='oph-wp-transfer'

mkdir -p "$workdir"
cd "$workdir"

from='tuotanto'
fromhost='opintopolkuwordpress2.prod.oph.ware.fi'
fromdb='opintopolkuwordpress'
fromuser='wpuser'
frompw='2nksYCzDCmJCEHeH'
fromsubstitute1="https://opintopolku.fi/"
fromsubstitute2="opintopolku.fi"
frompath="/opt/www/opintopolkuwordpress/html"
fromtitle=''


if [ "x$1" = "xprod-standby" ]
    then 
    if [ "x$2" != "xlaskutikku" ]
	then
	echo "$1-ympäristö ei sijaitse palvelimella $2. Tarkkana nyt!"
	exit 1
    fi

    to='prod-standby'
    tohost='opintopolkuwordpress1.prod.oph.ware.fi'
    todb='opintopolkuwordpress'
    touser='wpuser'
    topw='2nksYCzDCmJCEHeH'
    tosubstitute1='https://opintopolku.fi/'
    tosubstitute2='opintopolku.fi'
    topath='/opt/www/opintopolkuwordpress/html'
    totitle='Opintopolku'
    toblogname='Opintopolku'
    setreadonly='yes'
fi

if [ "x$tohost" = "x" ]; then echo en tunne ympäristöä "$2"; exit 1; fi

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
# ei päivitellä kun kloonataan.
# echo "update wp_options set option_value='$toblogname' where option_name = 'blogname';" | ssh $tohost mysql -u "$touser" -p"$topw" "$todb"

ssh $fromhost tar -cf - /usr/share/wordpress/wp-content \
    /usr/share/wordpress/.htaccess | \
ssh $tohost '(cd / && sudo tar -xpf -)'


ssh $fromhost cat "$frompath"/.htaccess | \
ssh $tohost "sudo tee $topath/.htaccess >/dev/null"

# ei kosketa titleen nyt. korjataan joskus, kun tarvii kloonata
# esim. QA:lle.
# ssh $tohost "sudo perl -pi -e 's/$fromtitle/$totitle/' /usr/share/wordpress/wp-content/themes/ophver3/functions.php"

ssh $tohost sudo /sbin/restorecon -R /usr/share/wordpress/

if [ "x$setreadonly" == "xyes" ]
    then
    ssh $tohost "cd $topath/wp/ && sudo php /opt/www/bin/wp-cli.phar --allow-root plugin activate code-freeze"

    else
    ssh $tohost "cd $topath/wp/ && sudo php /opt/www/bin/wp-cli.phar --allow-root plugin deactivate code-freeze"
fi
