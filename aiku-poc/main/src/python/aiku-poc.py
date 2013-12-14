#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
reload(sys)
sys.setdefaultencoding('utf8')

import requests
import re
import json

#        {
#            "YhteystietojenTyyppi.nimi.fi": "Aikuiskoulutuksen verkkosivu",
#            "YhteystietoElementti.oid": "1.2.246.562.5.2013100908554227835194",
#            "YhteystietoElementti.kaytossa": "true",
#            "YhteystietojenTyyppi.oid": "1.2.246.562.5.2013100908554227741880",
#            "YhteystietojenTyyppi.nimi.sv": "Vuxenutbildningens webbsida",
#            "YhteystietoElementti.pakollinen": "false",
#            "YhteystietoArvo.arvoText": null,
#            "YhteystietoElementti.tyyppi": "Www",
#            "YhteystietoElementti.nimi": "Www-osoite",
#            "YhteystietoElementti.nimiSv": "Www-adress"
#        }

def get_aiku_url(org):
    for y in org["yhteystietoArvos"]:
        if y["YhteystietojenTyyppi.nimi.fi"] == "Aikuiskoulutuksen verkkosivu" and y["YhteystietoElementti.nimi"] == "Www-osoite":
            if y["YhteystietoArvo.arvoText"] is None:
                return ""
            else:
                return y["YhteystietoArvo.arvoText"]
    return None

def main(base_url):
    aiku = { "fi": [ [ "Oppilaitos", "Sijainti", "Oppilaitoksen kotisivut" ] ],
             "sv": [ [ "Läroanstalt", "Ort", "Läroanstaltens hemsidor" ] ] }
    session = requests.Session()
    r = session.get("{base_url}/organisaatio-service/rest/organisaatio/".format(base_url=base_url))
    if r.status_code not in [200, 201]:
        raise Exception("Organisaatiopalvelu ei vastannut")

    r_json = r.json()
    i = 0
    for branch_id in r_json:
        r = session.get("{base_url}/organisaatio-service/rest/organisaatio/{branch_id}".format(base_url=base_url, branch_id=branch_id))
        if r.status_code not in [200, 201]:
            raise Exception("Organisaatiopalvelu ei vastannut")

        org = r.json()

        #print json.dumps(org, indent=4)

        for lang in ["fi", "sv"]:

            aiku_url = get_aiku_url(org)

            if aiku_url is not None and "kielivalikoima_{lang}".format(lang=lang) in org["kieletUris"]:

                nimi = None
                if lang in org["nimi"]:
                    nimi = org["nimi"][lang]
                else:
                    nimi = org["nimi"]["fi"]

                r = session.get("{base_url}/koodisto-service/rest/json/kunta/koodi/{kunta}".format(base_url=base_url, kunta=org["kotipaikkaUri"]))
                if r.status_code not in [200, 201]:
                    raise Exception("Koodistopalvelu ei vastannut tai kuntaa ei löytynyt")

                kunta = r.json()
                kuntanimi = None
                for md in kunta["metadata"]:
                    if md["kieli"].upper() == lang.upper():
                        kuntanimi = md["nimi"]
    
                if kuntanimi is None:
                    raise Exception("Kunnalle ei löytynyt nimeä: {koodi} {org}".format(kunta=org["kotipaikkaUri"], koodi=kunta, org=json.dumps(org, indent=4)))
                if not aiku_url.startswith("http://") or aiku_url.startswith("https://"):
                    aiku_url = "http://" + aiku_url
                aiku[lang].append([nimi, kuntanimi, "<a href='{aiku_url}'>{aiku_url}</a>".format(aiku_url=aiku_url)])
                i += 1

                break

    with open("aiku-fi.json", "w") as f:
        f.write(json.dumps(aiku["fi"], indent=4))
    with open("aiku-sv.json", "w") as f:
        f.write(json.dumps(aiku["sv"], indent=4))

if __name__ == "__main__":
    main("http://itest-virkailija.oph.ware.fi")

