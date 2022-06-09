#!/usr/bin/env bash
#
# =============================================================================
#title:         runtests.sh
#description:   Führt die Tests der Softwate aus
#author:        pfroch <patrick.froch@easySolutionsIT.de>
#date:          20220503
#version:       1.1.0
#usage:         runtests.sh
# =============================================================================
#


## Ausgabe
function myecho() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\e[1;96m\n================================================================================"
        echo -e "${1}"
        echo -e "--------------------------------------------------------------------------------\n\e[0m"
    fi
}

function myinfo() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\e[0;37m\n================================================================================"
        echo -e "${1}"
        echo -e "--------------------------------------------------------------------------------\n\e[0m"
    fi
}

function myerror() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\n\e[1;91m================================================================================\e[0m"
        echo -e "\e[0;101m\u2717 ${1}\e[0m"
        echo -e "\e[1;91m--------------------------------------------------------------------------------\e[0m"
    else
        echo -e "\e[0;101m\u2717 ${1}\e[0m"
    fi

    if [ "${EXIT_ON_ERORR}" == "TRUE" ]
    then
        exit 124;
    fi
}

function myshortecho() {
    if [ "${VERBOSE}" != "TRUE" ]
    then
        echo -e "\e[0;92m\u2713 ${1}\e[0m"
    fi
}


##
# Header
#
echo -e "\e[1;96m\n================================================================================"
echo -e "e@sy Solutions IT - Test Suite by Patrick Froch - Version: 1.0.1"
echo -e "--------------------------------------------------------------------------------\n\e[0m"


##
# Parameters
##
while [ $# -gt 0 ]
do
    case ${1} in
    -v|--verbose)
        VERBOSE="TRUE"
        #shift  # Kein shift, da kein Wert übergeben wird!
        ;;

    *)          # unknown option
        myerror "Parameter [${1}] unbekannt!"
        #shift  # Kein shift, da kein Wert übergeben wird!
        ;;
    esac
    shift
done


## Variablen
EXIT_ON_ERORR="TRUE"
error=0
tmperr=0
configFolder='./build'
toolFolder="${configFolder}/tools"
classesFolder='./Classes'
codeStandard='PSR12'

## Eintragung der Abhängigkeiten prüfen
if [ -f /home/pfroch/bin/checkdependencies ]
then
    /home/pfroch/bin/checkdependencies
    tmperr=$?

    if [ ${tmperr} -gt 1 ]
        then
            error=${tmperr}
            myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
        else
           myshortecho "Alle Abhängigkeiten vorhanden"
        fi
fi


## phpcbf
if [ -f ${toolFolder}/phpcbf ]
then
    myecho "Verbessern des Code-Styles"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcbf --colors --standard=${codeStandard} ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcbf --colors --standard=${codeStandard} ${classesFolder} &>/dev/null
    fi
else
    myinfo "Verbessern des Code-Styles ausgelassen. PHP Code Beautifier and Fixer (phpcbf) nicht vorhanden!"
fi


## phpcs
if [ -f ${toolFolder}/phpcs ]
then
    myecho "Führe statische Code-Analyse mit PHP Codesniffer durch"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcs --colors --standard=${codeStandard} ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcs -q --standard=${codeStandard} ${classesFolder}
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
        myshortecho "Statische Code-Analyse mit PHP Codesniffer erfolgreich"
    fi
else
    myinfo "Statische Code-Analyse ausgelassen. PHP Codesniffer nicht vorhanden!"
fi


## PHPUnit
if [ -f ../../../vendor/bin/phpunit ] && [ -d ./Tests ]
then
    # PHPUnit gobal mit composer installiert
    echo
    myecho "Führe UnitTests mit globalem PHPUnit durch"
    XDEBUG_MODE=coverage ../../../vendor/bin/phpunit --configuration ${configFolder}/phpunit/phpunit.xml.dist --testdox
    tmperr=$?

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    fi
else
    myinfo "Ausführen der UnitTests ausgelassen. PHPUnit nicht vorhanden!"
fi

echo


## Zusammenfassung
if [ ${error} -ne 0 ]
then
    if [ "${VERBOSE}" != "TRUE" ]
    then
        echo
    fi

    myerror ">>>>>>>>>> Bei der Verarbeitung der Tests sind Fehler aufgetreten ! <<<<<<<<<<"
    echo
    exit 127
else

    myecho ">>>>>>>>>>>>>>>>>>>>>>> Es sind keine Fehler aufgetreten <<<<<<<<<<<<<<<<<<<<<<<"
    echo
    exit 0
fi

